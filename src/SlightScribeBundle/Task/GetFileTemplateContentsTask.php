<?php


namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\FileTemplateError;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class GetFileTemplateContentsTask {

    protected $container;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    public function get(Run $run, File $file, $projectRunFields=array(), $previousRunHasCommunications=array()) {

        $twigVariables = array(
            'projectRun' => $run,
            'fields' => array(),
            'previousCommunications' => array(),
        );

        foreach($projectRunFields as $projectRunField) {
            $twigVariables['fields'][$projectRunField->getField()->getPublicId()] = $projectRunField->getValue();
        }

        foreach($previousRunHasCommunications as $previousRunHasCommunication) {
            $twigVariables['previousCommunications'][$previousRunHasCommunication->getCommunication()->getPublicId()] = array(
                'created_at' => $previousRunHasCommunication->getCreatedAt(),
            );
        }

        try {

            $return = array();
            $return['template'] = $file->hasLetterContentTemplate() ?
                $this->container->get('twig')->createTemplate($file->getLetterContentTemplate())->render($twigVariables) :
                '';
            $return['template_header_right'] = $file->hasLetterContentTemplateHeaderRight() ?
                $this->container->get('twig')->createTemplate($file->getLetterContentTemplateHeaderRight())->render($twigVariables) :
                '';
            return $return;

        } catch (\Twig_Error $e) {

            if ($file->getId()) {
                // The if statement just checks File is already saved - might not be, in which case we don't save.
                $fileTemplateError = new FileTemplateError();
                $fileTemplateError->setFile($file);
                // We might have been passed a dummy run, in which case don't save it.
                $fileTemplateError->setRun($run->getId() ? $run : null);
                $fileTemplateError->setLetterContentTemplate($file->getLetterContentTemplate());
                $fileTemplateError->setLetterContentTemplateHeaderRight($file->getLetterContentTemplateHeaderRight());
                $fileTemplateError->setTwigVariables(json_encode($twigVariables, 0, 3));
                $fileTemplateError->setErrorCode($e->getCode());
                $fileTemplateError->setErrorFile($e->getFile());
                $fileTemplateError->setErrorLine($e->getLine());
                $fileTemplateError->setErrorMessage($e->getMessage());
                $doctrine = $this->container->get('doctrine')->getManager();
                $doctrine->persist($fileTemplateError);
                $doctrine->flush($fileTemplateError);
            }

            throw $e;
        }
    }


}

