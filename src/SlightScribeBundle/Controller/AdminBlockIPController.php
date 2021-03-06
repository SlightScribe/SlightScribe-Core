<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\BlockIP;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminBlockIPController extends Controller
{

    /** @var  BlockIP */
    protected $blockIP;

    protected function build($blockId)
    {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('SlightScribeBundle:BlockIP');
        $this->blockIP = $repository->findOneById($blockId);
        if (!$this->blockIP) {
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
            $this->blockIP->setFinishedAt(new \DateTime());
            $doctrine->persist($this->blockIP);
            $doctrine->flush($this->blockIP);
        }



        $runRepo = $doctrine->getRepository('SlightScribeBundle:Run');
        $runs = $runRepo->findBy(array('createdByIp' => $this->blockIP->getIp()));



        return $this->render('SlightScribeBundle:AdminBlockIP:index.html.twig', array(
            'blockIP' => $this->blockIP,
            'runs' => $runs,
        ));
    }

}
