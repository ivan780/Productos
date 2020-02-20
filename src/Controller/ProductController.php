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
     *     "/update/{id}",
     *     name="update"
     * )
     */
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

        if ($request->request->get("name")){
            $name = $request->request->get("name");
            $product->setName($name);
        }else{
            $name = $product->getName();
        }

        if ($request->request->get("price")){
            $price = $request->request->get("price");
            $product->setPrice($price);
        }else{
            $price = $product->getPrice();
        }

        if ($request->request->get("desc")){
            $desc = $request->request->get("desc");
            $product->setDescription($desc);
        }else{
            $desc = $product->getDescription();
        }

        $entityManager->flush();

        return $this->render('product/show.html.twig', [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'desc' => $desc,
        ]);


    }

    /**
     * @Route(
     *     "/updateForm/{id}",
     *     name="updateForm"
     * )
     */
    public function updateProductForm($id)
    {
        return $this->render('product/formWithId.html.twig', [
            'action' => "update",
            '_id' => $id
        ]);
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
     *     "/",
     *     name="index"
     * )
     */
    public function index()
    {
        return $this->render('product/index.html.twig');
    }
}
