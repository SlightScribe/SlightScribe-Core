<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasField;
use SlightScribeBundle\Security\ProjectVoter;
use SlightScribeBundle\Task\GetFileTemplateContentsTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminProjectVersionFileController extends Controller
{



    /** @var  Project */
    protected $project;

    /** @var  ProjectVersion */
    protected $projectVersion;

    /** @var  File */
    protected $file;

    protected function build($projectId, $versionId, $fileId)
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
        $repository = $doctrine->getRepository('SlightScribeBundle:File');
        $this->file = $repository->findOneBy(array('projectVersion'=>$this->projectVersion, 'publicId'=>$fileId));
        if (!$this->file) {
            throw new  NotFoundHttpException('Not found');
        }

    }

    public function indexAction($projectId, $versionId, $fileId)
    {
        // build
        $this->build($projectId, $versionId, $fileId);
        //data
        return $this->render('SlightScribeBundle:AdminProjectVersionFile:index.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'file' => $this->file,
        ));
    }



    public function previewAction($projectId, $versionId, $fileId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $fileId);
        //data
        $doctrine = $this->getDoctrine()->getManager();

        $run = new Run();
        // Set created At in case template wants to reference it.
        $run->setCreatedAt(new \DateTime());

        $projectRunFields = array();
        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->findBy(array('project'=>$this->project));
        foreach($fields as $field) {
            $runHasField =  new RunHasField();
            $runHasField->setField($field);
            $runHasField->setValue($request->request->has('field_'.$field->getPublicId()) ? $request->request->get('field_'.$field->getPublicId()): '');
            $projectRunFields[] = $runHasField;
        }

        $task = new GetFileTemplateContentsTask($this->container);
        $templateContents = "";
        $templateContentsHeaderRight = "";
        $templateError = null;
        try {
            $templates = $task->get($run, $this->file, $projectRunFields);
            $templateContents = $templates['template'];
            $templateContentsHeaderRight = $templates['template_header_right'];
        } catch (\Exception $e) {
            $templateError = array(
                'message' => $e->getMessage(),
            );
        }

        return $this->render('SlightScribeBundle:AdminProjectVersionFile:preview.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'file' => $this->file,
            'filePreviewContents' => $templateContents,
            'filePreviewContentsHeaderRight' => $templateContentsHeaderRight,
            'filePreviewError' => $templateError,
            'fields' => $fields,
        ));
    }




}
