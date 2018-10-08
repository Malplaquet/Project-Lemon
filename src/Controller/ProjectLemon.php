<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;

class ProjectLemon extends Controller
{
  /**
  * @Route("/", name="inscription")
  * @Route("/create", name="addUser")
  * @Route ("user/{id}/edit", name="editUser")
  */
  public function form(Users $users = null, Request $request, ObjectManager $manager)
  {
    // si il n'y a pas d'user existant, on créé une variable users qui contient un nouvel user
    if (!$users) {
      $users = new Users();
    }

    // on prérempli le contenu du formulaire avec le pays récupéré par géolocalisation
    // $users->setPays( ICI VIENNENT LES DONNEES POUR RECUPERER LA GEOLOCALISATION )

    // on créé un formulaire à partir de la fonction createFormBuilder qui ajoutera les informations voulues ici ajoutées par les add(), au sein de la variable users. Le getForm(); final permet de créer une formulaire dont les informations demandées ont été signifiées par les add()
    $form = $this->createFormBuilder($users)
                 ->add('prenom')
                 ->add('nom')
                 ->add('dateDeNaissance', BirthdayType::class, [
                   'format' => 'dd-MM-yyyy'
                 ])
                 ->add('email', EmailType::class)
                 ->add('sexe', ChoiceType::class, [
                   'choices' => [
                     'Femme' => 'Femme',
                     'Homme' => 'Homme',
                     'Non défini' => 'Non défini'
                   ]
                 ])
                 ->add('pays', CountryType::class)
                 ->add('metier', ChoiceType::class, [
                   'choices' => [ // données recueillies sur le site de l'INSEE et adaptées
                      'Agent de maîtrise' => 'Agent de maîtrise',
                      'Agent de surveillance' => 'Agent de surveillance',
                      'Agriculteur' => 'Agriculteur',
                      'Artisan' => 'Artisan',
                      'Cadre administratif' => 'Cadre administratif',
                      'Cadre de la fonction publique' => 'Cadre de la fonction publique',
                      'Chauffeur' => 'Chauffeur',
                      'Chômeur' => 'Chômeur',
                      'Clergé, religieux' => 'Clergé, religieux',
                      'Commerçant' => 'Commerçant',
                      'Commerciaux d\'entreprise' => 'Commerciaux d\'entreprise',
                      'Employé administratif d\'entreprise' => 'Employé administratif d\'entreprise',
                      'Employé administratif de commerce' => 'Employé administratif de commerce',
                      'Employé de la fonction publique' => 'Employé de la fonction publique',
                      'Entrepreneur' => 'Entrepreneur',
                      'Étudiant' => 'Étudiant',
                      'Ingénieur' => 'Ingénieur',
                      'Ouvrier' => 'Ouvrier',
                      'Ouvrier agricole' => 'ouvrier agricole',
                      'Ouvrier qualifié' => 'ouvrier qualifié',
                      'Personnel des services directs aux particuliers' => 'Personnel des services directs aux particuliers',
                      'Professeur' => 'Professeur',
                      'Profession de l\'information, des arts et spectacles' => 'Profession de l\'information, des arts et spectacles',
                      'Profession intermédiaire administratives et commerciales' => 'Profession intermédiaire administratives et commercialese',
                      'Profession intermédiaire administratives de la fonction publique' => 'Profession intermédiaire administratives de la fonction publique',
                      'Profession intermédiaire de la santé et du travail social' => 'Profession intermédiaire de la santé et du travail social',
                      'Profession libérale' => 'Profession libérale',
                      'Salarié' => 'Salarié',
                      'Technicien' => 'Technicien',
                      'Autre' => 'Autre'
                   ]
                 ])
                 ->getForm();

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {
      $manager->persist($users);
      $manager->flush();
    }

    return $this->render(
      'Lemon/form.html.twig', [
        'formInscription' => $form->createView(), // on demande à twig d'afficher le résultat du formulaire et non pas le formualaire en temps que méthode complexe
        'editMode' => $users->getId() !== null
      ]
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
}
