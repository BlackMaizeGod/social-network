<?php

namespace App\Form;

use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'group-input',
                    ],
                ])
           // ->add('user')
//            ->add('user', TextType::class,
//                [
//                    'required' => false,
//                    'label' => false,
//                    'attr' => [
//                        'hidden' => true,
//                    ],
//                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
