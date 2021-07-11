<?php

namespace App\Controller;

use Mailer;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {

        return $this->render('contact/index.html.twig', [
            'contact' => $contactRepository->findAll(),

        ]);
    }
}
