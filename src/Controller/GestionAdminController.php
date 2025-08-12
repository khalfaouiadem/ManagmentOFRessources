<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType as FormAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use src\Form\AdminType;
use Symfony\Component\HttpFoundation\Request;

final class GestionAdminController extends AbstractController
{  private $adminRepo;
    private $entityManager;
    
  public function __construct(AdminRepository $adminRepositoryParam, EntityManagerInterface $entityManagerParam)
{
    $this->adminRepo = $adminRepositoryParam;
    $this->entityManager = $entityManagerParam;
}

    #[Route('/Adminlist', name: 'app_list', methods:['GET'])]
    public function AdminList(): Response
    {
        // Récupérer la liste des auteurs
        $admins = $this->adminRepo->findAll(); 

        // Rendre la vue avec la liste des auteurs
        return $this->render('gestion_admin/listAdmins.html.twig', [
            'authors' => $admins,
        ]);
    }

    #[Route('/AdminDelete/{id}', name: 'app_delete', methods: ['GET'])]
    public function AdminDelete(int $id): Response
    {
        // Récupérer l'auteur par son ID
        $author = $this->adminRepo->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        // Supprimer l'auteur
        $this->entityManager->remove($author);
        $this->entityManager->flush();

        // Rediriger vers la liste des auteurs
        return $this->redirectToRoute('app_list');
    }
  
        #[Route('/AddAdmin', name: 'add_Admin', methods: ['GET', 'POST'])]
public function AddAdmin(Request $request): Response
{
    $admin = new Admin();
    $form = $this->createForm(FormAdminType::class, $admin);

    // Handle the form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_list'); // Redirige vers la liste des auteurs
    }

    return $this->render('gestion_admin/AddAdmin.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
