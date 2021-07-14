<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchOutingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Campus filter :
            ->add('campus', EntityType::class, [
                'class'             => Campus::class,
                'required'          => false,
                'label'             => false,
            ])
            // Keyword filter :
            ->add('q', TextType::class, [
                'label'             => false,
                'required'          => false,
            ])
            ->add('dateStart', DateType::class, [
                'label'             => false,
                'required'          => false,
                'widget'            => 'single_text',
            ])
            ->add('dateEnd', DateType::class, [
                'label'             => false,
                'required'          => false,
                'widget'            => 'single_text',
            ])
            ->add('organizer', CheckboxType::class, [
                'label'             => 'Sorties dont je suis l\'organisateur/trice',
                'required'          => false,
                'attr'              => [
                    'class'         => 'filters'
                ]
            ])
            ->add('registered', CheckboxType::class, [
                'label'             => 'Sorties auxquelles je suis inscrit/e',
                'required'          => false,
                'attr'              => [
                    'class'         => 'filters'
                ]
            ])
            ->add('unregistered', CheckboxType::class, [
                'label'             => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required'          => false,
                'attr'              => [
                    'class'         => 'filters'
                ]
            ])
            ->add('olds',CheckboxType::class, [
                'label'             => 'Sorties passées',
                'required'          => false,
                'attr'              => [
                    'class'         => 'filters'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
