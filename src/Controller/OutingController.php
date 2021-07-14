<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Outing;
use App\Form\OutingModificationType;
use App\Form\OutingType;
use App\Form\SearchOutingFormType;
use App\Repository\CampusRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class OutingController extends AbstractController
{

    private $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/outing/list', name: 'outing_list')]
    public function list(OutingRepository $outingRepository,
                         Request $request,
                         PaginatorInterface $paginator
    ): Response
    {
        $user = $this->security->getToken()->getUser();
        $data = new SearchData();
        $form = $this->createForm(SearchOutingFormType::class, $data);

        $form->handleRequest($request);

        $page = $outingRepository->findSearch($data);

        $outings = $paginator->paginate(
            $page,
            $request->query->getInt('page', 1),
            10
        );

        // Gestion du rafraichissement automatique de la liste des sortie et de la pagination.
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('outing/_outingTable.html.twig', ['outings' => $outings]),
                'pagination' => $this->renderView('outing/_pagination.html.twig', ['outings' => $outings]),
                'pages' => ceil($outings->getTotalItemCount() / $outings->getItemNumberPerPage()),
            ]);
        }
        return $this->render('outing/outingList.html.twig', [
            'outings'   => $outings,
            'user'      => $user,
            'form'      => $form->createView()
        ]);
    }

    //Methode pour annuler une sortie
    #[Route('/outingCancel/{id}', name: 'outing_cancel')]
    /**
     * @ParamConverter ("State", options={"mapping":{"id": "id"}})
     */
    public function cancel($id, Request $request,
                           OutingRepository $outingRepository, EntityManagerInterface $entityManager,
                           StateRepository $stateRepository):Response
    {
        $outingToCancel = $outingRepository->find($id);
        //$defaultData = ['motif' => ''];
        $formCancel = $this->createFormBuilder()
            ->add('motif', TextareaType::class,[
                'label'=>'Motif'
            ])
            ->getForm();
        $formCancel->handleRequest($request);

        if ($formCancel->isSubmitted() && $formCancel->isValid()) {
           $motif= $formCancel->get('motif')->getData();
            $outingToCancel->setState($stateRepository->find(1));
            $outingToCancel->setOutingReport($outingToCancel->getOutingReport().". Motif d'annulation : ".$motif);
            $entityManager->persist($outingToCancel);
            $entityManager->flush();
            $this->addFlash("Sortie","Sortie annulée avec succès.");
            return $this->redirectToRoute('outing_list' );
        }
        return $this->render('outing/cancel.html.twig', [
            'formCancel' => $formCancel->createView(),
            'outingToCancel'=>$outingToCancel
        ]);

    }

    #[Route('/outing/create', name: 'outing_create')]
    /**
     * @ParamConverter ("State", options={"mapping":{"id": "id"}})
     * @ParamConverter ("Location", options={"mapping":{"id": "id"}})
     * @ParamConverter ("User", options={"mapping":{"id": "id"}})
     */
    public function create(Request $request,  EntityManagerInterface $entityManager,
                       StateRepository $stateRepository, LocationRepository $locationRepository,
                       UserRepository $userRepository

    ): Response
    {
          if($this->getUser()){
              $userConnectedCampus = $this->getUser()->getCampus()->getName();
              $userId=$this->getUser()->getId();
              $outing = New Outing();
              $outingForm = $this->createForm(OutingType::class,$outing);
              $outingForm->handleRequest($request);
              $typeSubmit=$request->request->get('submitAction');
              //Recuperation des données pour l'insertion
              if( $outingForm->isSubmitted()){
                  $userCampusId=$this->getUser()->getCampus();
                  $outingLocationId1=$request->request->get('location');
                  $outing->setCampus($userCampusId);
                  $outing->setOrganizerUser($outing->getOrganizerUser());
                  $outing->setLocation($locationRepository->find($outingLocationId1));
                  $outing->setOrganizerUser($userRepository->find($userId));
              }

          }else{
              $this->addFlash("Connexion","Veuillez vous connecter.");
              return $this->redirectToRoute('outing_list' );
          }
        if ($typeSubmit === 'enregistrer' )
        {
            $outing->setState($stateRepository->find(1));
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash("Sortie","Sortie créée avec succès.");
            return $this->redirectToRoute('outing_list' );
        }
        elseif ($typeSubmit=== 'publier')
        {
            $outing->setState($stateRepository->find(2));
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash("Sortie","Sortie publiée avec succès.");
            return $this->redirectToRoute('outing_list' );
        }
        elseif ($typeSubmit === 'annuler')
        {   //redirection vers la page de sortie
            return $this->redirectToRoute('outing_list' );
        }

        return $this->render('outing/creation.html.twig', [
            'submitType' => $typeSubmit,
            'outingForm'=>$outingForm->createView(),
            // 'campusName'=>$user->getCampus()->getName()
             'campusName'=>$userConnectedCampus
        ]);
    }

    #[Route('/place/list', name: 'list_places')]
    public function listPlaces(Request $request,
                               LocationRepository $locationRepository ): Response
    {
        $id=json_decode($request->getContent());
        //$outings =  $locationRepository->findCityLocation($id);
        $outings =  $locationRepository->findLocation($id);
        return  $this->json($outings) ;
    }

    #[Route('/place/detail', name: 'detail_place')]
    public function detailLieu(Request $request,
                               LocationRepository $locationRepository ): Response
    {
        $id=json_decode($request->getContent());
        $detailLieu=$locationRepository->findLocationDetail($id);
        return $this->json($detailLieu);

    }

    #[Route('/outing/detail/{id}', name: 'outing_detail')]
    public function campus($id, Request $request, OutingRepository $outingRepository, EntityManagerInterface $entityManager): Response
    {
        $outing = $outingRepository->find($id);

        // TODO: Message flash "Cette sortie n'existe pas... pourquoi ne pas en créer une ?" et renvoyer vers accueil

        return $this->render('outing/detail.html.twig', ['outing' => $outing]);
    }

    #[Route('/outing/modify/{id}', name: 'outing_modify')]
    public function edit(int $id, request $request,
                         LocationRepository $locationRepository,
                         StateRepository $stateRepository,
                         CampusRepository $campusRepository,
                         EntityManagerInterface $entityManager)
    : Response
    {
        $user = $this->getUser();
        $outing = $entityManager->getRepository(Outing::class)->find($id);
        $outingForm = $this->createForm(OutingModificationType::class, $outing);
        $outingForm->handleRequest($request);
        $submit =$request->request->get('submitAction');

        if ($outingForm->isSubmitted() && $outingForm->isValid()) {
            $currentUser = $this->getUser();

            if ($currentUser == null || $currentUser->getId() != $outing->getOrganizerUser()->getId()) {
                return $this->redirectToRoute("outing_list");
            }
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash('success', "La sortie a été modifié");

        }

        if ($submit === 'enregistrer' )
        {
            $outing->setCampus($campusRepository->find($user->getCampus()->getId()));
            $outing->setState($stateRepository->find(1));
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash("Sortie","Sortie modifiée avec succès.");
            return $this->redirectToRoute('outing_list' );
        }
        elseif ($submit=== 'publier')
        {
            $outing->setCampus($campusRepository->find($user->getCampus()->getId()));
            $outing->setState($stateRepository->find(2));
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash("Sortie","Sortie publiée avec succès.");
            return $this->redirectToRoute('outing_list' );
        }
        elseif ($submit === 'annuler')
        {   //redirection vers la page de sortie
            return $this->redirectToRoute('outing_list' );
        }

        return $this->render('outing/modify.html.twig', [
            'modifyForm' => $outingForm->createView(),
            'user' => $this->security->getUser(),
            'campus' => $this->getUser()->getCampus()->getName(),
            'outing' => $outing
        ]);
    }


    //Methode pour annuler une sortie



    #[Route('/outing/subscribe/{id}', name: 'outing_subscribe')]
    public function inscription(OutingRepository $outingRepository, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $user = $this->security->getUser();
        $outing = $outingRepository->find($id);
        $outing->addRegisteredUser($user);
        $entityManager->persist($outing);
        $entityManager->flush();
        return $this->redirectToRoute('outing_list');
    }

    #[Route('/outing/unsubscribe/{id}', name: 'outing_unsubscribe')]
    public function unsubscribe(OutingRepository $outingRepository, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $user = $this->security->getUser();
        $outing = $outingRepository->find($id);
        $outing->removeRegisteredUser($user);
        $entityManager->persist($outing);
        $entityManager->flush();
        return $this->redirectToRoute('outing_list');
    }

}