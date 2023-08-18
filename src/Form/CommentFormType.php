<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Products;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', null, [
                'label' => ' комментария',
            ])
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'label' => 'Выбор продукта',
                'choice_label' => 'product',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Выбор автора',
                'choice_label' => 'firstName',
            ])
            ->add('createdAt', null, [
                'label' => 'Дата создания',
            ])
            ->add('updatedAt', null, [
                'label' => 'Дата редактирования'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
