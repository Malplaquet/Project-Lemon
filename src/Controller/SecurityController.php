<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Admin;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
      $admin = new Admin();

      $form = $this->createForm(RegistrationType::class, $admin);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($admin, $admin->getPassword());
        $admin->setPassword($hash);
        $manager->persist($admin);
        $manager->flush();

        return $this->redirectToRoute('security_login');
      }

        return $this->render('security/registration.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(){
      return $this->render('security/login.html.twig');
    }

    /**
    * @Route("/deconnexion", name="security_logout")
    */
    public function logout() {}
}
