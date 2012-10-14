<?php

namespace UCP\AbsenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('ine')
            ->add('email')
            ->add('phone')
            // ->add('picturePath')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCP\AbsenceBundle\Entity\Student'
        ));
    }

    public function getName()
    {
        return 'ucp_absencebundle_studenttype';
    }
}
