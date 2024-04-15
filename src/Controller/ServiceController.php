<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{

  
    #[Route('/Client', name: 'Home' )]
    public function index(): Response {
        return $this->render('/front.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }

    #[Route('/About', name: 'About' )]
 public  function About() :Response {
    return $this -> render('/Client/about.html.twig');
 }

 #[Route('/Package', name: 'Package' )]
 public  function Package() :Response {
    return $this -> render('/Client/package-v2.html.twig');
 }

 #[Route('/MoreDetailsPackage', name: 'MoreDetailsPackage' )]
 public  function MoreDetailsPackage() :Response {
    return $this -> render('/Client/single-package.html.twig');
 }

 #[Route('/JoinNowPackage', name: 'JoinNowPackage' )]
 public  function JoinNowPackage() :Response {
    return $this -> render('/Client/pricing.html.twig');
 }

 #[Route('/shop', name: 'shop' )]
 public  function shop() :Response {
    return $this -> render('/Client/shop.html.twig');
 }


 #[Route('/MoreDetailsShop', name: 'MoreDetailsShop' )]
 public  function MoreDetailsShop() :Response {
    return $this -> render('/Client/single-shop.html.twig');
 }

 #[Route('/AddCart', name: 'AddCart' )]
 public  function AddCart() :Response {
    return $this -> render('/Client/cart.html.twig');
 }

 #[Route('/CHECKOUTShop ', name: 'CHECKOUTShop' )]
 public  function CHECKOUTShop() :Response {
    return $this -> render('/Client/checkout.html.twig');
 }


 
}
