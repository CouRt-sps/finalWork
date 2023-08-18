<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrdersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TelType::class, [
                'label' => 'Телефон',
                'help' => '+79000000000',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Поле "Телефон" является обязательным.',
                    ]),
                ],
            ])
            ->add('deliveryMethod', ChoiceType::class, [
                'label' => 'Выберите способ доставки',
                'choices' => [
                    'Обычная доставка' => 'обычная',
                    'Экспресс доставка (500$)' => 'экспресс',
                ],
                'expanded' => true, // для отображения радиокнопок
                'multiple' => false, // для выбора только одного варианта
            ])
            ->add('address', TextType::class, [
                'label' => 'Введите адресс',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Поле является обязательным.',
                    ]),
                ],
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Выберите способ оплаты',
                'choices' => [
                    'Онлайн картой' => 'онлайн картой',
                    'Онлайн с чужого счета' => 'с чужого счета',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
