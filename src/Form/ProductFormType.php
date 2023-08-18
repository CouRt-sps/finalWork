<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Seller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('description')
            ->add('body')
            ->add('imageFilename')
            ->add('limitedEdition')
            ->add('sortIndex')
            ->add('price', IntegerType::class, [
                'mapped' => false,
            ])
            ->add('seller', EntityType::class, [
                'mapped' => false,
                'class' => Seller::class,
                'choice_label' => 'seller',
            ])
            ->add('createdAt')
            ->add('updatedAt')
            ->add('quantityBuy')
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
