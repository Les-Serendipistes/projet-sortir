<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingType;
use App\Repository\OutingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    #[Route('/outingList/{page}', name: 'outing_list', requirements: [ 'page' => '\d+'])]
    public function list(OutingRepository $outingRepository, int $page = 1): Response
    {
        $nbOutings = $outingRepository->count([]);
        $maxPage = ceil($nbOutings / 10);

        if ($page < 1 || $page > $maxPage) {
            $page = 1;
            //$outings = $outingRepository->findAll()
        }

        return $this->render('outing/outingList.html.twig', [
            'controller_name' => 'OutingController',
        ]);


    }
    #[Route('/outingCreate', name: 'outing_create')]
    public function create(Request $request,  EntityManagerInterface $entityManager,
                           OutingRepository $outingRepository
    ): Response
    {
        $outing=New Outing();
        $outingForm=$this->createForm(OutingType::class,$outing);
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




}