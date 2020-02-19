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
     * @Route("/product", name="create")
     */
    public function createProduct(Request $request): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('desc'));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Producto guardado con el numero: ' . $product->getId());
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
     * @Route("/form", name="form")
     */
    public function formSimple()
    {
        return $this->render('product/form.html.twig', [
            'action' => "create"
        ]);
    }

    /**
     * @Route("/formId", name="formId")
     */
    public function formId()
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
