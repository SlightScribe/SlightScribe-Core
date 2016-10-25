<?php

namespace SlightScribeBundle\Controller;

use SlightScribeBundle\Entity\BlockEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function indexAction($blockId)
    {
        // build
        $this->build($blockId);
        //data


        return $this->render('SlightScribeBundle:AdminBlockEmail:index.html.twig', array(
            'blockEmail' => $this->blockEmail,
        ));
    }

}
