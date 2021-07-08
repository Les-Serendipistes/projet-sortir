<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Utils\UploadProfilePic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile')]
    public function edit($id, Request $request,
                         EntityManagerInterface $entityManager,
                         UserRepository $userRepository,
                         UserPasswordEncoderInterface $passwordEncoder,
                         UploadProfilePic $uploadProfilePic
    )
    {
        $user=$userRepository->find($id);
        $userForm=$this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);
        /**
         * @var $User $user
         */
        if($userForm->isSubmitted() && $userForm->isValid()){
            $file=$userForm->get('picture')->getData();
            $firstPasswordField=$userForm->get('plainPassword')->getData();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $firstPasswordField
                ));
            if($file){
                $imageDirectory=$this->getParameter('upload_profile_picture');
                $imageName=$user->getPseudo();
                $user->setPicture($uploadProfilePic->save($imageName, $file,$imageDirectory));
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("Edition","Profil édité avec succès");
            return $this->redirectToRoute('profile',['id'=>$user->getId(),
                'user'=>$user
            ]);
        }
        else{
            return $this->render('editProfile.html.twig', [
                'userForm'=>$userForm->createView(),
                'user'=>$user
            ]);
        }
    }

    #[Route('/profileView/{id}', name: 'profileView')]
    public function viewProfile($id, UserRepository $userRepository)
    {
        $userProfileView=$userRepository->find($id);
        return  $this->render('profile/view_profile.html.twig', [ 'user'=>$userProfileView ]);
    }

}
