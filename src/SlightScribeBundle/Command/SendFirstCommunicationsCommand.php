<?php

namespace SlightScribeBundle\Command;

use SlightScribeBundle\Task\CreateAndSendProjectRunCommunicationTask;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
*  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
*  @link https://github.com/SlightScribe/SlightScribe-Core
*/
class SendFirstCommunicationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slightscribe:send-first-communications')
            ->setDescription('Send First Communications');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine')->getManager();
        $createAndSendProjectRunCommunicationTask = new CreateAndSendProjectRunCommunicationTask($this->getContainer());

        foreach($doctrine->getRepository('SlightScribeBundle:Run')->getProjectRunsWithNoProjectRunLetters()  as $projectRun) {

            $output->writeln("Project ".$projectRun->getProject()->getPublicId()." Run ". $projectRun->getPublicId());

            $projectCommunication = $doctrine->getRepository('SlightScribeBundle:Communication')->getFirstForProjectVersion($projectRun->getProjectVersion());

            try {
                $createAndSendProjectRunCommunicationTask->createAndSend($projectRun, $projectCommunication);
            } catch(\Exception $e) {
                $this->getContainer()->get('logger')->error(
                    'Error While Trying to send first communication for project '.$projectRun->getProject()->getPublicId()." Run ". $projectRun->getPublicId(). " Error ". $e->getMessage()
                );
                $output->writeln("Error ".$e->getMessage());
            }

        }

        $output->writeln('Finished All');

    }

}

