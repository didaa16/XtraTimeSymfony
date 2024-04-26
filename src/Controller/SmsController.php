<?php

namespace App\Controller;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    #[Route('/sms', name: 'app_sms')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig', [
            'controller_name' => 'SmsController',
        ]);
    }

    #[Route('/sendSms', name: 'send_sms', methods:'POST')]
    public function sendSms(Request $request, SmsGenerator $smsGenerator): Response
    {

        return $this->render('sms/index.html.twig', ['smsSent'=>true]);
    }

}
