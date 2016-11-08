<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\AccessPointHasField;
use SlightScribeBundle\Entity\AccessPointHasFile;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\CommunicationHasFile;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\ProjectVersionHasDefaultAccessPoint;
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

            foreach($doctrine->getRepository('SlightScribeBundle:CommunicationHasFile')->findBy(array('communication'=>$oldCommunication)) as $oldCommunicatonHasFile) {

                $communicationHasFile = new CommunicationHasFile();
                $communicationHasFile->setFile($oldCommunicatonHasFile->getFile());
                $communicationHasFile->setCommunication($communications[$oldCommunication->getPublicId()]);
                $doctrine->persist($communicationHasFile);
                $doctrine->flush($communicationHasFile);

            }
        }

        $accessPoints = array();
        foreach($doctrine->getRepository('SlightScribeBundle:AccessPoint')->findBy(array('projectVersion'=>$this->oldVersion)) as $oldAccessPoint) {

            $accessPoints[$oldAccessPoint->getPublicId()] = new AccessPoint();
            $accessPoints[$oldAccessPoint->getPublicId()]->setProjectVersion($this->newVersion);
            $accessPoints[$oldAccessPoint->getPublicId()]->copyFromOld($oldAccessPoint);
            $accessPoints[$oldAccessPoint->getPublicId()]->setCommunication($communications[$oldAccessPoint->getCommunication()->getPublicId()]);
            $doctrine->persist($accessPoints[$oldAccessPoint->getPublicId()]);
            $doctrine->flush($accessPoints[$oldAccessPoint->getPublicId()]);


            foreach($doctrine->getRepository('SlightScribeBundle:AccessPointHasField')->findBy(array('accessPoint'=>$oldAccessPoint)) as $oldAccessPointHasField) {

                $accessPointHasField = new AccessPointHasField();
                $accessPointHasField->setField($oldAccessPointHasField->getField());
                $accessPointHasField->setAccessPoint($accessPoints[$oldAccessPoint->getPublicId()]);
                $doctrine->persist($accessPointHasField);
                $doctrine->flush($accessPointHasField);

            }

            foreach($doctrine->getRepository('SlightScribeBundle:AccessPointHasFile')->findBy(array('accessPoint'=>$oldAccessPoint)) as $oldAccessPointHasFile) {

                $accessPointHasFile = new AccessPointHasFile();
                $accessPointHasFile->setFile($oldAccessPointHasFile->getFile());
                $accessPointHasFile->setAccessPoint($accessPoints[$oldAccessPoint->getPublicId()]);
                $doctrine->persist($accessPointHasFile);
                $doctrine->flush($accessPointHasFile);

            }

        }

        $oldProjectVersionHasDefaultAccessPoint = $doctrine->getRepository('SlightScribeBundle:ProjectVersionHasDefaultAccessPoint')->findOneBy(array('projectVersion'=>$this->oldVersion));
        if ($oldProjectVersionHasDefaultAccessPoint) {

            $projectVersionHasDefaultAccessPoint = new ProjectVersionHasDefaultAccessPoint();
            $projectVersionHasDefaultAccessPoint->setProjectVersion($this->newVersion);
            $projectVersionHasDefaultAccessPoint->setAccessPoint($accessPoints[$oldProjectVersionHasDefaultAccessPoint->getAccessPoint()->getPublicId()]);
            $doctrine->persist($projectVersionHasDefaultAccessPoint);
            $doctrine->flush($projectVersionHasDefaultAccessPoint);

        }

    }

}
