<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use SlightScribeBundle\Form\Type\AdminCommunicationEditType;
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





}
