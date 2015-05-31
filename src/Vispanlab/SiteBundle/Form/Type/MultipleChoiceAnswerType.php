<?php

namespace Vispanlab\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MultipleChoiceAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('answer', null, array('label' => false, 'required' => true,))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            //'data_class' => 'Forky\LogisticsBundle\Entity\DeliveryPerson',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'multiplechoice';
    }
}