<?php

namespace SlightScribeBundle\Command;

use SlightScribeBundle\Task\CreateAndSendProjectRunCommunicationTask;
use SlightScribeBundle\Task\CreateProjectRunCommunicationTask;
use SlightScribeBundle\Task\IsFurtherCommunicationReadyToSendTask;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class SendFurtherCommunicationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slightscribe:send-further-communications')
            ->setDescription('Send Further Communications');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine')->getManager();
        $createAndSendProjectRunCommunicationTask = new CreateAndsendProjectRunCommunicationTask($this->getContainer());
        $isFurtherCommunicationReadyToSendTask = new IsFurtherCommunicationReadyToSendTask(new \DateTime());

        foreach($doctrine->getRepository('SlightScribeBundle:Run')->getActiveProjectRunsWithAtLeastOneRunCommunication()  as $projectRun) {

            $output->writeln("Project Run " . $projectRun->getPublicId());

            // Get last communication sent
            $lastRunCommunication = $doctrine->getRepository('SlightScribeBundle:RunCommunication')->getLastForRun($projectRun);

            // Get next communication
            $nextCommunication =  $doctrine->getRepository('SlightScribeBundle:Communication')->getNextAfter($lastRunCommunication->getCommunication());

            if ($nextCommunication) {

                if ($isFurtherCommunicationReadyToSendTask->go($nextCommunication, $lastRunCommunication)) {

                    $output->writeln("Sending Next Communication ".$nextCommunication->getPublicId());
                    $createAndSendProjectRunCommunicationTask->createAndSend($projectRun, $nextCommunication);

                }

            } else {

                $output->writeln("Run has finished naturally.");
                $projectRun->setFinishedNaturallyAt(new \DateTime());
                $doctrine->persist($projectRun);
                $doctrine->flush($projectRun);

            }

        }

        $output->writeln('Finished All');

    }

}

