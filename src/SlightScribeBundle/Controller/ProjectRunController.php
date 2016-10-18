<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasField;
use SlightScribeBundle\Form\Type\ProjectRunCreate;
use SlightScribeBundle\Form\Type\ProjectRunStop;
use SlightScribeBundle\Task\CreateAndSaveProjectRunFileTask;
use SlightScribeBundle\Task\MakeFileTask;
use SlightScribeBundle\Task\ProcessDataForBlocksTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectRunController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var Run */
    protected $run;

    protected function build($projectId, $runId, $securityKey, Request $request)
    {

        $doctrine = $this->getDoctrine()->getManager();
        // load
        $this->project = $doctrine->getRepository('SlightScribeBundle:Project')->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        // load
        $this->run = $doctrine->getRepository('SlightScribeBundle:Run')->findOneBy(array('project' => $this->project, 'publicId' => $runId));
        if (!$this->run) {
            throw new  NotFoundHttpException('Not found');
        }
        // load
        if ($this->run->getSecurityKey() != $securityKey) {
            throw new  NotFoundHttpException('Not found');
        }


    }

    public function stopAction($projectId, $runId, $securityKey, Request $request)
    {
        $this->build($projectId, $runId, $securityKey, $request);

        $doctrine = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ProjectRunStop());
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $this->run->setStoppedManuallyAt(new \DateTime());
                $doctrine->persist($this->run);
                $doctrine->flush($this->run);

                return $this->redirect($this->run->getProjectVersion()->getRedirectUserToAfterManualStop());
            }
        }

        return $this->render('SlightScribeBundle:ProjectRun:stop.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function fileAction($projectId, $runId, $securityKey, $fileId, Request $request)
    {
        $this->build($projectId, $runId, $securityKey, $request);


        $doctrine = $this->getDoctrine()->getManager();
        $projectVersion = $doctrine->getRepository('SlightScribeBundle:ProjectVersion')->findPublishedVersionForProject($this->project);

        $file = $doctrine->getRepository('SlightScribeBundle:File')->findOneBy(array('projectVersion' => $projectVersion, 'publicId' => $fileId));
        if (!$file) {
            throw new  NotFoundHttpException('Not found');
        }


        $runFile =  $doctrine->getRepository('SlightScribeBundle:RunHasFile')->findOneBy(array('run' => $this->run, 'file' => $file));

        if (!$runFile) {
            $task = new CreateAndSaveProjectRunFileTask($this->container);
            $runFile = $task->createAndSave($this->run, $file, true);
        }

        $task = new MakeFileTask($this->container);
        $filename = $task->getFileName($runFile);

        $response = new Response(file_get_contents($filename), 200);
        $response->headers->set('Content-Type', $file->getContentType());
        // TODO set no cache headers
        return $response;


    }

}