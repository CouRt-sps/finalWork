<?php

namespace App\Form;

use App\Entity\Discounts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', TextType::class, [
                'label' => 'Укажите процент скидки',
                'help' => 'Число от 1 до 99'
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'Дата старта'
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
                'label' => 'Дата окончания'
            ])
            ->add('description', null, [
                'label' => 'Описание',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Discounts::class,
        ]);
    }
}
