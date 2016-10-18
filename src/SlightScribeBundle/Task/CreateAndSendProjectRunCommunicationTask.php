<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasCommunication;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use SlightScribeBundle\Entity\RunHasCommunicationFile;
use Swift_Attachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CreateAndSendProjectRunCommunicationTask
{

    protected $container;

    /** @var  MakeFileTask */
    protected $makeFileTask;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

        $this->makeFileTask = new MakeFileTask($container);
    }

    public function createAndSend(Run $run, Communication $communication) {

        $doctrine = $this->container->get('doctrine')->getManager();

        // Make communication, save
        list($runCommunication, $runCommunicationFiles) = $this->create($run, $communication, true);

        // Send Email
        $fromEmail = $this->container->hasParameter('from_email') ? $this->container->getParameter('from_email') : 'hello@example.com';
        $fromEmailName = $this->container->hasParameter('from_email_name') ? $this->container->getParameter('from_email_name') : 'Hello';
        
        $message = \Swift_Message::newInstance()
            ->setSubject($runCommunication->getEmailSubject())
            ->setFrom(array($fromEmail => $fromEmailName))
            ->setTo($run->getEmail())
            ->setBody($runCommunication->getEmailContentText())
            ->addPart($runCommunication->getEmailContentHTML(),'text/html');


        $tmpFileNames = array();
        foreach($runCommunicationFiles as $runCommunicationFile) {

            $fileName = $this->makeFileTask->getFileName($runCommunicationFile);

            $attachment = Swift_Attachment::fromPath($fileName);
            $attachment->setFilename($runCommunicationFile->getFileName());
            $attachment->setContentType($runCommunicationFile->getFile()->getContentType());

            $message->attach($attachment);

            $tmpFileNames[] = $fileName;

        }

        $this->container->get('mailer')->send($message);

        // Flush Que
        // from https://gist.github.com/arnaud-lb/3727254
        $this->container->get('mailer')->getTransport()->getSpool()->flushQueue($this->container->get('swiftmailer.transport.real'));


        // Mark Sent
        $runCommunication->setSentAt(new \DateTime());

        $doctrine->persist($runCommunication);
        $doctrine->flush($runCommunication);

        // Delete Tmp
        foreach($tmpFileNames as $tmpFileName) {
            unlink($tmpFileName);
        }
    }


    public function create(Run $run, Communication $communication, $save = true) {

        $doctrine = $this->container->get('doctrine')->getManager();

        $runCommunication = new RunHasCommunication();
        $runCommunication->setRun($run);
        $runCommunication->setCommunication($communication);

        $stopURL = $this->container->get('router')->generate('slight_scribe_project_run_stop', array(
                'projectId' => $run->getProject()->getPublicId(),
                'runId' => $run->getPublicId(),
                'securityKey' => $run->getSecurityKey()
            ), UrlGeneratorInterface::ABSOLUTE_URL);

        $twigVariables = array(
            'projectRun' => $run,
            'fields' => array(),
            'stop_url' => $stopURL,
        );

        foreach($doctrine->getRepository('SlightScribeBundle:RunHasField')->findBy(array('run'=>$run)) as $projectRunField) {
            $twigVariables['fields'][$projectRunField->getField()->getPublicId()] = $projectRunField->getValue();
        }

        $runCommunication->setEmailSubject($this->container->get('twig')->createTemplate($communication->getEmailSubjectTemplate())->render($twigVariables));
        $runCommunication->setEmailContentText($this->container->get('twig')->createTemplate($communication->getEmailContentTextTemplate())->render($twigVariables));
        $runCommunication->setEmailContentHTML($this->container->get('twig')->createTemplate($communication->getEmailContentHTMLTemplate())->render($twigVariables));

        $runCommunicationFiles = array();

        foreach($doctrine->getRepository('SlightScribeBundle:File')->findByCommunication($communication) as $file) {

            $runCommunicationFile = new RunHasCommunicationFile();
            $runCommunicationFile->setRun($run);
            $runCommunicationFile->setCommunication($communication);
            $runCommunicationFile->setFile($file);

            $runCommunicationFile->setFilename($file->getFilename());
            $runCommunicationFile->setLetterContent($this->container->get('twig')->createTemplate($file->getLetterContentTemplate())->render($twigVariables));

            $runCommunicationFiles[] = $runCommunicationFile;
        }


        if ($save) {
            $doctrine->persist($runCommunication);
            $doctrine->flush($runCommunication);

            foreach($runCommunicationFiles as $runCommunicationFile) {
                $doctrine->persist($runCommunicationFile);
                $doctrine->flush($runCommunicationFile);
            }
        }

        return array($runCommunication, $runCommunicationFiles);

    }


}
