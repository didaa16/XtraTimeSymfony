<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{

    private $provider;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        // Assign tokenStorage parameter to the class property
        $this->tokenStorage = $tokenStorage;

        // Initialize Facebook provider
        $this->provider = new Facebook([
            'clientId' => $_ENV['FCB_ID'],
            'clientSecret' => $_ENV['FCB_SECRET'],
            'redirectUri' => $_ENV['FCB_CALLBACK'],
            'graphApiVersion' => 'v19.0',
        ]);
    }



    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/', name: 'app_front')]
    public function front(): Response
    {
        return $this->render('home/front.html.twig' ,[
            'title' => 'welcome',
        ]);
    }


    #[Route('/back', name: 'app_back')]
    public function back(UtilisateursRepository $userRepository): Response
    {
        // 1. Extraire les données de la base de données
        $usersByRole = $userRepository->countUsersByRole();

        // 2. Préparer les données pour le graphique
        $labels = [];
        $data = [];
        $totalUsers = 0;

        // Calculer le nombre total d'utilisateurs
        foreach ($usersByRole as $roleData) {
            $totalUsers += $roleData['count'];
        }

        foreach ($usersByRole as $roleData) {
            $role = $roleData['roles'];
            $count = $roleData['count'];
            $labels[] = $role;
            $percentage = ($count / $totalUsers) * 100;
            $data[] = round($percentage, 2);
        }


        // 3. Afficher le graphique
        return $this->render('home/back.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }

    #[Route('/banned', name: 'app_banned')]
    public function banned(): Response
    {
        return $this->render('home/banned.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/fcb-login', name: 'fcb_login')]
    public function fcbLogin(): Response
    {

        $helper_url=$this->provider->getAuthorizationUrl();

        return $this->redirect($helper_url);
    }

    #[Route('/fcb-callback', name: 'fcb_callback')]
    public function fcbCallBack(UtilisateursRepository $userDb, EntityManagerInterface $manager): Response
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        try {
            $user = $this->provider->getResourceOwner($token);
            $userData = $user->toArray(); // Convert user object to array
            $pseudo = $userData['id'];
            $email = $userData['email'];
            $nom = $userData['first_name'];
            $prenom = $userData['last_name'];
            $userExist = $userDb->findBySearch($pseudo);
            if ($userExist) {
                $existingUser = $userExist[0]; // Assuming $userExist is an array of Utilisateurs objects, retrieve the first one
                $token = new UsernamePasswordToken($existingUser, null, 'main', $existingUser->getRoles());
                $this->tokenStorage->setToken($token);
            } else {
                $newUser = new Utilisateurs();
                $newUser->setPseudo($pseudo)
                    ->setRoles(["Client"])
                    ->setNom($nom)
                    ->setPrenom($prenom)
                    ->setEmail($email)
                    ->setIsVerified(true)
                    ->setPassword(sha1(str_shuffle('abscdop123390hHHH;:::OOOI')));
                $manager->persist($newUser);
                $manager->flush();
                $token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
                $this->tokenStorage->setToken($token);
            }
            return $this->render('home/front.html.twig', [
                'title' => 'welcome',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }



}
