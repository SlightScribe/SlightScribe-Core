<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Form\Type\AdminFileEditType;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionFileEditController extends AdminProjectVersionFileController
{


    protected function build($projectId, $versionId, $fileId)
    {
        parent::build($projectId, $versionId, $fileId);
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);

    }

    public function editAction($projectId, $versionId, $fileId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $fileId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $form = $this->createForm(new AdminFileEditType(), $this->file);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($this->file);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_version_file_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'versionId'=>$this->projectVersion->getPublicId(),
                    'fileId'=>$this->file->getPublicId(),
                )));
            }
        }



        return $this->render('SlightScribeBundle:AdminProjectVersionFileEdit:edit.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'file' => $this->file,
            'form' => $form->createView(),
        ));
    }




}
