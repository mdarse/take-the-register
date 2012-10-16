<?php

namespace UCP\AbsenceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    protected $baseRouteName = 'user_admin';
    protected $baseRoutePattern = 'user';

    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('email')
            ->add('phone')
            ->add('enabled')
            ->add('lastLogin')
            ->add('roles')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username', null, array(
                'label' => "Nom d'utilisateur"
            ))
            ->add('email', null, array(
                'label' => 'Email'
            ))
            ->add('phone', null, array(
                'label' => 'Téléphone'
            ))
            ->add('enabled', null, array(
                'required' => false,
                'label' => 'Activé'
            ))
            // ->add('roles', 'sonata_security_roles', array( 'multiple' => true))
            // ->add('roles', 'sonata_type_immutable_array',  array(
            //     'keys' => array(
            //         array('type', 'choice', array(
            //             'choices' => array(
            //                 'ROLE_USER'      => 'Intervenant',
            //                 'RODE_SECRETARY' => 'Secrétaire',
            //                 'ROLE_ADMIN'     => 'Responsable'
            //             ),
            //             'expanded' => true
            //         ))
            //     )
            // ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('phone')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('lastLogin')
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('username')
                ->assertNotBlank()
            ->end()
        ;
    }
}
