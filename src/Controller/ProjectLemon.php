<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Users;

class ProjectLemon extends Controller
{
  /**
  * @Route("/", name="inscription")
  */
  public function index()
  {
    return $this->render(
      'Lemon/index.html.twig'
    );
  }

  /**
   * @Route("/connexion", name="connexion")
   */
  public function connexion()
  {
    return $this->render(
      'Lemon/connexion.html.twig'
    );
  }

  /**
   * @Route("/gestion", name="gestion")
   */
  public function gestion()
  {
    // demande à Doctrine d'aller chercher le repo de Users
    $repo = $this->getDoctrine()->getRepository(Users::class);

    // demande au repo d'aller cherche les users correspondant au pays sélectionné
    $users = $repo->findByPays("France");

    return $this->render(
      'Lemon/gestion.html.twig', [
        'users' => $users
      ]
    );
  }

  /**
   * @Route("/user/{id}", name="user")
   */
  public function user($id)
  {
    // demande à Doctrine d'aller cherche le repo de Users
    $repo = $this->getDoctrine()->getRepository(Users::class);

    // demande au repo d'aller cherche l'id de l'user affiché
    $users = $repo->find($id);

    return $this->render(
      'Lemon/user.html.twig', [
        'users' => $users
      ]
    );
  }

  /**
   * @Route("/ajouter", name="addUser")
   */
  public function addUser()
  {
    return $this->render(
      'Lemon/addUser.html.twig'
    );
  }
}
