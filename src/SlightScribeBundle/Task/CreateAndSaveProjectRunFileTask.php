<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunHasCommunication;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use SlightScribeBundle\Entity\RunHasCommunicationFile;
use SlightScribeBundle\Entity\RunHasFile;
use Swift_Attachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class CreateAndSaveProjectRunFileTask
{

    protected $container;

    protected $getFileTemplateContentsTask;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

        $this->getFileTemplateContentsTask = new GetFileTemplateContentsTask($container);
    }

    public function createAndSave(Run $run, File $file, $save = true ) {


        $doctrine = $this->container->get('doctrine')->getManager();

        $runCommunications = $doctrine->getRepository('SlightScribeBundle:RunHasCommunication')->findBy(array('run'=>$run));

        $projectRunFields = $doctrine->getRepository('SlightScribeBundle:RunHasField')->findBy(array('run'=>$run));

        $runFile = new RunHasFile();
        $runFile->setRun($run);
        $runFile->setFile($file);
        $runFile->setFilename($file->getFilename());

        $templates = $this->getFileTemplateContentsTask->get($run, $file, $projectRunFields, $runCommunications);

        $runFile->setLetterContent($templates['template']);
        $runFile->setLetterContentHeaderRight($templates['template_header_right']);

        if ($save) {
            $doctrine->persist($runFile);
            $doctrine->flush($runFile);

        }

        return $runFile;

    }


}
