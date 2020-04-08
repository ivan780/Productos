<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    public function doCreateProduct(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('desc'));
        $product->setUser($this->getUser());

        $entityManager->persist($product);

        $entityManager->flush();

        return $this->redirectToRoute('listAll');
    }


    public function createProduct()
    {
        $user = $this->getUser()->getUsername();

        return $this->render('product/form.html.twig', [
            'email' => $user
        ]);
    }


    public function updateProduct($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        //Check if exist
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->flush();

        return $this->redirectToRoute("listAll");


    }


    public function doUpdateProduct($id)
    {
        $user = $this->getUser()->getUsername();

        return $this->render('product/formWithId.html.twig', [
            'action' => "update",
            '_id' => $id,
            'email' => $user
        ]);
    }


    public function deleteProduct($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        //Check if exist
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("listAll");
    }


    public function listAllProduct()
    {
        $user = $this->getUser();
        $products = $this->getDoctrine()->getRepository(Product::class);
        $pro = $products->findBy(['user' => $user->getId()]);
        $user = $this->getUser()->getUsername();

        return $this->render(
            'product/table.html.twig',
            [
                'product' => $pro,
                'email' => $user

            ]
        );
    }


    public function index()
    {
        $user = $this->getUser()->getUsername();
        return $this->render('product/index.html.twig', [
            'email' => $user
        ]);
    }
}
