<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Pseudo', TextType::class,[
                'label'=>'Pseudo :'
            ])
            ->add('Surname', TextType::class,[
                'label'=>'Prenom :'
            ])
            ->add('Name', TextType::class, [
                'label'=>'Nom: '
            ])
            ->add('Phone', TextType::class ,[
                'label'=>'Telephone :'
            ])
            ->add('email',
                EmailType::class ,[
                    'label'=>'Email :'
                ])
            /*
                        ->add('agreeTerms', CheckboxType::class, [
                            'mapped' => false,
                            'constraints' => [
                                new IsTrue([
                                    'message' => 'You should agree to our terms.',
                                ]),
                            ],
                        ])
                        */
            ->add('plainPassword', RepeatedType::class, [
                'mapped'=>false,
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmer :'],
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
            ])
            ->add('Campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>'name'
            ])
            ->add('picture', FileType::class,[
                'mapped'=>false,
                'required' => false,
                'constraints'=>[
                    new Image([
                        'maxSize'=>'7000k',
                        'mimeTypesMessage'=>"Format d'image non autorisé"
                    ])

                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
