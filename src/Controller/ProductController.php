<?php

namespace App\Controller;

use App\Entity\Product;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create")
     */
    public function createProduct(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    /**
     * @Route("/update", name="update")
     */
    public function updateProduct()
    {

    }

    /**
     * @Route("/list", name="list")
     */
    public function listProduct()
    {

    }

    /**
     * @Route("/list", name="listAll")
     */
    public function listAllProduct()
    {

    }

    /**
     * @Route("/delete", name="delete")
     */
    public function deleteProduct()
    {

    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('product/index.html.twig');
    }
}
