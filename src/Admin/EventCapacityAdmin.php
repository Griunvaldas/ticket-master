<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Event;
use App\Enum\CapacityType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class EventCapacityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('capacityType', EnumType::class, [
                'class' => CapacityType::class,
            ])
            ->add('capacity', NumberType::class)
            ->add('price', TextType::class)
            ->add('name', TextType::class)
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'title',
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('capacityType')
            ->add('capacity')
            ->add('price')
            ->add('name')
            ->add('event.title');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('capacityType')
            ->add('capacity')
            ->add('price')
            ->add('name')
            ->add('event', null, ['associated_property' => 'title']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('capacityType')
            ->add('capacity')
            ->add('price')
            ->add('name')
            ->add('event.title');
    }
}
