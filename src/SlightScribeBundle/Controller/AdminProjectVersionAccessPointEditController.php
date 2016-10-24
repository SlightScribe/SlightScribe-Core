<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Form\Type\AdminAccessPointEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionAccessPointEditController extends AdminProjectVersionAccessPointController
{


    protected function build($projectId, $versionId, $accessPointId)
    {
        parent::build($projectId, $versionId, $accessPointId);

    }

    public function editAction($projectId, $versionId, $accessPointId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $accessPointId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $form = $this->createForm(new AdminAccessPointEditType(), $this->accessPoint);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($this->accessPoint);
                $doctrine->flush();

                return $this->redirect($this->generateUrl('slight_scribe_admin_project_version_access_point_show', array(
                    'projectId'=>$this->project->getPublicId(),
                    'versionId'=>$this->projectVersion->getPublicId(),
                    'accessPointId'=>$this->accessPoint->getPublicId(),
                )));
            }
        }



        return $this->render('SlightScribeBundle:AdminProjectVersionAccessPointEdit:edit.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'accessPoint' => $this->accessPoint,
            'form' => $form->createView(),
        ));
    }


    public function editFieldsAction($projectId, $versionId, $accessPointId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $accessPointId);
        //data

        $doctrine = $this->getDoctrine()->getManager();


        $projectFieldRepo = $doctrine->getRepository('SlightScribeBundle:Field');
        $accessPointHasFieldRepo = $doctrine->getRepository('SlightScribeBundle:AccessPointHasField');

        ## TODO CSFR protection
        if ($request->request->get('action') == 'add') {
            $field = $projectFieldRepo->findOneBy(array('project'=>$this->project, 'publicId'=> $request->request->get('id') ));
            if ($field) {
                $accessPointHasFieldRepo->addFieldToAccessPoint($field, $this->accessPoint);
            }
        } else if ($request->request->get('action') == 'remove') {
            $field = $projectFieldRepo->findOneBy(array('project'=>$this->project, 'publicId'=> $request->request->get('id') ));
            if ($field) {
                $accessPointHasFieldRepo->removeFieldFromAccessPoint($field, $this->accessPoint);
            }
        }


        $projectFields = array();
        foreach($projectFieldRepo->findBy(array('project'=>$this->project)) as $field) {
            $projectFields[] = array(
                'field' => $field,
                'isExisting' => $accessPointHasFieldRepo->findOneBy(array('field'=>$field, 'accessPoint'=>$this->accessPoint)),
            );

        }
        
        return $this->render('SlightScribeBundle:AdminProjectVersionAccessPointEdit:editFields.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'accessPoint' => $this->accessPoint,
            'fields' => $projectFields,
        ));
    }




}
