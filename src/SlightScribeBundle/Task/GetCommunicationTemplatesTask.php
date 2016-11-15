<?php


namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\File;
use SlightScribeBundle\Entity\Run;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class GetCommunicationTemplatesTask {

    protected $container;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    public function get(Run $run, Communication $communication, $projectRunFields=array(), $previousRunHasCommunications=array()) {

        $stopURL = $this->container->get('router')->generate('slight_scribe_project_run_stop', array(
            'projectId' => $run->getProject()->getPublicId(),
            'runId' => $run->getPublicId(),
            'securityKey' => $run->getSecurityKey()
        ), UrlGeneratorInterface::ABSOLUTE_URL);

        $twigVariables = array(
            'projectRun' => $run,
            'fields' => array(),
            'stop_url' => $stopURL,
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

        $return = array();

        $return['subject'] = $this->container->get('twig')->createTemplate($communication->getEmailSubjectTemplate())->render($twigVariables);
        $return['text'] = $this->container->get('twig')->createTemplate($communication->getEmailContentTextTemplate())->render($twigVariables);
        $return['html'] = $this->container->get('twig')->createTemplate($communication->getEmailContentHTMLTemplate())->render($twigVariables);

        return $return;

    }

}
