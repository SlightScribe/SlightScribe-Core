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

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    public function createAndSave(Run $run, File $file, $save = true ) {


        $doctrine = $this->container->get('doctrine')->getManager();

        $twigVariables = array(
            'projectRun' => $run,
            'fields' => array(),
        );

        foreach($doctrine->getRepository('SlightScribeBundle:RunHasField')->findBy(array('run'=>$run)) as $projectRunField) {
            $twigVariables['fields'][$projectRunField->getField()->getPublicId()] = $projectRunField->getValue();
        }


        $runFile = new RunHasFile();
        $runFile->setRun($run);
        $runFile->setFile($file);
        $runFile->setFilename($file->getFilename());

        $runFile->setLetterContent($this->container->get('twig')->createTemplate($file->getLetterContentTemplate())->render($twigVariables));


        if ($save) {
            $doctrine->persist($runFile);
            $doctrine->flush($runFile);

        }

        return $runFile;

    }


}
