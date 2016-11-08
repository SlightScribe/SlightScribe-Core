<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use SlightScribeBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionAccessPointController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  ProjectVersion */
    protected $projectVersion;

    /** @var AccessPoint */
    protected $accessPoint;

    protected function build($projectId, $versionId, $accessPointId)
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
        $repository = $doctrine->getRepository('SlightScribeBundle:AccessPoint');
        $this->accessPoint = $repository->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=>$accessPointId));
        if (!$this->accessPoint) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $versionId, $accessPointId)
    {
        // build
        $this->build($projectId, $versionId, $accessPointId);
        //data

        $doctrine = $this->getDoctrine()->getManager();

        $repo = $doctrine->getRepository('SlightScribeBundle:File');
        $files = $repo->findForAccessPoint($this->accessPoint);


        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($this->accessPoint);

        return $this->render('SlightScribeBundle:AdminProjectVersionAccessPoint:index.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'accessPoint' => $this->accessPoint,
            'files' => $files,
            'fields' => $fields,
        ));
    }





}
