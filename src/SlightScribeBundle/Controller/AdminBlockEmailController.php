<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\BlockEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminBlockEmailController extends Controller
{

    /** @var  BlockEmail */
    protected $blockEmail;

    protected function build($blockId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:BlockEmail');
        $this->blockEmail = $repository->findOneById($blockId);
        if (!$this->blockEmail) {
            throw new  NotFoundHttpException('Not found');
        }


    }

    public function indexAction($blockId, Request $request)
    {
        // build
        $this->build($blockId);
        //data
        $doctrine = $this->getDoctrine()->getManager();

        // TODO CSFR
        if ($request->request->get('action') == 'finish') {
            $this->blockEmail->setFinishedAt(new \DateTime());
            $doctrine->persist($this->blockEmail);
            $doctrine->flush($this->blockEmail);
        }

        $runRepo = $doctrine->getRepository('SlightScribeBundle:Run');
        $runs = $runRepo->findBy(array('emailClean' => $this->blockEmail->getEmailClean()));



        return $this->render('SlightScribeBundle:AdminBlockEmail:index.html.twig', array(
            'blockEmail' => $this->blockEmail,
            'runs' => $runs,
        ));
    }

}
