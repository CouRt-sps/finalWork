<?php

namespace App\Form;

use App\Entity\Seller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('seller', TextType::class, [
                'label' => 'Название',
                'help' => 'С формой организации',
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание',
            ])
            ->add('imageSeller', null, [
                'label' => 'Логотип'
            ])
            ->add('phone', null, [
                'label' => 'Телфон',
                'help' => '+79000000000',
            ])
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seller::class,
        ]);
    }
}
