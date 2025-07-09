<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserRegisterForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $inputClass = 'block w-full px-4 py-2 mt-1 text-black border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500';
        $labelClass = 'text-black mb-1 block font-medium';
        $sectionClass = 'mb-4';

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome completo',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => "$inputClass $sectionClass"]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => "$inputClass $sectionClass"]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Senha',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => "$inputClass $sectionClass"]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Criar Conta',
            ]);
    }
}
