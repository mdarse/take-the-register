<?php

namespace UCP\AbsenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tutor')
            ->add('email')
            ->add('phone')
            ->add('address', 'textarea')
            ->add('city')
            ->add('postalCode')
        ;
    }

    public function getName()
    {
        return 'ucp_company_form';
    }

    public function getDefaultOptions(array $options){
        return array('data_class' => 'UCP\AbsenceBundle\Entity\Company');
    }
}