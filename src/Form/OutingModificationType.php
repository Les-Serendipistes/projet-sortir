<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options
    )
    {
        $builder
            ->add('name', TextType::class, [
                'label'             =>'Nom de la sortie :',
            ])
            ->add('dateTimeStart',DateTimeType::class, [
                'label'             => "Date et heure de la sortie",
                'date_widget'            => 'single_text',
                'time_widget'            => 'single_text',
                'html5'             => true,
            ])
            ->add('limitDateInscription',DateType::class, [
                'label'             => "Date limite d'inscription :",
                'widget'            => 'single_text',
                // this is actually the default format for single_text
                'format'            => 'yyyy-MM-dd',
                'html5'             => true,
            ])
            ->add('maxNbPart', NumberType::class, [
                'label'             => 'Nombre de places :',
            ])
            ->add('duration',NumberType::class, [
                'label'             => 'Durée (minutes) :',
            ])
            ->add('outingReport', TextareaType::class, [
                'label'             => 'Description et infos : ',
                'attr'              => [
                    'placeholder'   => 'Faites une description de la sortie'
                ]
            ])
            ->add('campus',TextType::class,[
                'mapped'            =>'false',
                'label'             =>'Campus :',
                'attr'              => [
                    'disabled' => 'disabled'
                ],
            ])
            ->add('city',EntityType::class, [
                'class'             => City::class,
                'mapped'            => false,
                'label'             =>'Ville :',
                'choice_label'      =>'name',
            ])
            ->add('location', ChoiceType::class,[
                'mapped'            => false,
                'label'             => 'Lieu :',
                'choices'           => [
                    ''              => ''
                ]
            ])
            ->add('street', TextType::class,[
                'mapped'            => false,
                'label'             => 'Rue :',
                'attr'              => [
                    'disabled'      => 'disabled'
                ],
            ])
            ->add('zipcode', TextType::class,[
                'mapped'            => false,
                'label'             => 'Code postal :',
                'attr'              => [
                    'disabled'      => 'disabled'
                ],
            ])
            ->add('longitude',NumberType::class,[
                'mapped'            => false,
                'label'             => 'Longitude :',
                'attr'              => [
                    'disabled'      => 'disabled'
                ],
            ])
            ->add('latitude',NumberType::class,[
                'mapped'            => false,
                'label'             => 'Latitude :',
                'attr'              => [
                    'disabled' => 'disabled'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
            'method' => 'POST',
            'allow_extra_fields' => true
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}

