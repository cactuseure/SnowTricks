<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/',name: 'app_home_index')]
    public function index():Response
    {
        return $this->render('home/home.html.twig');
    }


    #[Route('/mail',name: 'app_mail_index')]
    public function mail(MailerInterface $mailer):Response
    {
        try {
            $email = (new Email())
                ->from('mail@snowtricks.matteo-groult.com')
                ->to('mattgroult10@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $mailer->send($email);
        }catch (\Exception $exception){
            dd($exception);
        }

        return $this->render('home/home.html.twig');
    }
}