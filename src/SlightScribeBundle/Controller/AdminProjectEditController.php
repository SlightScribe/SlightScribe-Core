<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Form\Type\AdminFieldNewType;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectEditController extends AdminProjectController
{

    protected function build($projectId)
    {
       parent::build($projectId);
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $this->project);
    }

    public function newFieldTextAction($projectId) {

        $this->build($projectId);

        $doctrine = $this->getDoctrine()->getManager();

        $field = new Field();
        $field->setProject($this->project);
        $field->setType(Field::TYPE_TEXT);

        $form = $this->createForm(new AdminFieldNewType(), $field);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($field);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_fields_list', array('projectId'=>$this->project->getPublicId())));
            }
        }

        return $this->render('SlightScribeBundle:AdminProjectEdit:newFieldText.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }

    public function newFieldTextAreaAction($projectId) {

        $this->build($projectId);

        $doctrine = $this->getDoctrine()->getManager();

        $field = new Field();
        $field->setProject($this->project);
        $field->setType(Field::TYPE_TEXTAREA);

        $form = $this->createForm(new AdminFieldNewType(), $field);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($field);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_fields_list', array('projectId'=>$this->project->getPublicId())));
            }
        }

        return $this->render('SlightScribeBundle:AdminProjectEdit:newFieldTextArea.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }

}
