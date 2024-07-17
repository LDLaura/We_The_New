<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/gender', name: 'admin.gender')]
class GenderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ){  
    }

    #[Route('/create', name: '.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $gender = new Gender();

        $form = $this->createForm(GenderType::class, $gender);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();
            $this->addFlash('success', 'Le genre a bien été créé');

            return $this->redirectToRoute('admin.gender.index');
        }

        return $this->render('Backend/Gender/create.html.twig', [
            'form' => $form,
        ]);
    }
}
