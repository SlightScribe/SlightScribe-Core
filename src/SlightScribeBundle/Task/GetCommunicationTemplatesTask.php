<?php


namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Communication;
use SlightScribeBundle\Entity\CommunicationTemplateError;
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

        try {
            $return['subject'] = $this->container->get('twig')->createTemplate($communication->getEmailSubjectTemplate())->render($twigVariables);
            $return['text'] = $this->container->get('twig')->createTemplate($communication->getEmailContentTextTemplate())->render($twigVariables);
            // HTML template is optional.
            $return['html'] = $communication->getEmailContentHTMLTemplate() ? $this->container->get('twig')->createTemplate($communication->getEmailContentHTMLTemplate())->render($twigVariables) : null;

            return $return;
        } catch (\Twig_Error $e) {

            if ($communication->getId()) {
                // The if statement just checks Communication is already saved - might not be, in which case we don't save.
                $communicationTemplateError = new CommunicationTemplateError();
                $communicationTemplateError->setCommunication($communication);
                // We might have been passed a dummy run, in which case don't save it.
                $communicationTemplateError->setRun($run->getId() ? $run : null);
                $communicationTemplateError->setEmailSubjectTemplate($communication->getEmailSubjectTemplate());
                $communicationTemplateError->setEmailContentHTMLTemplate($communication->getEmailContentHTMLTemplate());
                $communicationTemplateError->setEmailContentTextTemplate($communication->getEmailContentTextTemplate());
                $communicationTemplateError->setTwigVariables(json_encode($twigVariables, 0, 3));
                $communicationTemplateError->setErrorCode($e->getCode());
                $communicationTemplateError->setErrorFile($e->getFile());
                $communicationTemplateError->setErrorLine($e->getLine());
                $communicationTemplateError->setErrorMessage($e->getMessage());
                $doctrine = $this->container->get('doctrine')->getManager();
                $doctrine->persist($communicationTemplateError);
                $doctrine->flush($communicationTemplateError);
            }

            throw $e;
        }

    }

}
