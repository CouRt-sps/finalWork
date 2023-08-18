<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название категории',
            ])
            ->add('iconFilename', null, [
                'label' => 'Иконка',
            ])
            ->add('sortIndex', null, [
                'label' => 'Индекс сортировки'
            ])
            ->add('favorites', null, [
                'label' => 'Избранное'
            ])
            ->add('isActive', null, [
                'label' => 'Активная'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
