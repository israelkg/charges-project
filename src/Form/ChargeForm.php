<?php

namespace App\Form;

use App\Form\ItemForm; 
use App\Document\Charge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ChargeForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $inputClass = 'block w-full px-4 py-2 mt-1 text-black border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500';
        $labelClass = 'text-black mb-1 block font-medium';
        $sectionClass = 'mb-4';

        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Valor Total',
                'label_attr' => ['class' => $labelClass],
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0.01',
                    'placeholder' => 'Ex: 100.00',
                    'class' => "$inputClass $sectionClass money-input"
                ],
            ])
            ->add('descriptionCharge', TextareaType::class, [
                'label' => 'Descrição',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['rows' => 3, 'class' => "$inputClass $sectionClass"],
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Forma de Pagamento',
                'label_attr' => ['class' => $labelClass],
                'choices' => [
                    'Cartão de Crédito' => 'credit_card',
                    'Boleto' => 'boleto',
                    'PIX' => 'pix',
                    'Transferência' => 'transfer'
                ],
                'attr' => ['class' => "$inputClass $sectionClass"]
            ])
            ->add('paymentType', ChoiceType::class, [
                'label' => 'Tipo de Pagamento',
                'label_attr' => ['class' => $labelClass],
                'choices' => [
                    'Á Vista' => 'cash',
                    'Parcelado' => 'installment',
                    'Assinatura' => 'subscription'
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => "payment-type-radio flex gap-4 $inputClass $sectionClass"]
            ])
            ->add('installmentsCount', ChoiceType::class, [
                'label' => 'Número de Parcelas',
                'label_attr' => ['class' => $labelClass],
                'required' => false,
                'choices' => $options['parcelas_choices'] ?? [],
                'attr' => ['class' => "parcelas-select mt-2 $inputClass $sectionClass"]
            ])
            ->add('dueDate', DateType::class, [
                'label' => 'Data de Vencimento',
                'label_attr' => ['class' => $labelClass],
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => true,
                'attr' => [
                    'class' => "$inputClass $sectionClass"
                ],
            ])

            ->add('items', CollectionType::class, [
                'entry_type' => ItemForm::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'attr' => [
                    'class' => "$sectionClass"
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Criar Cobrança',
                'attr' => [
                    'class' => 'w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200'
                ]
            ])


            ->add('customerName', TextType::class, [
                'label' => 'Nome do Cliente',
                'label_attr' => ['class' => $labelClass],
                'attr' => [
                    'placeholder' => 'Ex: João da Silva',
                    'class' => "$inputClass $sectionClass"
                ],
            ])
            ->add('customerEmail', EmailType::class, [
                'label' => 'Email do Cliente',
                'label_attr' => ['class' => $labelClass],
                'attr' => [
                    'placeholder' => 'Ex: joao@email.com',
                    'class' => "$inputClass $sectionClass"
                ],
            ])
            ->add('cpfCnpj', TextType::class, [
                'label' => 'CPF ou CNPJ',
                'label_attr' => ['class' => $labelClass],
                'required' => true,
                'attr' => [
                    'maxlength' => 18,
                    'placeholder' => "Ex: 123.456.789-12",
                    'class' => "form-control $inputClass $sectionClass",
                ],
            ])
            ->add('numberPhone', TextType::class, [
                'label' => 'Celular',
                'label_attr' => ['class' => $labelClass],
                'required' => true,
                'attr' => [
                    'maxlength' => 18,
                    'placeholder' => "(00) 00000-0000 ",
                    'class' => "$inputClass $sectionClass",
                    'inputmode' => 'tel'
                ],
            ])
            ->add('deliveryMethods', ChoiceType::class, [
                'choices'  => [
                    'WhatsApp' => 'whatsapp',
                    'E-mail' => 'email',
                    'SMS' => 'sms',
                ],
                'expanded' => true, // mostra como botões de radio ou checkbox
                'multiple' => true, // permite múltiplas seleções
                'label'    => 'Como essa cobrança será enviada?',
                'mapped' => true,
                'required' => false,
                'attr' => ['class' => 'hidden'], // vamos esconder e controlar com JS + Tailwind
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Charge::class,
            'parcelas_choices' => [],
            'empty_data' => function (FormInterface $form) {
                return new Charge();
            }
        ]);
    }
}