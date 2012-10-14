<?php

namespace UCP\AbsenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tutorName')
            ->add('tutorEmail')
            ->add('phone')
            ->add('address')
            ->add('city')
            ->add('postalCode')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCP\AbsenceBundle\Entity\Company'
        ));
    }

    public function getName()
    {
        return 'ucp_absencebundle_companytype';
    }
}
