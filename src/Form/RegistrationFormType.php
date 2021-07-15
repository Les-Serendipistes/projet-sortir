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
            ->add('firstname', TextType::class,[
                'label'=>'Prénom :'
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom : '
            ])
            ->add('Phone', TextType::class ,[
                'label'=>'Téléphone :'
            ])
            ->add('email',
                EmailType::class ,[
                    'label'=>'Email :'
                ])

            ->add('plainPassword', RepeatedType::class, [
                'mapped'=>false,
                'type' => PasswordType::class,
                'required' => false,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmation :'],
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
            ])
            ->add('Campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>'name'
            ])
            ->add('picture', FileType::class,[
                'mapped'=> false,
                'required' => false,
                'label'=>'Ma photo :',
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
