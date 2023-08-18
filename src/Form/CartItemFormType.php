<?php

namespace App\Form;

use App\Entity\CartItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', ButtonType::class, [
                'label' => 'Обновить',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Очистить',
                'attr' => ['class' => 'btn btn-secondary'],
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => CartItem::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartItem::class,
        ]);
    }
}
