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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class ProjectController extends Controller
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

    public function createAction($projectId, Request $request)
    {
        $this->build($projectId, $request);

        $doctrine = $this->getDoctrine()->getManager();
        $accessPointHasFieldRepository = $doctrine->getRepository('SlightScribeBundle:AccessPointHasField');

        $projectVersion = $doctrine->getRepository('SlightScribeBundle:ProjectVersion')->findPublishedVersionForProject($this->project);

        $projectVersionHasDefaultAccessPoint = $doctrine->getRepository('SlightScribeBundle:ProjectVersionHasDefaultAccessPoint')->findOneByProjectVersion($projectVersion);

        $accessPoint = $projectVersionHasDefaultAccessPoint->getAccessPoint();
        
        $fields = $doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($accessPoint);


        if ($request->getMethod() == 'POST' && $request->request->get('action') == 'new') {

            // ############## USER HAS SUBMITTED, CHECK IT!

            $email = $request->request->get('email');

            $processBlocksForDataTask = new ProcessDataForBlocksTask($this->container);
            if ($processBlocksForDataTask->process($request->getClientIp(), $email)) {
                throw new  NotFoundHttpException('Access Denied');
            }

            $hasErrors = false;
            $hasErrorsEmail = false;

            $fieldsData = array();
            foreach($fields as $field) {
                $runField = new RunHasField();
                $runField->setField($field);
                $runField->setValue($request->request->get('field_'. $field->getPublicId()));
                if ($accessPointHasFieldRepository->findOneBy(array('field'=>$field,'accessPoint'=>$accessPoint))->getIsRequired() && !$runField->hasValue()) {
                    $hasErrors = true;
                }
                $fieldsData[$field->getPublicId()] = $runField;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $hasErrors = true;
                $hasErrorsEmail = true;
            }

            if (!$hasErrors) {

                // ############## USER HAS SUBMITTED WITH NO ERRORS, SAVE!
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

                foreach ($fieldsData as $fieldData) {
                    $fieldData->setRun($run);
                    $doctrine->persist($fieldData);
                    $doctrine->flush($fieldData);
                }

                $doctrine->flush();

                $files = $doctrine->getRepository('SlightScribeBundle:File')->findForAccessPoint($accessPoint);

                return $this->render('SlightScribeBundle:Project:newRun.done.html.twig', array(
                    'project' => $this->project,
                    'projectVersion' => $projectVersion,
                    'run' => $run,
                    'files' => $files,
                ));

            } else {

                // ############## USER TRIED TO SUBMIT BUT HAD ERRORS
                $accessPointFormTask = new AccessPointFormTask($this->container, $accessPoint);


                return $this->render('SlightScribeBundle:Project:newRun.html.twig', array(
                    'form' => $accessPointFormTask->getHTMLForm($fieldsData),
                    'formIntroHTML' => $accessPoint->getFormIntroHTML(),
                    'email' => $email,
                    'hasErrors' => true,
                    'hasErrorsEmail' => $hasErrorsEmail,
                ));

            }
        }

        // ############## USER HAS NOT TRIED TO SUBMIT

        $accessPointFormTask = new AccessPointFormTask($this->container, $accessPoint);


        return $this->render('SlightScribeBundle:Project:newRun.html.twig', array(
            'form' => $accessPointFormTask->getHTMLForm(),
            'formIntroHTML' => $accessPoint->getFormIntroHTML(),
            'email' => null,
            'hasErrors' => false,
            'hasErrorsEmail' => false,
        ));

    }

    public function createWidgetAction($projectId, Request $request)
    {
        $this->build($projectId, $request);

        return $this->render('SlightScribeBundle:Project:newRunWidget.html.twig', array(
            'project' => $this->project,
        ));

    }

}
