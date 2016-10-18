<?php

namespace SlightScribeBundle\Tests\Repository;


use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Project;
use SlightScribeBundle\Entity\ProjectVersion;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasCommunication;
use SlightScribeBundle\Entity\User;
use SlightScribeBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class RunRepositoryTest extends BaseTestWithDataBase {


    public function testProjectRunWithCommunication1() {


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


        $run = new Run();
        $run->setProjectVersion($projectVersion);
        $run->setProject($project);
        $run->setPublicId('run');
        $run->setEmail('test@example.com');
        $run->setCreatedByIp('127.0.0.1');
        $this->em->persist($run);

        $this->em->flush();


        // Test
        $repo =  $this->em->getRepository('SlightScribeBundle:Run');

        // Test getProjectRunsWithAtLeastOneRunCommunication
        $runs = $repo->getActiveProjectRunsWithAtLeastOneRunCommunication($run);
        $this->assertEquals(0, count($runs));


        // Test getProjectRunsWithAtLeastOneRunCommunication
        $runs = $repo->getProjectRunsWithNoProjectRunLetters($run);
        $this->assertEquals(1, count($runs));
        $this->assertEquals('run', $runs[0]->getPublicId() );

    }

    public function testProjectRunWithOneCommunication1() {


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


        $run = new Run();
        $run->setProjectVersion($projectVersion);
        $run->setProject($project);
        $run->setPublicId('run');
        $run->setEmail('test@example.com');
        $run->setCreatedByIp('127.0.0.1');
        $this->em->persist($run);

        $this->em->flush();


        $runCommunication1 = new RunHasCommunication();
        $runCommunication1->setRun($run);
        $runCommunication1->setCommunication($communication1);
        $this->em->persist($runCommunication1);

        $this->em->flush();


        // Test
        $repo =  $this->em->getRepository('SlightScribeBundle:Run');

        // Test getProjectRunsWithNoProjectRunLetters
        $runs = $repo->getProjectRunsWithNoProjectRunLetters($run);
        $this->assertEquals(0, count($runs));


        // Test getProjectRunsWithAtLeastOneRunCommunication
        $runs = $repo->getActiveProjectRunsWithAtLeastOneRunCommunication($run);
        $this->assertEquals(1, count($runs));
        $this->assertEquals('run', $runs[0]->getPublicId() );

    }








}