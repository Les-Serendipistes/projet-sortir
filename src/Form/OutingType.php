<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Entity\State;
use App\Repository\CityRepository;
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
use Symfony\Component\Validator\Constraints as Assert;

class OutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options
    )
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :',
            ])
            ->add('dateTimeStart',DateTimeType::class, [
                'label'             => "Date et heure de la sortie",
                'date_widget'            => 'single_text',
                'time_widget'            => 'single_text',
                'html5'             => true,
            ])
            ->add('limitDateInscription',DateType::class, [

                'label'=>"Date limite d'inscription :",
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'html5' => true,

            ])
            ->add('maxNbPart')
            ->add('duration'
            )
            ->add('outingReport', TextareaType::class, [
                'label'=>'Description et infos : ',
                'attr' => array(
                    'placeholder' => 'Faites une description de la sortie'
                )
            ])
            ->add('campus',TextType::class,[
                'mapped'=>'false',
                'label'=>'Campus :',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ] )
            ->add('state',EntityType::class, [
                'class'=>City::class,
                'query_builder' => function(CityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'mapped'=>false,
                'placeholder' => ' ',
                'label'=>'Ville :',
                'choice_label'=>'name',

            ])
            ->add('location', ChoiceType::class,[
                'mapped'=>false,
                'label'=>'Lieu :',
                'choices'=>[
                    ''=>''
                ]
            ])
            ->add('street', TextType::class,[
                'mapped'=>false,
                'label'=>'Rue :',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ])
            ->add('zipcode', TextType::class,[
                'mapped'=>false,
                'label'=>'Code postal :',
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
                'label'=>'Longitude :',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ])
            ->add('latitude',NumberType::class,[
                'mapped'=>false,
                'label'=>'Latitude :',
                'attr' => array(
                    'disabled' => 'disabled'
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
            'allow_extra_fields' => true
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

