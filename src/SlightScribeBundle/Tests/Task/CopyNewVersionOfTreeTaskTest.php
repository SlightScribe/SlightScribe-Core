<?php

namespace SlightScribeBundle\Tests\Repository;




use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\AccessPointHasField;
use SlightScribeBundle\Entity\AccessPointHasFile;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\CommunicationHasFile;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\ProjectVersionHasDefaultAccessPoint;
use SlightScribeBundle\Entity\User;
use SlightScribeBundle\Task\CopyNewVersionOfTreeTask;
use SlightScribeBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CopyNewVersionOfTreeTaskTest extends BaseTestWithDataBase
{



    public function test1() {


        // Build Data
        $user = new User();
        $user->setEmail("test@example.com");
        $user->setUsername("test");
        $user->setPassword("ouhosu");
        $this->em->persist($user);

        $project = new Project();
        $project->setTitleAdmin('Test');
        $project->setPublicId('test');
        $project->setOwner($user);
        $this->em->persist($project);

        $field = new Field();
        $field->setProject($project);
        $field->setPublicId('f1');
        $field->setType(Field::TYPE_TEXT);
        $field->setTitleAdmin('field1');
        $field->setLabel('Field1');
        $field->setDescription('This is the first field');
        $this->em->persist($field);

        $oldProjectVersion = new ProjectVersion();
        $oldProjectVersion->setProject($project);
        $oldProjectVersion->setPublicId('v1');
        $oldProjectVersion->setTitleAdmin('v1');
        $this->em->persist($oldProjectVersion);

        $file = new File();
        $file->setProjectVersion($oldProjectVersion);
        $file->setPublicId('file1');
        $file->setTitleAdmin('File1');
        $file->setFilename('file1.txt');
        $this->em->persist($file);

        $communication = new Communication();
        $communication->setPublicId('c1');
        $communication->setTitleAdmin('c1');
        $communication->setProjectVersion($oldProjectVersion);
        $communication->setSequence(1);
        $this->em->persist($communication);

        $accessPoint = new AccessPoint();
        $accessPoint->setProjectVersion($oldProjectVersion);
        $accessPoint->setPublicId('ap1');
        $accessPoint->setTitleAdmin('ap1');
        $accessPoint->setCommunication($communication);
        $this->em->persist($accessPoint);

        $newProjectVersion = new ProjectVersion();
        $newProjectVersion->setProject($project);
        $newProjectVersion->setPublicId('v2');
        $newProjectVersion->setTitleAdmin('v2');
        $newProjectVersion->setFromOldVersion($oldProjectVersion);
        $this->em->persist($newProjectVersion);

        $this->em->flush();

        $accessPointHasField = new AccessPointHasField();
        $accessPointHasField->setAccessPoint($accessPoint);
        $accessPointHasField->setField($field);
        $this->em->persist($accessPointHasField);

        $accessPointHasFile = new AccessPointHasFile();
        $accessPointHasFile->setAccessPoint($accessPoint);
        $accessPointHasFile->setFile($file);
        $this->em->persist($accessPointHasFile);

        $communicationHasFile = new CommunicationHasFile();
        $communicationHasFile->setCommunication($communication);
        $communicationHasFile->setFile($file);
        $this->em->persist($communicationHasFile);

        $projectVersionHasDefaultAccessPoint = new ProjectVersionHasDefaultAccessPoint();
        $projectVersionHasDefaultAccessPoint->setProjectVersion($oldProjectVersion);
        $projectVersionHasDefaultAccessPoint->setAccessPoint($accessPoint);
        $this->em->persist($projectVersionHasDefaultAccessPoint);

        $this->em->flush();


        $task = new CopyNewVersionOfTreeTask($this->container, $oldProjectVersion, $newProjectVersion);
        $task->go();


        ///////////// Test results

        $files = $this->em->getRepository('SlightScribeBundle:File')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($files));


        $communications = $this->em->getRepository('SlightScribeBundle:Communication')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($communications));


        $accessPoints = $this->em->getRepository('SlightScribeBundle:AccessPoint')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($accessPoints));


        $accessPointHasFields = $this->em->getRepository('SlightScribeBundle:AccessPointHasField')->findBy(array('field'=>$field));
        $this->assertEquals(2, count($accessPointHasFields));


        $accessPointHasFiles = $this->em->getRepository('SlightScribeBundle:AccessPointHasFile')->findBy(array('file'=>$file));
        $this->assertEquals(2, count($accessPointHasFiles));

        $communicationHasFiles = $this->em->getRepository('SlightScribeBundle:CommunicationHasFile')->findBy(array('file'=>$file));
        $this->assertEquals(2, count($communicationHasFiles));


        $communicationHasFiles = $this->em->getRepository('SlightScribeBundle:ProjectVersionHasDefaultAccessPoint')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($communicationHasFiles));






    }


}

