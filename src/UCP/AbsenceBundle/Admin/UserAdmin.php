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
            ->add('username')
            ->add('email')
            ->add('phone')
            ->add('enabled')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('phone')
            ->add('enabled')
            ->add('lastLogin')
            ->add('roles')
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
