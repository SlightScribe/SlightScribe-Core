<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
*  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
*  @link https://github.com/SlightScribe/SlightScribe-Core
*/
class CopyNewVersionOfTreeTask
{


    protected $container;

    protected $oldVersion;

    protected $newVersion;


    /**
     * @param $container
     */
    public function __construct($container, ProjectVersion $oldVersion, ProjectVersion $newVersion)
    {
        $this->container = $container;
        $this->oldVersion = $oldVersion;
        $this->newVersion = $newVersion;
    }

    public function go() {


        $doctrine = $this->container->get('doctrine')->getManager();

        $files = array();
        foreach($doctrine->getRepository('SlightScribeBundle:File')->findBy(array('projectVersion'=>$this->oldVersion)) as $oldFile) {

            $files[$oldFile->getPublicId()] = new File();
            $files[$oldFile->getPublicId()]->setProjectVersion($this->newVersion);
            $files[$oldFile->getPublicId()]->copyFromOld($oldFile);
            $doctrine->persist($files[$oldFile->getPublicId()]);
            $doctrine->flush($files[$oldFile->getPublicId()]);

        }

        $communications = array();
        foreach($doctrine->getRepository('SlightScribeBundle:Communication')->findBy(array('projectVersion'=>$this->oldVersion)) as $oldCommunication) {

            $communications[$oldCommunication->getPublicId()] = new Communication();
            $communications[$oldCommunication->getPublicId()]->setProjectVersion($this->newVersion);
            $communications[$oldCommunication->getPublicId()]->copyFromOld($oldCommunication);
            $doctrine->persist($communications[$oldCommunication->getPublicId()]);
            $doctrine->flush($communications[$oldCommunication->getPublicId()]);

        }

        $accessPoints = array();
        foreach($doctrine->getRepository('SlightScribeBundle:AccessPoint')->findBy(array('projectVersion'=>$this->oldVersion)) as $oldAccessPoint) {

            $accessPoints[$oldAccessPoint->getPublicId()] = new AccessPoint();
            $accessPoints[$oldAccessPoint->getPublicId()]->setProjectVersion($this->newVersion);
            $accessPoints[$oldAccessPoint->getPublicId()]->copyFromOld($oldAccessPoint);
            $accessPoints[$oldAccessPoint->getPublicId()]->setCommunication($communications[$oldAccessPoint->getCommunication()->getPublicId()]);
            $doctrine->persist($accessPoints[$oldAccessPoint->getPublicId()]);
            $doctrine->flush($accessPoints[$oldAccessPoint->getPublicId()]);

        }

        // TODO AccessPointHasField

        // TODO AccessPointHasFile

        // TODO CommunicationHasFile

        // TODO ProjectVersionHasDefaultAccessPoint




    }

}
