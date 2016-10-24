<?php


namespace SlightScribeBundle\Form\Type;

use SlightScribeBundle\Entity\LetterVersion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *  @license 3-clause BSD https://github.com/SlightScribe/SlightScribe-Core/blob/master/LICENSE.md
 *  @link https://github.com/SlightScribe/SlightScribe-Core
 */
class AdminCommunicationEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('titleAdmin', 'text', array(
            'required' => true,
            'label'=>'Title (For Admins)'
        ));


        $builder->add('emailSubjectTemplate', 'textarea', array(
            'required' => false,
            'label'=>'Email Subject'
        ));


        $builder->add('emailContentTextTemplate', 'textarea', array(
            'required' => false,
            'label'=>'Email Content - Text'
        ));

        $builder->add('emailContentHTMLTemplate', 'textarea', array(
            'required' => false,
            'label'=>'Email Content - HTML'
        ));


    }

    public function getName() {
        return 'tree';
    }


    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'SlightScribeBundle\Entity\Communication',
        );
    }

}
