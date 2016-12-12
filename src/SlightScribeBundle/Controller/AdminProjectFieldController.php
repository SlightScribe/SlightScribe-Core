<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectFieldController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  Field */
    protected $field;

    protected function build($projectId, $fieldId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Field');
        $this->field = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$fieldId));
        if (!$this->field) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $fieldId)
    {
        // build
        $this->build($projectId, $fieldId);
        //data
        $doctrine = $this->getDoctrine()->getManager();
        $fieldRepo = $doctrine->getRepository('SlightScribeBundle:Field');

        return $this->render('SlightScribeBundle:AdminProjectField:index.html.twig', array(
            'project' => $this->project,
            'field' => $this->field,
        ));
    }


    

}
