<?php

namespace SlightScribeBundle\Tests\Repository;




use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\User;
use SlightScribeBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CommunicationRepositoryTest extends BaseTestWithDataBase {


    public function testGetFirstForProjectVersion1() {


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

        $projectVersion = new ProjectVersion();
        $projectVersion->setProject($project);
        $projectVersion->setPublicId('v1');
        $projectVersion->setTitleAdmin('v1');
        $this->em->persist($projectVersion);

        $communication1 = new Communication();
        $communication1->setPublicId('1');
        $communication1->setProjectVersion($projectVersion);
        $communication1->setSequence(100);
        $communication1->setTitleAdmin('1');
        $this->em->persist($communication1);

        $communication2 = new Communication();
        $communication2->setPublicId('2');
        $communication2->setProjectVersion($projectVersion);
        $communication2->setSequence(200);
        $communication2->setTitleAdmin('2');
        $this->em->persist($communication2);


        $this->em->flush();


        // Test results
        $repo =  $this->em->getRepository('SlightScribeBundle:Communication');

        $communication = $repo->getFirstForProjectVersion($projectVersion);

        $this->assertEquals(100, $communication->getSequence());



    }

    public function testGetNextAfter1() {


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

        $projectVersion = new ProjectVersion();
        $projectVersion->setProject($project);
        $projectVersion->setPublicId('v1');
        $projectVersion->setTitleAdmin('v1');
        $this->em->persist($projectVersion);

        $communication1 = new Communication();
        $communication1->setPublicId('1');
        $communication1->setProjectVersion($projectVersion);
        $communication1->setSequence(100);
        $communication1->setTitleAdmin('1');
        $this->em->persist($communication1);

        $communication2 = new Communication();
        $communication2->setPublicId('2');
        $communication2->setProjectVersion($projectVersion);
        $communication2->setSequence(200);
        $communication2->setTitleAdmin('2');
        $this->em->persist($communication2);

        $communication3 = new Communication();
        $communication3->setPublicId('3');
        $communication3->setProjectVersion($projectVersion);
        $communication3->setSequence(300);
        $communication3->setTitleAdmin('3');
        $this->em->persist($communication3);


        $this->em->flush();


        // Test results
        $repo =  $this->em->getRepository('SlightScribeBundle:Communication');

        $communication = $repo->getNextAfter($communication1);

        $this->assertEquals(200, $communication->getSequence());



    }



}