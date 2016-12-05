<?php

namespace SlightScribeBundle\Task;

use SlightScribeBundle\Entity\AccessPoint;
use SlightScribeBundle\Entity\Field;
use SlightScribeBundle\Entity\RunCommunicationAttachment;
use SlightScribeBundle\Entity\RunHasField;
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

    /**
     * @param array $fieldsData This should be an array of existing values, to reshow. Key is public ID, Value is RunHasField object.
     * @return mixed|string
     */
    public function getHTMLForm($fieldsData = array()) {

        $form = $this->accessPoint->getForm();

        $form = nl2br(htmlspecialchars($form));

        $doctrine = $this->container->get('doctrine')->getManager();
        $accessPointHasFieldRepository = $doctrine->getRepository('SlightScribeBundle:AccessPointHasField');

        foreach($doctrine->getRepository('SlightScribeBundle:Field')->getForAccessPoint($this->accessPoint) as $field) {

            $form = str_replace(
                '{{'.$field->getPublicId().'}}',
                $this->getHTMLForField(
                    $field,
                    $accessPointHasFieldRepository->findOneBy(array('field'=>$field,'accessPoint'=>$this->accessPoint))->getIsRequired(),
                    isset($fieldsData[$field->getPublicId()]) ? $fieldsData[$field->getPublicId()] : null
                ),
                $form
            );

        }

        return $form;

    }

    public function getHTMLForField(Field $field, $isRequired = false, RunHasField $runHasField = null) {
        $isRequiredAndMissing = $isRequired && ( !$runHasField || !$runHasField->hasValue() );
        $extraClasses = ($isRequired ? ' field_required'.( $isRequiredAndMissing ? '  field_required_missing':''):'');
        if ($field->isTypeText()) {
            $value = $runHasField ? $runHasField->getValue() : '';
            return
                '<span class="field_wrapper">'.
                '<input type="text" name="field_'.$field->getPublicId().'" class="field_type_text field_name_'.$field->getPublicId().$extraClasses.'" placeholder="'.htmlspecialchars($field->getLabel(), ENT_QUOTES).'" value="'.htmlspecialchars($value, ENT_QUOTES).'">'.
                ($isRequired ? ' <span class="field_required_label' .($isRequiredAndMissing ? ' field_required_missing_label' :'').'">*(required)</span>' : '').
                '</span>';
        } else if ($field->isTypeDate()) {
            $value = $runHasField ? $runHasField->getValue() : '';
            return
                '<span class="field_wrapper">'.
                '<input type="text" name="field_'.$field->getPublicId().'" class="field_type_date field_name_'.$field->getPublicId().$extraClasses.'" placeholder="'.htmlspecialchars($field->getLabel(), ENT_QUOTES).'" value="'.htmlspecialchars($value, ENT_QUOTES).'">'.

                ($isRequired ? ' <span class="field_required_label' .($isRequiredAndMissing ? ' field_required_missing_label' :'').'">*(required)</span>' : '').
                '</span>';
        } else if ($field->isTypeTextArea()) {
            $value = $runHasField ? $runHasField->getValue() : '';
            return
                '<span class="field_wrapper">'.
                '<textarea name="field_'.$field->getPublicId().'" class="field_type_textarea field_name_'.$field->getPublicId().$extraClasses.'" placeholder="'.htmlspecialchars($field->getLabel(), ENT_QUOTES).'">'.htmlspecialchars($value, ENT_QUOTES).'</textarea>'.
                ($isRequired ? ' <span class="field_required_label' .($isRequiredAndMissing ? ' field_required_missing_label' :'').'">*(required)</span>' : '').
                '</span>';
        }
    }

}
