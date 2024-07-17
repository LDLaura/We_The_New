<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/gender', name: 'admin.gender')]
class GenderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET', 'POST'])]
    public function index(GenderRepository $repo): Response
    {
        return $this->render('Backend/Gender/index.html.twig', [
            'gender' => $repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Gender $gender, Request $request): Response
    {
        if (!$gender) {
            $this->addFlash('error', 'Le genre n\'existe pas');

            return $this->redirectToRoute('admin.gender.index');
        }

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a bien été modifié');

            return $this->redirectToRoute('admin.gender.index');
        }

        return $this->render('Backend/Gender/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'.delete', methods:['POST'])]
    public function delete(?Gender $gender, Request $request): RedirectResponse{
        if (!$gender) {
            $this->addFlash('error', 'La categorie demandée n\'existe pas');

            return $this->redirectToRoute('admin.gender.index');
        }
        if ($this->isCsrfTokenValid('delete' .$gender->getId(), $request->request->get('token'))) {
            $this->em->remove($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a bien été supprimé');
        } else {
            $this->addFlash('error', 'Le jeton CSRF a bien été supprimé');
        }
        return $this->redirectToRoute('admin.gender.index');
    }
}
