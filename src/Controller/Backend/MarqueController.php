<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/marque', name:'admin.marque')]
class MarqueController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ){  
    }

    #[Route('', name: '.index')]
    public function index(): Response
    {
        return $this->render('backend/marque/index.html.twig', [
            'controller_name' => 'MarqueController',
        ]);
    }

    #[Route('/create', name:'.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'La marque a bien été créé');

            return $this->redirectToRoute('admin.marque.index');
        }
        return $this->render('Backend/Marque/create.html.twig', [
            'form' => $form
        ]);
    }
}
