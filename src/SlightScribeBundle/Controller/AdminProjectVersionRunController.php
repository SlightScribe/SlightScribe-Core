<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionRunController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  ProjectVersion */
    protected $projectVersion;

    /** @var Run */
    protected $run;

    protected function build($projectId, $versionId, $runId)
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
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Run');
        $this->run = $repository->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=>$runId));
        if (!$this->run) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $versionId, $runId)
    {
        // build
        $this->build($projectId, $versionId, $runId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $runFields = $doctrine->getRepository('SlightScribeBundle:RunHasField')->findBy(array('run'=>$this->run));

        return $this->render('SlightScribeBundle:AdminProjectVersionRun:index.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'run' => $this->run,
            'runFields' => $runFields,
        ));

    }





}
