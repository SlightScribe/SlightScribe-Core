<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Form\Type\AdminCommunicationNewType;
use SlightScribeBundle\Form\Type\AdminFileNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionEditController extends AdminProjectVersionController
{

    protected function build($projectId, $versionId)
    {
        parent::build($projectId, $versionId);
    }




    public function newFileAction($projectId, $versionId, Request $request)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        $file = new File();
        $file->setProjectVersion($this->projectVersion);

        $form = $this->createForm(new AdminFileNewType(), $file);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($file);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_version_file_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'versionId'=>$this->projectVersion->getPublicId(),
                    'fileId'=>$file->getPublicId(),
                )));
            }
        }


        return $this->render('SlightScribeBundle:AdminProjectVersionEdit:newFile.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'form' => $form->createView(),
        ));
    }


    public function newCommunicationAction($projectId, $versionId, Request $request)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        $communication = new Communication();
        $communication->setProjectVersion($this->projectVersion);

        $form = $this->createForm(new AdminCommunicationNewType(), $communication);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($communication);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_version_communication_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'versionId'=>$this->projectVersion->getPublicId(),
                    'communicationId'=>$communication->getPublicId(),
                )));
            }
        }


        return $this->render('SlightScribeBundle:AdminProjectVersionEdit:newCommunication.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'form' => $form->createView(),
        ));
    }



}
