<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    #[Route('', name: '.index', methods:['GET'])]
    public function index(ModelRepository $repo): Response
    {
        return $this->render('Backend/Model/index.html.twig', [
            'model' => $repo->findAll(),
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

    #[Route('/{id}/update', name:'.update', methods:['GET', 'POST'])]
    public function update(?Model $model, Request $request): Response
    {
        if (!$model) {
            $this->addFlash('error', 'Le modèle n\'existe pas');

            return $this->redirectToRoute('admin.model.index');
        }
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();

            $this->addFlash('success','Le modèle a bien été modifié');

            return $this->redirectToRoute('admin.model.index');
        }
        return $this->render('Backend/Model/update.html.twig', [
            'form'=> $form,
        ]);
    }

    #[Route('/{id}/delete', name:'.delete', methods: ['POST'])]
    public function delete(?Model $model, Request $request): RedirectResponse
    {
        if (!$model) {
            $this->addFlash('success', 'Le modèle a bien été supprimé');

            return $this->redirectToRoute('admin.model.index');
        }
        if ($this->isCsrfTokenValid('delete' .$model->getId(), $request->request->get('token'))) {
            $this->em->remove($model);
            $this->em->flush();

            $this->addFlash('success', 'Le modèle a bien été supprimé');
        } else {
            $this->addFlash('error', 'Le jeton CSRF a bien été supprimé');
        }
        return $this->redirectToRoute('admin.model.index');
    }
}
