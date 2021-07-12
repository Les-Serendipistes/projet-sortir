<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Outing;
use App\Form\OutingType;
use App\Form\SearchOutingFormType;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
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
    public function create(Request $request,
    ): Response
    {
        $outing = New Outing();
        $outingForm = $this->createForm(OutingType::class,$outing);
        $outingForm->handleRequest($request);
        $typeSubmit=$request->request->get('submitAction');
        //$request->get('submitAction')
        $outingName=$outingForm->get('name')->getData();

        if ($typeSubmit === 'enregistrer')
        {
            dd("bouton enregistrer".$typeSubmit."---".$outingName);
        }
        elseif ($typeSubmit=== 'publier')
        {
            dd("bouton publier".$typeSubmit);
        }
        elseif ($typeSubmit === 'annuler'){
            dd("bouton annuler".$typeSubmit);
        }

        return $this->render('outing/creation.html.twig', [
            'submitType' => $typeSubmit,
            'outingForm'=>$outingForm->createView(),
        ]);
    }

    #[Route('/listPlaces', name: 'list_places')]
    public function listPlaces(Request $request,  LocationRepository $locationRepository ): Response
    {
            $id=$request->getContent();
        $outings =  $locationRepository->findBy(
            ['city'=>$id]
        );

     return $this->json($outings);
            //dd($outings);
    }

}