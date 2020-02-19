<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route(
     *     "/product",
     *     name="create"
     * )
     */
    public function createProduct(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('desc'));

        $entityManager->persist($product);

        $entityManager->flush();

        return new Response('Producto guardado con el numero: ' . $product->getId());
    }

    /**
     * @Route(
     *     "/update/{id}",
     *     name="update"
     * )
     */
    public function updateProduct($id)
    {

    }

    /**
     * @Route(
     *     "/delete/{id}",
     *     name="delete"
     * )
     */
    public function deleteProduct($id)
    {

    }

    /**
     * @Route(
     *     "/listAll",
     *     name="listAll"
     * )
     */
    public function listAllProduct()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)->findAll();

        return $this->render(
            'product/table.html.twig',
            [
                'product' => $products
            ]
        );
    }

    /**
     * @Route(
     *     "/form",
     *     name="createForm"
     * )
     */
    public function CreateProductForm()
    {
        return $this->render('product/form.html.twig', [
            'action' => "create"
        ]);
    }

    /**
     * @Route(
     *     "/",
     *     name="index"
     * )
     */
    public function index()
    {
        return $this->render('product/index.html.twig');
    }
}
