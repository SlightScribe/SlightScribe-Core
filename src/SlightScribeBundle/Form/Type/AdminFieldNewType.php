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
class AdminFieldNewType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('titleAdmin', 'text', array(
            'required' => true,
            'label'=>'Title (For Admins)'
        ));

        $builder->add('label', 'text', array(
            'required' => true,
            'label'=>'Label'
        ));

        $builder->add('description', 'text', array(
            'required' => true,
            'label'=>'Description'
        ));

        // TODO enforce slug like!
        // TODO A value is required here when it shouldn't be!
        $builder->add('publicId', 'text', array(
            'required' => false,
            'label'=>'Key'
        ));


    }

    public function getName() {
        return 'tree';
    }


    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'SlightScribeBundle\Entity\Field',
        );
    }

}
