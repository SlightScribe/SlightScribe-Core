<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AccessPointFormTask
{

    protected $container;

    /** @var AccessPoint */
    protected $accessPoint;

    /**
     * CreateLetterChainInstanceTask constructor.
     * @param $container
     * @param AccessPoint $accessPoint
     */
    public function __construct($container, AccessPoint $accessPoint)
    {
        $this->container = $container;
        $this->accessPoint = $accessPoint;
    }

    public function getHTMLForm() {

        $form = $this->accessPoint->getForm();

        $form = nl2br(htmlspecialchars($form));

        $doctrine = $this->container->get('doctrine')->getManager();

        foreach($doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($this->accessPoint) as $field) {

            $form = str_replace('{{'.$field->getPublicId().'}}', $this->getHTMLForField($field), $form);

        }

        return $form;

    }

    public function getHTMLForField(Field $field) {
        if ($field->isTypeText()) {
            return '<input type="text" name="field_'.$field->getPublicId().'">';
        } else if ($field->isTypeTextArea()) {
            return '<textarea name="field_'.$field->getPublicId().'"></textarea>';
        }
    }

}
