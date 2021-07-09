<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingFilterSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Campus filter :
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => function ($campus) {
                return $campus->getName();
                },
                'empty_data' => null,
                'placeholder' => 'Campus',
                'required' => false
            ])
            // Keyword filter :
            ->add('name', SearchType::class, [
                'label' => 'Le nom de la sortie contiett',
                'required' => false
            ])

            ->add('dateTimeStart', DateTimeType::class, [
                'label' => 'Entre le',
                'widget' => 'single_text',
                "input" => 'datetime'
                ])
            ->add('limitDateInscription', DateTimeType::class, [
                'label' => 'et le',
                'widget' => 'single_text',
                'widget' => 'single_text',
                "input" => 'datetime'
            ])
            ->add('organizerUser', CheckboxType::class, [
                'label'    => 'sorties dont je suis l\'organisateur.trice',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('registeredUsers', CheckboxType::class, [
                'label'    => 'sorties auxquelles je suis inscrit.e',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('unregisteredUser', CheckboxType::class, [
                'label'    => 'sorties auxquelles je ne suis pas inscrit.e',
                'required' => false,
                'empty_data' => null,
                'mapped' => false
            ])
            ->add('pastOuting',CheckboxType::class,  [
                'label'    => 'sorties passées',
                'required'      => false,
                'empty_data' => null,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
