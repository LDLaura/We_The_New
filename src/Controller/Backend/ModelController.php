<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/model', name: 'admin.model')]
class ModelController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index')]
    public function index(): Response
    {
        return $this->render('backend/model/index.html.twig', [
            'controller_name' => 'ModelController',
        ]);
    }

    #[Route('/create', name:'.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success', 'Le modèle a bien été créé');

            return $this->redirectToRoute('admin.model.index');
        }
        return $this->render('Backend/Model/create.html.twig', [
            'form' => $form
        ]);
    }
}
