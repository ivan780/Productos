<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


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

        return $this->redirectToRoute("changePass", ['email' => $email]);
    }


    public function changePass($email)
    {
        return $this->render('security/changePass.html.twig', [
            'email' => $email
        ]);
    }


    public function doChangePass(Request $request)
    {
        $email = $request->request->get('email');
        if (!$email) {
            return $this->redirectToRoute("resetPass");
        }

        $pass = $request->request->get('pass');
        if (!$pass) {
            return $this->redirectToRoute("changePass");
        }



        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find('6');

        //Check if exist
        if (!$user) {
            throw $this->createNotFoundException(
                'No user with this email:' . $email
            );
        }
        $encodePass = $this->passwordEncoder->encodePassword(
            $user,
            $pass
        );

        $user = $entityManager->getRepository(User::class)->upgradePassword($user, $encodePass);


        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute("appLogin");
    }

    public function signUp() {
        return $this->render('security/signUp.html.twig');
    }

    public function doSignUp(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();


        $email = $request->request->get('email');
        $pass = $request->request->get('pass');
        if (!$email && !$pass) {
            return $this->redirectToRoute("signUp");
        }
        $encodePass = $this->passwordEncoder->encodePassword(
            $user,
            $pass
        );

        $user->setEmail($email);
        $user->setPassword($encodePass);
        $user->setRoles((array)"ROLE_USER");

        $entityManager->persist($user);
        $entityManager->flush();


        return $this->redirectToRoute("appLogin");
    }

    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
