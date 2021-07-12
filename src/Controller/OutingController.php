<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Outing;
use App\Form\OutingType;
use App\Form\SearchOutingFormType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OutingController extends AbstractController
{
    #[Route('/outingList', name: 'outing_list')]
    public function list(OutingRepository $outingRepository,  Request $request, Security $security): Response
    {
        $user = $security->getToken()->getUser();
        $data = new SearchData();
        $form = $this->createForm(SearchOutingFormType::class, $data);

        $form->handleRequest($request);

        $outings = $outingRepository->findSearch($data);
        dump($request);
        return $this->render('outing/outingList.html.twig', [
            'outings'   => $outings,
            'user'      => $user,
            'form'      => $form->createView()
        ]);
    }



    #[Route('/outingCreate', name: 'outing_create')]
    public function create(Request $request,  EntityManagerInterface $entityManager,
                           OutingRepository $outingRepository
    ): Response
    {
        $userConnectedCampus=$this->getUser()->getCampus()->getName();
        $outing = New Outing();

        $outingForm = $this->createForm(OutingType::class,$outing);
        $outingForm->handleRequest($request);
        $typeSubmit=$request->request->get('submitAction');
        //Recuperation des données pour l'insertion
        $userCampusId=$this->getUser()->getCampus();
        $outingStateId=$request->request->get('location');
        $outingOrganizerId=$this->getUser();
        $outingLocationId1=$request->request->get('location');
        $outing->setCampus($userCampusId);
        $outing->setOrganizerUser($outingOrganizerId->getId());  $outing->setLocation($outingLocationId1);


        // $outingDateTimeStart=$outingForm->get('')->getData();
        if ($typeSubmit === 'enregistrer' && $outingForm->isValid())
        {
            $outing->setState(1);

            dd("bouton enregistrer"  );
        }
        elseif ($typeSubmit=== 'publier')
        { $outing->setState(2);
            dd("bouton publier".$typeSubmit);
        }
        elseif ($typeSubmit === 'annuler')
        {   //redirection vers la page de sortie
            dd("bouton annuler".$typeSubmit);
        }

        return $this->render('outing/creation.html.twig', [
            'submitType' => $typeSubmit,
            'outingForm'=>$outingForm->createView(),
            // 'campusName'=>$user->getCampus()->getName()
            'campusName'=>$userConnectedCampus
        ]);
    }

    #[Route('/listPlaces', name: 'list_places')]
    public function listPlaces(Request $request,
                               LocationRepository $locationRepository ): Response
    {
        $id=json_decode($request->getContent());
        $outings =  $locationRepository->findCityLocation($id);
        return  $this->json($outings) ;
    }

    #[Route('/detailLieu', name: 'detail_lieu')]
    public function detailLieu(Request $request,
                               LocationRepository $locationRepository ): Response
    {
        $data=json_decode($request->getContent());
        $detailLieu=$locationRepository->findLocationDetail($data->townId, $data->placeId);
        return $this->json($detailLieu);

    }



}