<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Location;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class EventAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('image', TextType::class)
            ->add('dateEvent', DatePickerType::class)
            ->add('dateAvailable', DatePickerType::class)
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('title')
            ->add('dateEvent')
            ->add('dateAvailable');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->addIdentifier('totalTickets')
            ->addIdentifier('dateEvent')
            ->addIdentifier('dateAvailable')
            ->addIdentifier('dateCreated')
            ->addIdentifier('dateModified');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('title')
            ->add('description')
            ->add('image')
            ->add('dateEvent')
            ->add('dateAvailable')
            ->add('totalTickets');
    }
}
