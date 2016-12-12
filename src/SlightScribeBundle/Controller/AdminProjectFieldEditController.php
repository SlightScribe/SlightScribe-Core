<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Form\Type\AdminFieldEditType;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectFieldEditController extends AdminProjectFieldController
{

    protected function build($projectId, $fieldId)
    {
        parent::build($projectId, $fieldId);
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);
    }

    public function editAction($projectId, $fieldId, Request $request)
    {
        // build
        $this->build($projectId, $fieldId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AdminFieldEditType(), $this->field);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($this->field);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_field_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'fieldId'=>$this->field->getPublicId(),
                )));
            }
        }


        return $this->render('SlightScribeBundle:AdminProjectFieldEdit:edit.html.twig', array(
            'project' => $this->project,
            'field' => $this->field,
            'form' => $form->createView(),
        ));
    }


}
