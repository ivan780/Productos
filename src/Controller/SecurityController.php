<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('listAll');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }


    public function resetPass()
    {
        return $this->render('security/resetPass.html.twig');
    }


    public function doResetPass(Request $request)
    {
        $email = $request->request->get('email');
        if (!$email) {
            return $this->redirectToRoute("resetPass");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findEmail($email);

        //Check if exist
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with this email:' . $email
            );
        }

        return $this->redirectToRoute("changePass");
    }


    public function changePass(){
        return $this->render('security/changePass.html.twig');
    }


    public function doChangePass(Request $request) {

    }

    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
