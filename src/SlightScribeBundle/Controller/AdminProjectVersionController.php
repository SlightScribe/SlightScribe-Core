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
class AdminProjectVersionController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  ProjectVersion */
    protected $projectVersion;

    protected function build($projectId, $versionId)
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
        $repository = $doctrine->getRepository('SlightScribeBundle:ProjectVersion');
        $this->projectVersion = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$versionId));
        if (!$this->projectVersion) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $versionId)
    {
        // build
        $this->build($projectId, $versionId);
        //data
        $doctrine = $this->getDoctrine()->getManager();
        $projectVersionRepo = $doctrine->getRepository('SlightScribeBundle:ProjectVersion');
        $projectVersionPublished = $projectVersionRepo->findPublishedVersionForProject($this->project);

        return $this->render('SlightScribeBundle:AdminProjectVersion:index.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'isPublishedVersion'=>($projectVersionPublished ? $projectVersionPublished == $this->projectVersion : null),
        ));
    }


    public function communicationsAction($projectId, $versionId)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $projectCommunicationRepo = $doctrine->getRepository('SlightScribeBundle:Communication');
        $projectCommunications = $projectCommunicationRepo->findBy(array('projectVersion' => $this->projectVersion));


        return $this->render('SlightScribeBundle:AdminProjectVersion:communications.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'communications' => $projectCommunications,
        ));
    }

    public function runsAction($projectId, $versionId)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $runRepo = $doctrine->getRepository('SlightScribeBundle:Run');
        $runs = $runRepo->findBy(array('projectVersion' => $this->projectVersion));


        return $this->render('SlightScribeBundle:AdminProjectVersion:runs.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'runs' => $runs,
        ));
    }

    public function filesAction($projectId, $versionId)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $runRepo = $doctrine->getRepository('SlightScribeBundle:File');
        $files = $runRepo->findBy(array('projectVersion' => $this->projectVersion));

        return $this->render('SlightScribeBundle:AdminProjectVersion:files.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'files' => $files,
        ));
    }

    public function accessPointsAction($projectId, $versionId)
    {
        // build
        $this->build($projectId, $versionId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $repo = $doctrine->getRepository('SlightScribeBundle:AccessPoint');
        $accessPoints = $repo->findBy(array('projectVersion' => $this->projectVersion));

        return $this->render('SlightScribeBundle:AdminProjectVersion:accessPoints.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'accessPoints' => $accessPoints,
        ));
    }


}
