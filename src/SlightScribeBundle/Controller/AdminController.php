<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\ProjectVersionHasDefaultAccessPoint;
use SlightScribeBundle\Form\Type\AdminProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('SlightScribeBundle:Admin:index.html.twig');
    }



    public function projectsAction()
    {

        $doctrine = $this->getDoctrine()->getManager();
        $projectRepo = $doctrine->getRepository('SlightScribeBundle:Project');
        $projects = $projectRepo->findAll();

        return $this->render('SlightScribeBundle:Admin:projects.html.twig', array('projects'=>$projects));
    }

    public function blockIPsAction()
    {

        $doctrine = $this->getDoctrine()->getManager();
        $repo = $doctrine->getRepository('SlightScribeBundle:BlockIP');
        $blockIPs = $repo->findAll();

        return $this->render('SlightScribeBundle:Admin:blockIPs.html.twig', array('blockIPs'=>$blockIPs));
    }

    public function blockEmailsAction()
    {

        $doctrine = $this->getDoctrine()->getManager();
        $repo = $doctrine->getRepository('SlightScribeBundle:BlockEmail');
        $blockEmails = $repo->findAll();

        return $this->render('SlightScribeBundle:Admin:blockEmails.html.twig', array('blockEmails'=>$blockEmails));
    }

    public function newProjectAction() {


        $doctrine = $this->getDoctrine()->getManager();

        $project = new Project();
        $project->setOwner($this->getUser());

        $form = $this->createForm(new AdminProjectNewType(), $project);
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($project);

                $projectVersion = new ProjectVersion();
                $projectVersion->setProject($project);
                $doctrine->persist($projectVersion);

                $communication = new Communication();
                $communication->setProjectVersion($projectVersion);
                $communication->setTitleAdmin('First');
                $communication->setPublicId('first');
                $communication->setSequence(1);
                $doctrine->persist($communication);

                $accessPoint = new AccessPoint();
                $accessPoint->setProjectVersion($projectVersion);
                $accessPoint->setPublicId('start');
                $accessPoint->setTitleAdmin('Start');
                $accessPoint->setCommunication($communication);
                $doctrine->persist($accessPoint);

                $doctrine->flush();

                $projectVersionHasDefaultAccessPoint = new ProjectVersionHasDefaultAccessPoint();
                $projectVersionHasDefaultAccessPoint->setProjectVersion($projectVersion);
                $projectVersionHasDefaultAccessPoint->setAccessPoint($accessPoint);
                $doctrine->persist($projectVersionHasDefaultAccessPoint);

                $doctrine->flush();


                return $this->redirect($this->generateUrl('slight_scribe_admin_project_show', array('projectId'=>$project->getPublicId())));
            }
        }

        return $this->render('SlightScribeBundle:Admin:newProject.html.twig', array(
            'form' => $form->createView(),
        ));

    }


}
