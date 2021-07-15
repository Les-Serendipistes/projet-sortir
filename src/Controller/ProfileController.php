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
    #[Route('/profile', name: 'profile')]
    public function edit( Request $request,
                         EntityManagerInterface $entityManager,
                         UserRepository $userRepository,
                         UserPasswordEncoderInterface $passwordEncoder,
                         UploadProfilePic $uploadProfilePic
    )
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $typeSubmit=$request->request->get('submitAction');
        if ($typeSubmit === 'Retour')
        {
            return $this->redirectToRoute('outing_list' );
        }
         $userConnected=$this->getUser();
         $userConnectedId=$userConnected->getId();
        $user=$userRepository->find($userConnectedId);
        $userForm=$this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);
        /**
         * @var $User $user
         */
        if($userForm->isSubmitted() && $userForm->isValid()){
            $file=$userForm->get('picture')->getData();
            $firstPasswordField=$userForm->get('plainPassword')->getData();
            if($firstPasswordField){
                $user->setPassword( $passwordEncoder->encodePassword( $user,  $firstPasswordField ));}

            if($file){
                $imageDirectory=$this->getParameter('upload_profile_picture');
                $imageName=$user->getPseudo();
                $user->setPicture($uploadProfilePic->save($imageName, $file,$imageDirectory));
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("Edition","Profile édité avec succès");
            return $this->redirectToRoute('outing_list',[
                'user'=>$user
            ]);
        }
        else{
            return $this->render('edit.html.twig', [
                'userForm'=>$userForm->createView(),
                'user'=>$user
            ]);
        }
    }

    #[Route('/profile/view/{id}', name: 'profileView')]
    public function viewProfile($id, UserRepository $userRepository)
    {
        $userProfileView=$userRepository->find($id);
        return  $this->render('view.html.twig', [ 'user'=>$userProfileView ]);
    }

}
