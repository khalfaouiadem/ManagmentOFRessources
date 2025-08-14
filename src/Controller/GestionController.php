<?php

namespace App\Controller;

use App\Entity\Effect;
use App\Entity\Poste;
use App\Form\EffectifsType;
use App\Form\PosteType;
use App\Repository\EffectRepository;
use App\Repository\PosteRepository;
use App\Repository\RessourcesHumainesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class GestionController extends AbstractController
{


    private $effectRepo;
    private $entityManager;



    public function __construct(EffectRepository $adminRepositoryParam, EntityManagerInterface $entityManagerParam)
    {
        $this->effectRepo = $adminRepositoryParam;
        $this->entityManager = $entityManagerParam;
    }
#[Route('/AddEffectifs', name: 'add_effectifs', methods: ['GET', 'POST'])]
public function AddEffec(
    Request $request,
    EntityManagerInterface $em,
    SluggerInterface $slugger,
    MailerInterface $mailer
): Response {
    $effectifs = new Effect();
    $form = $this->createForm(EffectifsType::class, $effectifs);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image_effect')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('effect_images_directory'), // à configurer dans services.yaml
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
            }

            $effectifs->setImageEffect($newFilename);
        }

        $em->persist($effectifs);
        $em->flush();

       
       try {
                $emailContent = "
                    <h2>Bonjour {$effectifs->getNom()},</h2>
                    <p>Merci d'avoir rejoint notre plateforme <strong>ManagamentOfHumanRessources</strong> en tant que {$effectifs->getPoste()}  !</p>
                    <p>Voici un récapitulatif de vos informations :</p>
                    <ul>
                        <li><strong>Nom :</strong> {$effectifs->getNom()}</li>
                        <li><strong>Email :</strong> {$effectifs->getEmail()}</li>
                    </ul>
                    <p>Nous sommes ravis de collaborer avec vous.</p>
                ";
    
                $email = (new Email())
                    ->from('testprojetpi@gmail.com')
                    ->to($effectifs->getEmail())  // You might want to use $sponsor->getEmail() here
                    ->subject('Bienvenue parmi nos effectifs !')
                    ->html($emailContent);
    
                // Envoi de l'email
                $mailer->send($email);
               
    
                // Si l'email est envoyé avec succès
                $this->addFlash('success', 'Sponsor ajouté avec succès et e-mail envoyé à ' . $effectifs->getEmail());
            } catch (\Exception $e) {
                // Si l'envoi de l'email échoue
              
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de l\'email.');
                return $this->redirectToRoute('add_sponsor');
            }
    

        $this->addFlash('success', 'Effectif ajouté avec succès et email envoyé !','à',$effectifs);
        return $this->redirectToRoute('list_effectifs');
    }

    return $this->render('gestion/AddEffectifs.html.twig', [
        'form' => $form->createView(),
    ]);
}

   #[Route('/home', name: 'stats_app')]
public function dashboard(): Response
{
    $stats = $this->effectRepo->countByPoste();
    $effectif = $this->effectRepo->findAll();

    $labels = array_column($stats, 'poste');
    $data = array_column($stats, 'total');

    return $this->render('home/index.html.twig', [
        'labels' => json_encode($labels),
        'data' => json_encode($data)
        ,
        'locations' => json_encode([]), 
        'effectif' => $effectif,
        
        // Si tu n'as pas de localisation, tu peux laisser vide ou gérer autrement
         // Si tu n'as pas de labels, tu
        // Si tu n
        // Si tu n'as pas de labels, tu
         // Si tu n'as pas de données, tu
    ])->setStatusCode(Response::HTTP_OK); // Assurez-vous que la réponse est
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
    #[Route('/ListEffectifs', name: 'list_effectifs', methods:['GET'])]
    public function ListEffectifs(): Response
    {
        // Récupérer la liste des auteurs
        $effectifs = $this->effectRepo->findAll(); 

        // Rendre la vue avec la liste des auteurs
        return $this->render('gestion/listEffectifs.html.twig', [
            'effectifs' => $effectifs,
            
        ]);
    }
    
    public function index(): Response
    {
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
    #[Route('/DeleteEffectifs/{id}', name: 'app_delete_effectifs', methods: ['GET'])]
    public function DeleteEffectifs(int $id): Response
    {
        // Récupérer l'auteur par son ID
        $effectifs = $this->effectRepo->find($id);
        if (!$effectifs) {
            throw $this->createNotFoundException('Effectifs non trouvé');
        }   
        // Supprimer l'auteur
        $this->entityManager->remove($effectifs);
        $this->entityManager->flush();      
        // Rediriger vers la liste des auteurs
        return $this->redirectToRoute('list_effectifs');



    }    #[Route('/UpdateEffectifs/{id}', name: 'app_update_effectifs', methods: ['GET', 'POST'])]
    public function UpdateEffectifs(Request $request, int $id): Response   
    {
        // Récupérer l'auteur par son ID
        $effectifs = $this->effectRepo->find($id);
        if (!$effectifs) {
            throw $this->createNotFoundException('Effectifs non trouvé');
        }

        $form = $this->createForm(EffectifsType::class, $effectifs);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('list_effectifs'); // Redirige vers la liste des auteurs
        }

        return $this->render('gestion/UpdateEffectifs.html.twig', [
            'form' => $form->createView(),
            'effectifs' => $effectifs,
            'effects' => json_encode([]), // Si tu n'as pas de localisation, tu peux laisser vide ou gérer autrement
        
        ]);
    }
   
}
