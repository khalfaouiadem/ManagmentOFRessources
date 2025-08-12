<?php

namespace App\Controller;

use App\Repository\EffectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
        private $effectRepo;
    public function __construct(EffectRepository $effectRepositoryParam)
    {
        $this->effectRepo = $effectRepositoryParam;
    }   
#[Route('/home', name: 'app_homes')]
public function home(): Response
{
    $effectif = $this->effectRepo->findAll();

    return $this->render('home/index.html.twig', [
        'effectif' => $effectif,
    ]);
}

  
}
