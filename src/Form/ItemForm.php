<?php

namespace App\Form;

use App\Document\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ItemForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $inputClass = 'block w-full px-3 py-2 text-black border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-2';
        $labelClass = 'text-black mb-1 block font-medium';

        $builder
            ->add('id', HiddenType::class, [
                'required' => false,
                'empty_data' => null
            ])
            
            ->add('description', TextType::class, [
                'label' => 'Descrição',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['placeholder' => 'Ex: Assinatura Mensal, Consultoria',
                           'class' => $inputClass]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantidade',
                'label_attr' => ['class' => $labelClass],
                'html5' => true,
                'attr' => ['min' => '1',
                           'class' => $inputClass]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Preço Unitário',
                'label_attr' => ['class' => $labelClass],
                'scale' => 2,
                'html5' => true,
                'attr' => ['step' => '0.01', 'min' => '0',
                           'class' => $inputClass]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}