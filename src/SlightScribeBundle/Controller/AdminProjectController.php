<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectController extends Controller
{

    /** @var  Project */
    protected $project;

    protected function build($projectId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        // TODO security $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);

    }

    public function indexAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $projectVersionRepo = $doctrine->getRepository('SlightScribeBundle:ProjectVersion');

        return $this->render('SlightScribeBundle:AdminProject:index.html.twig', array(
            'project' => $this->project,
            'publishedProjectVersion'=>$projectVersionRepo->findPublishedVersionForProject($this->project),
            'latestProjectVersion'=>$projectVersionRepo->findLatestVersionForProject($this->project),
        ));
    }

    public function versionsAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $projectVersionRepo = $doctrine->getRepository('SlightScribeBundle:ProjectVersion');
        $projectVersions = $projectVersionRepo->findBy(array('project'=>$this->project));


        return $this->render('SlightScribeBundle:AdminProject:versions.html.twig', array(
            'project' => $this->project,
            'versions' => $projectVersions,
        ));
    }

    public function fieldsAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $projectFieldRepo = $doctrine->getRepository('SlightScribeBundle:Field');
        $projectFields = $projectFieldRepo->findBy(array('project'=>$this->project));


        return $this->render('SlightScribeBundle:AdminProject:fields.html.twig', array(
            'project' => $this->project,
            'fields' => $projectFields,
        ));
    }

    public function versionsPublishedAction($projectId)
    {
        // build
        $this->build($projectId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $projectVersionPublishedRepo = $doctrine->getRepository('SlightScribeBundle:ProjectVersionPublished');
        $projectVersionPublished = $projectVersionPublishedRepo->findAllForProject($this->project);


        return $this->render('SlightScribeBundle:AdminProject:versionsPublished.html.twig', array(
            'project' => $this->project,
            'projectVersionsPublished' => $projectVersionPublished,
        ));
    }

}
