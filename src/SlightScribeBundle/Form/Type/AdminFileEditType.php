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
class AdminFileEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('titleAdmin', 'text', array(
            'required' => true,
            'label'=>'Title (For Admins)'
        ));


        $builder->add('letterContentTemplate', 'textarea', array(
            'required' => false,
            'label'=>'Letter Content'
        ));

    }

    public function getName() {
        return 'tree';
    }


    public function getDefaultOptions(array $options) {
        return array(
            'data_class' => 'SlightScribeBundle\Entity\File',
        );
    }

}
