<?php

namespace App\Controller;

use App\Entity\RessourcesHumaines;
use App\Form\RessourcesHumainesType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\RessourcesHumainesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class GestionRessourcesHumainesController extends AbstractController
{
   private $adminRepo;
    private $entityManager;
       
  public function __construct(RessourcesHumainesRepository $resRepositoryParam, EntityManagerInterface $entityManagerParam)
{
    $this->adminRepo = $resRepositoryParam;
    $this->entityManager = $entityManagerParam;
  
}
  
#[Route('/AddRh', name: 'add_RH', methods: ['GET', 'POST'])]
public function AddRH(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
{
    $rh = new RessourcesHumaines();
    $form = $this->createForm(RessourcesHumainesType::class, $rh);
    $form->handleRequest($request);





        
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération du fichier uploadé (le champ 'image' doit être unmapped dans le formulaire)
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('rh_images_directory'),
                    $newFilename
                );
                // Enregistrer le nom du fichier dans l'entité
                $rh->setImage($newFilename);
            } catch (FileException $e) {
                // Affiche une erreur flash ou loggue l'erreur
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                // Tu peux aussi retourner la vue ici avec un message d'erreur
                return $this->render('gestion_ressources_humaines/AddRH.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        // Persister en base
        $entityManager->persist($rh);
        $entityManager->flush();

            
        // Ajout d'un message flash de succès
        $this->addFlash('success', 'Ressource humaine ajoutée avec succès.');

        // Redirection vers la liste (vérifie que la route 'list_app' existe bien)
        return $this->redirectToRoute('list_app');
    }

    return $this->render('home/index.html.twig', [
        'form' => $form->createView(),
        'locations' => json_encode([]), // Si tu n'as pas de localisation, tu peux laisser vide ou gérer autrement
        'labels' => json_encode([]),
        'effects' => '',
        'effectif' => '',
        // Si tu n'as pas de labels, tu peux laisser vide ou gérer autrement
       // Si tu n
    ]);
}
// src/Controller/GestionRessourcesHumainesController.php





    #[Route('/ListRh', name: 'list_app', methods:['GET'])]
    public function ListRH(): Response
    {
        // Récupérer la liste des auteurs
         $rhlist = $this->adminRepo->findAllByAdmin(); 

        // Rendre la vue avec la liste des auteurs
        return $this->render('gestion_ressources_humaines/listeRH.html.twig', [
            'rh' => $rhlist,
        ]);
    }
#[Route('/completed-tasks', name: 'completed_tasks_stats')]
public function completedTasksStats(RessourcesHumainesRepository $repo): Response
{
    $stats = $repo->countCompletedTasksByResource();

    $labels = array_column($stats, 'resource'); // noms des ressources humaines
    $data = array_map('intval', array_column($stats, 'completedTasks')); // nombres de tâches terminées

    return $this->render('home/index.html.twig', [
        'labels' => json_encode($labels),
        'data' => json_encode($data),
        'locations' => json_encode([]),
        'effects' =>json_encode([]),
        'effectif' => json_encode([]), // Si tu n'as pas de localisation, tu peux laisser vide ou gérer autrement
        // Si tu n'as pas de labels, tu peux laisser vide ou gérer autrement
         // Si tu n'as pas de localisation, tu peux laisser vide ou gérer autrement
         // Si tu n'as pas de labels, tu peux laisser vide ou gérer autrement
        // Si tu n
    ]);
}


    #[Route('/gestion/ressourceshumaines/delete/{id}', name: 'app_gestion_ressourceshumaines_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $rh = $this->adminRepo->find($id);

        if (!$rh) {
            throw $this->createNotFoundException('Ressource humaine non trouvée');
        }
        $this->entityManager->remove($rh);
        $this->entityManager->flush();
        $this->addFlash('success', 'Ressource humaine supprimée avec succès !');
        return $this->redirectToRoute('list_app');  
    }
}
