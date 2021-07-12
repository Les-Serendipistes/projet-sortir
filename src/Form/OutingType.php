<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
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

class OutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :',
            ])
            ->add('dateTimeStart',DateTimeType::class, [
                'label'=>'Date et heure de la sortie :',
                'widget' => "single_text",
                'html5' => true,
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ]
            ])
            ->add('limitDateInscription',DateType::class, [
                'label'=>"Date limite d'inscription :",
            ])
            ->add('maxNbPart', NumberType::class,[
                'label'=>'Nombre de places : ',
            ])
            ->add('duration',NumberType::class,[
                'label'=>'Durée : ',
            ])
            ->add('outingReport', TextareaType::class, [
                'label'=>'Description : ',
                'attr' => array(
                    'placeholder' => 'Faites une description de la sortie'
                )
            ])
            ->add('campus',TextType::class,[
                'mapped'=>'false',
                'label'=>'Campus',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ] )
            ->add('state',EntityType::class, [
                'class'=>City::class,
                'placeholder' => ' ',
                'choice_label'=>'name',
            ])
            ->add('location', ChoiceType::class,[
                'mapped'=>false,
                'choices'=>[
                    ''=>''
                ]
            ])
            ->add('street', TextType::class,[
                'mapped'=>false,
                'label'=>'Rue: ',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ])
            ->add('zipcode', TextType::class,[
                'mapped'=>false,
                'label'=>'Code postal: ',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ])
            /*
                      ->add('organizerUser',NumberType::class,[
                          'mapped'=>'false',
                  ])
                      /*
                      ->add('registeredUsers',NumberType::class,[
                          'mapped'=>'false',
                      ])

            ->add('location',NumberType::class,[
                'mapped'=>'false',
            ])
            */
            ->add('longitude',NumberType::class,[
                'mapped'=>false,
            ])
            ->add('latitude',NumberType::class,[
                'mapped'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
/*
    public function getBlockPrefix()
    {
        return null;
    }
    */
    public function getBlockPrefix()
    {
        return '';
    }
}
