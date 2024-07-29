<?php

namespace App\Controller\Backend;

use App\Entity\ProductImage;
use App\Form\ProductImageType;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/productimage', name:'admin.productimage')]
class ProductImageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ){
    }

    #[Route('', name: '.index')]
    public function index(ProductImageRepository $repo): Response
    {
        return $this->render('Backend/Product_image/index.html.twig', [
            'productimage' => $repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods:['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $productImg = new ProductImage();
        $form = $this->createForm(ProductImageType::class, $productImg);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($productImg);
            $this->em->flush();

            $this->addFlash('success', 'L\'image du produit a bien été créée');

            return $this->redirectToRoute('admin.productimage.index');
        }
        return $this->render('Backend/Product_image/create.html.twig', [
            'form' => $form
        ]);
    }
}
