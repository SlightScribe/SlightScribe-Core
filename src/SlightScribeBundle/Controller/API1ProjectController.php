<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunAccessPoint;
use SlightScribeBundle\Entity\RunHasField;
use SlightScribeBundle\Entity\RunUsedAccessPoint;
use SlightScribeBundle\Form\Type\ProjectRunCreate;
use SlightScribeBundle\Task\AccessPointFormTask;
use SlightScribeBundle\Task\ProcessDataForBlocksTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class API1ProjectController extends Controller
{

    /** @var  Project */
    protected  $project;

    protected function build($projectId, Request $request) {

        $doctrine = $this->getDoctrine()->getManager();
        // load
        $this->project = $doctrine->getRepository('SlightScribeBundle:Project')->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        // test blocks
        if ($doctrine->getRepository('SlightScribeBundle:BlockIP')->isAddressBlocked($request->getClientIp())) {
            throw new  NotFoundHttpException('Access Denied');
        }

    }

    protected function getData() {
        $doctrine = $this->getDoctrine()->getManager();

        $data = array('fields'=> array());


        $projectVersion = $doctrine->getRepository('SlightScribeBundle:ProjectVersion')->findPublishedVersionForProject($this->project);

        $projectVersionHasDefaultAccessPoint = $doctrine->getRepository('SlightScribeBundle:ProjectVersionHasDefaultAccessPoint')->findOneByProjectVersion($projectVersion);

        $accessPoint = $projectVersionHasDefaultAccessPoint->getAccessPoint();
        $data['form'] = $accessPoint->getForm();

        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($accessPoint);
        foreach($fields as $field) {
            $data['fields'][] = array(
                'id' => $field->getPublicId(),
                'type' => ($field->isTypeText() ? 'text' : ($field->isTypeTextArea() ? 'textarea' : ($field->isTypeDate() ? 'date' : 'unknown'))),
            );
        }


        return $data;

    }


    public function dataJSONPAction($projectId, Request $request)
    {
        $this->build($projectId, $request);

        $data = $this->getData();
        $func  = $request->query->get('callback');
        if (!$func) {
            $func='callback';
        }
        $response = new Response($func . "(" . json_encode($data) . ")");
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
    }

    public function dataJSONAction($projectId, Request $request)
    {
        $this->build($projectId, $request);
        $data = $this->getData();
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    protected function saveData(Request $request) {
        $data = array('files'=>array());

        $doctrine = $this->getDoctrine()->getManager();

        $projectVersion = $doctrine->getRepository('SlightScribeBundle:ProjectVersion')->findPublishedVersionForProject($this->project);

        $projectVersionHasDefaultAccessPoint = $doctrine->getRepository('SlightScribeBundle:ProjectVersionHasDefaultAccessPoint')->findOneByProjectVersion($projectVersion);

        $accessPoint = $projectVersionHasDefaultAccessPoint->getAccessPoint();

        $email = $request->request->get('email');

        $processBlocksForDataTask = new ProcessDataForBlocksTask($this->container);
        if ($processBlocksForDataTask->process($request->getClientIp(), $email)) {
            throw new  NotFoundHttpException('Access Denied');
        }

        // Actually Save!

        // TODO check required fields set and error!

        $run = new Run();
        $run->setProject($this->project);
        $run->setProjectVersion($projectVersion);
        $run->setEmail($email);
        $run->setCreatedByIp($request->getClientIp());
        $doctrine->persist($run);
        $doctrine->flush($run);

        $runUsedAccessPoint = new RunUsedAccessPoint();
        $runUsedAccessPoint->setAccessPoint($accessPoint);
        $runUsedAccessPoint->setRun($run);
        $doctrine->persist($runUsedAccessPoint);
        $doctrine->flush($runUsedAccessPoint);

        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($accessPoint);

        foreach($fields as $field) {
            $runField = new RunHasField();
            $runField->setField($field);
            $runField->setRun($run);
            $runField->setValue($request->request->get('field_'. $field->getPublicId()));
            $doctrine->persist($runField);
            $doctrine->flush($runField);
        }

        $doctrine->flush();

        foreach($doctrine->getRepository('SlightScribeBundle:File')->findForAccessPoint($accessPoint) as $file) {
            $data['files'][] = array(
                'url'=>$this->generateUrl('slight_scribe_project_run_file', array(
                    'projectId'=>$this->project->getPublicId(),
                    'runId'=>$run->getPublicId(),
                    'fileId'=>$file->getPublicId(),
                    'securityKey'=>$run->getSecurityKey(),
                )),
                'filename'=>$file->getFileName(),
            );

        }

        return $data;
    }

    public function actionJSONPAction($projectId, Request $request)
    {
        $this->build($projectId, $request);

        $data = $this->saveData($request);
        $func  = $request->query->get('callback');
        if (!$func) {
            $func='callback';
        }
        $response = new Response($func . "(" . json_encode($data) . ")");
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
    }

    public function actionJSONAction($projectId, Request $request)
    {
        $this->build($projectId, $request);
        $data = $this->saveData($request);
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
