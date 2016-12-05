<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectCommunication;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasField;
use SlightScribeBundle\Security\ProjectVoter;
use SlightScribeBundle\Task\GetCommunicationTemplatesTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
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

    public function previewAction($projectId, $versionId, $communicationId, Request $request)
    {
        // build
        $this->build($projectId, $versionId, $communicationId);
        //data
        $doctrine = $this->getDoctrine()->getManager();

        $run = new Run();
        $run->setProject($this->project);
        $run->setPublicId('enf7vecgine0omkrlo2capruwre0buzstoi6salaea2phred');
        $run->setSecurityKey('sym7mytpey0unkcoum6atfakabu8gavaoy0morhu');

        $projectRunFields = array();
        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->findBy(array('project'=>$this->project));
        foreach($fields as $field) {
            $runHasField =  new RunHasField();
            $runHasField->setField($field);
            $runHasField->setValue($request->request->has('field_'.$field->getPublicId()) ? $request->request->get('field_'.$field->getPublicId()): '');
            $projectRunFields[] = $runHasField;
        }

        // For now just passing blank. Maybe have options for user to set things in here later?
        $previousRunHasCommunications = array();

        $task = new GetCommunicationTemplatesTask($this->container);
        $templates = null;
        $templateError = null;
        try {
            $templates = $task->get($run, $this->communication, $projectRunFields, $previousRunHasCommunications);
        } catch (\Exception $e) {
            $templateError = array(
                'message' => $e->getMessage(),
            );
        }

        return $this->render('SlightScribeBundle:AdminProjectVersionCommunication:preview.html.twig', array(
            'project' => $this->project,
            'version' => $this->projectVersion,
            'communication' => $this->communication,
            'fields' => $fields,
            'communicationPreviewSubject' => $templates['subject'],
            'filePreviewContentsHTML' => $templates['html'],
            'filePreviewContentsText' => $templates['text'],
            'templatesError' => $templateError,
        ));
    }



}
