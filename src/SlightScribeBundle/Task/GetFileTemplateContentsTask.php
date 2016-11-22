<?php


namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
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

        return $this->container->get('twig')->createTemplate($file->getLetterContentTemplate())->render($twigVariables);

    }


}

