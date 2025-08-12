<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteType;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionPosteController extends AbstractController
{
    private $posteRepo;
    private $entityManager;

    public function __construct(PosteRepository $posteRepositoryParam,EntityManagerInterface $entityManagerParam)
    {
        $this->posteRepo = $posteRepositoryParam;
        $this->entityManager = $entityManagerParam;
    }

  
    #[Route('/gestion/poste/list', name: 'app_gestion_poste_list')]
    public function list(): Response
    {
        $postes = $this->posteRepo->findAll();

        return $this->render('gestion_poste/list.html.twig', [
            'postes' => $postes,
        ]);
    }
    #[Route('/new', name: 'app_gestion_poste_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'), // défini dans config/services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }

                $poste->setImagePoste($newFilename);
            }

            $em->persist($poste);
            $em->flush();

            $this->addFlash('success', 'Poste ajouté avec succès !');
            return $this->redirectToRoute('app_gestion_poste_list');
        }

        return $this->render('gestion_poste/add.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/gestion/poste/delete/{id}', name: 'app_gestion_poste_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $poste = $this->posteRepo->find($id);

        if (!$poste) {
            throw $this->createNotFoundException('Poste non trouvé');
        }
        $this->entityManager->remove($poste);
        $this->entityManager->flush();  
        return $this->redirectToRoute('app_gestion_poste_list');


    }
    #[Route('/gestion/poste/edit/{id}', name: 'app_gestion_poste_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $poste = $this->posteRepo->find($id);
        if (!$poste) {
            throw $this->createNotFoundException('Poste non trouvé');
        }
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($poste);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gestion_poste_list');
        }
        return $this->render('gestion_poste/edit.html.twig', [
            'form' => $form->createView(),
            'poste' => $poste,
        ]);
    }   
    #[Route('/gestion/poste/view/{id}', name: 'app_gestion_poste_view', methods: ['GET'])]
    public function view(int $id): Response

    {
        $poste = $this->posteRepo->find($id);

        if (!$poste) {
            throw $this->createNotFoundException('Poste non trouvé');
        }

        return $this->render('gestion_poste/view.html.twig', [
            'poste' => $poste,
        ]);
    }   
    #[Route('/gestion/poste/search', name: 'app_gestion_poste_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $searchTerm = $request->query->get('q');
        $postes = $this->posteRepo->findBySearchTerm($searchTerm);
        return $this->render('gestion_poste/search.html.twig', [
            'postes' => $postes,
            'searchTerm' => $searchTerm,
        ]);


    }
  

   
    #[Route('/gestion/poste/statistics', name: 'app_gestion_poste_statistics', methods: ['GET'])]
    public function statistics(): Response
    {
        $postes = $this->posteRepo->findAll();
        $statistics = [
            'total' => count($postes),
            'active' => count(array_filter($postes, fn($poste) => $poste
->isActive())),

            'inactive' => count(array_filter($postes, fn($poste) => !$poste->isActive())),
        ];

        return $this->render('gestion_poste/statistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }
  
 
}