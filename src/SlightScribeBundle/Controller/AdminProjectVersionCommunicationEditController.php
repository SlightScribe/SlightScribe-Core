<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use SlightScribeBundle\Form\Type\AdminCommunicationEditType;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionCommunicationEditController extends AdminProjectVersionCommunicationController
{


    protected function build($projectId, $versionId, $communicationId)
    {
        parent::build($projectId, $versionId, $communicationId);
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);

    }

    public function editAction($projectId, $versionId, $communicationId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $communicationId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $form = $this->createForm(new AdminCommunicationEditType(), $this->communication);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($this->communication);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_version_communication_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'versionId'=>$this->projectVersion->getPublicId(),
                    'communicationId'=>$this->communication->getPublicId(),
                    )));
            }
        }



        return $this->render('SlightScribeBundle:AdminProjectVersionCommunicationEdit:edit.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'communication' => $this->communication,
            'form' => $form->createView(),
        ));
    }


    public function editFilesAction($projectId, $versionId, $communicationId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $communicationId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $projectFileRepo = $doctrine->getRepository('SlightScribeBundle:File');
        $communicationHasFileRepo = $doctrine->getRepository('SlightScribeBundle:CommunicationHasFile');

        ## TODO CSFR protection
        if ($request->request->get('action') == 'add') {
            $file = $projectFileRepo->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=> $request->request->get('id') ));
            if ($file) {
                $communicationHasFileRepo->addFileToCommunication($file, $this->communication);
            }
        } else if ($request->request->get('action') == 'remove') {
            $file = $projectFileRepo->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=> $request->request->get('id') ));
            if ($file) {
                $communicationHasFileRepo->removeFileFromCommunication($file, $this->communication);
            }
        }


        $projectFiles = array();
        foreach($projectFileRepo->findBy(array('projectVersion'=>$this->projectVersion)) as $file) {
            $projectFiles[] = array(
                'file' => $file,
                'isExisting' => $communicationHasFileRepo->findOneBy(array('file'=>$file, 'communication'=>$this->communication)),
            );

        }

        return $this->render('SlightScribeBundle:AdminProjectVersionCommunicationEdit:editFiles.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'communication' => $this->communication,
            'files' => $projectFiles,
        ));
    }




}
