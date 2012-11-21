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
            ->add('id', null, array('mapped' => false))
            ->add('firstname')
            ->add('lastname')
            ->add('ine')
            ->add('email', 'email')
            ->add('phone')
            // ->add('picturePath')
            ->add('company', new CompanyType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCP\AbsenceBundle\Entity\Student',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return '';
    }
}
