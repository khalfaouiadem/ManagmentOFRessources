<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionMaterielController extends AbstractController
{
    private $materielRepo;
    private $entityManager;
    public function __construct(MaterielRepository $materielRepositoryParam, EntityManagerInterface $entityManagerParam)
    {
        $this->materielRepo = $materielRepositoryParam;
        $this->entityManager = $entityManagerParam;
    }

   
    #[Route('/gestion/materiel/list', name: 'app_gestion_materiel_list')]
    public function list(): Response
    {
       
       $materiels = $this->materielRepo->findAll();         

        return $this->render('gestion_materiel/list.html.twig', [
            'materiels' =>$materiels // Remplacer par la variable contenant les matériels
        ]);             
    }
    #[Route('/gestion/materiel/add', name: 'app_gestion_materiel_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
     
    $materiel = new Materiel();
    $form = $this->createForm(MaterielType::class, $materiel);
    $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($materiel);
            $this->entityManager->flush();

            $this->addFlash('success', 'Matériel ajouté avec succès !');
            return $this->redirectToRoute('app_gestion_materiel_list');
        }
        return $this->render('gestion_materiel/add.html.twig', [
            'form' => $form->createView(),
        ]);

     
         
    }
        
    #[Route('/gestion/materiel/delete/{id}', name: 'app_gestion_materiel_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $materiel = $this->materielRepo->find($id);

        if (!$materiel) {
            throw $this->createNotFoundException('Matériel non trouvé');
        }
        $this->entityManager->remove($materiel);
        $this->entityManager->flush();
        $this->addFlash('success', 'Matériel supprimé avec succès !');
        return $this->redirectToRoute('app_gestion_materiel_list');

    }
    #[Route('/gestion/materiel/edit/{id}', name: 'app_gestion_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $materiel = $this->materielRepo->find($id);
        if (!$materiel) {
            throw $this->createNotFoundException('Matériel non trouvé');
        }
        $form = $this->createForm(MaterielType::class, $materiel
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($materiel);
            $this->entityManager->flush();

            $this->addFlash('success', 'Matériel modifié avec succès !');
            return $this->redirectToRoute('app_gestion_materiel_list');
        }
        return $this->render('gestion_materiel/edit.html.twig', [
            'form' => $form->createView(),
            'materiel' => $materiel,
        ]);
    }

}
