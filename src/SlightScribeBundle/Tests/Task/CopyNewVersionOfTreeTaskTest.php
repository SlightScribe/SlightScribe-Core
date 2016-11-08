<?php

namespace SlightScribeBundle\Tests\Repository;




use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
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

        $task = new CopyNewVersionOfTreeTask($this->container, $oldProjectVersion, $newProjectVersion);
        $task->go();


        ///////////// Test results

        $files = $this->em->getRepository('SlightScribeBundle:File')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($files));


        $communications = $this->em->getRepository('SlightScribeBundle:Communication')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($communications));


        $accessPoints = $this->em->getRepository('SlightScribeBundle:AccessPoint')->findBy(array('projectVersion'=>$newProjectVersion));
        $this->assertEquals(1, count($accessPoints));





    }


}

