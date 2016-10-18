<?php

namespace SlightScribeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SlightScribeBundle:Default:index.html.twig');
    }
}
