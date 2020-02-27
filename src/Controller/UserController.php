<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route(
     *     "/user",
     *     name="createUser"
     * )
     */
    public function createUser(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute("listAllUser");
    }

    /**
     * @Route(
     *     "/formUser",
     *     name="createFormUser"
     * )
     */
    public function CreateUserForm()
    {
        return $this->render('user/form.html.twig', [
            'action' => "createUser"
        ]);
    }


    /**
     * @Route(
     *     "/updateUser/{id}",
     *     name="updateUser"
     * )
     */
    public function updateUser($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        //Check if exist
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        if ($request->request->get("name")){
            $name = $request->request->get("name");
            $user->setName($name);
        }else{
            $name = $user->getName();
        }

        if ($request->request->get("email")){
            $email = $request->request->get("email");
            $user->setEmail($email);
        }else{
            $price = $user->getEmail();
        }

        if ($request->request->get("password")){
            $desc = $request->request->get("password");
            $user->setPassword($desc);
        }else{
            $desc = $user->getPassword();
        }

        $entityManager->flush();

        return $this->redirectToRoute("listAllUser");
    }

    /**
     * @Route(
     *     "/updateFormUser/{id}",
     *     name="updateFormUser"
     * )
     */
    public function updateUserForm($id)
    {
        return $this->render('user/formWithId.html.twig', [
            'action' => "updateUser",
            '_id' => $id
        ]);
    }

    /**
     * @Route(
     *     "/deleteUser/{id}",
     *     name="deleteUser"
     * )
     */
    public function deleteUser($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        //Check if exist
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("listAllUser");
    }

    /**
     * @Route(
     *     "/listAllUser",
     *     name="listAllUser"
     * )
     */
    public function listAllProduct()
    {
        $products = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render(
            'user/table.html.twig',
            [
                'user' => $products,
            ]
        );
    }

    /**
     * @Route(
     *     "/User",
     *     name="indexUser"
     * )
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }
}
