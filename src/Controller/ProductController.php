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

        return $this->redirectToRoute('index');
    }


    public function createProduct()
    {
        $user = $this->getUser()->getUsername();

        return $this->render('product/form.html.twig', [
            'email' => $user
        ]);
    }


    public function doUpdateProduct($id, Request $request)
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        //Check if exist
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if ($product->getUser() != $user) {
            throw $this->createNotFoundException(
                'This product is not created by you '.$id
            );
        }

        $name = $request->request->get('name');
        if ($name){
            $product->setName($name);
        }
        $price = $request->request->get('price');
        if ($price){
            $product->setPrice($price);
        }
        $desc = $request->request->get('desc');
        if ($desc){
            $product->setDescription($desc);
        }

        $entityManager->flush();

        return $this->redirectToRoute("index");


    }


    public function updateProduct($id)
    {
        $user = $this->getUser()->getUsername();

        return $this->render('product/formWithId.html.twig', [
            'action' => "doUpdate",
            '_id' => $id,
            'email' => $user
        ]);
    }


    public function doDeleteProduct($id)
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        //Check if exist
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if ($product->getUser() != $user) {
            throw $this->createNotFoundException(
                'This product is not created by you '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("index");
    }


    public function listAll(){
        $user = $this->getUser()->getUsername();
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render(
            'product/table.html.twig',
            [
                'product' => $products,
                'email' => $user

            ]
        );
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
