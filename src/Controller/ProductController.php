<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Entity\Appointments;

// Incluir interfaz de paginador
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="inicio")
     */
    public function index(Request $request, PaginatorInterface $paginator, ManagerRegistry $doctrine): Response
    {
        $appointmentsRepository = $doctrine->getRepository(Product::class);

        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')->getQuery();

        $appointments = $paginator->paginate(
            $allAppointmentsQuery,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('product/index.html.twig', [
            'productos' => $appointments
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idCategory = $request->request->all()["product"]["categoria"];
            $category = $doctrine->getRepository(Category::class)->find($idCategory);

            $product->setCategory($category);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('detail', ['id' => $product->getId()]);
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="edit")
     */
    public function edit(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $idCategory = $request->request->all()["product"]["categoria"];
            $category = $doctrine->getRepository(Category::class)->find($idCategory);
            $product->setCategory($category);
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
        }

        return $this->render('product/edit.html.twig', [
            'id' => $product->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="delete")
     */
    public function destroy(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('inicio');
    }

}
