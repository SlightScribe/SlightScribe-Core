<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionCommunicationController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  ProjectVersion */
    protected $projectVersion;

    /** @var Communication */
    protected $communication;

    protected function build($projectId, $versionId, $communicationId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        // TODO security $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:ProjectVersion');
        $this->projectVersion = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$versionId));
        if (!$this->projectVersion) {
            throw new  NotFoundHttpException('Not found');
        }
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:Communication');
        $this->communication = $repository->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=>$communicationId));
        if (!$this->communication) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $versionId, $communicationId)
    {
        // build
        $this->build($projectId, $versionId, $communicationId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $repo = $doctrine->getRepository('SlightScribeBundle:File');
        $files = $repo->findByCommunication($this->communication);

        return $this->render('SlightScribeBundle:AdminProjectVersionCommunication:index.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'communication' => $this->communication,
            'files' => $files,
        ));
    }





}
