<?php

namespace App\Controller;


use Swift;
use Mailer;
use App\Entity\About;
use App\Entity\Parcours;
use App\Entity\Maquettes;
use App\Form\ContactType;
use App\Entity\Competences;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]


    public function index(Request $request, \Swift_Mailer $mailer): Response

    {
        // $dernierlangage = $this->getDoctrine()->getRepository(Competences::class)->findOneBy([], ["Type" => "DESC"]);
        // $contact = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        $competence = $this->getDoctrine()->getRepository(Competences::class)->findAll();
        $maquette = $this->getDoctrine()->getRepository(Maquettes::class)->findAll();
        $parcours = $this->getDoctrine()->getRepository(Parcours::class)->findAll();
        $about = $this->getDoctrine()->getRepository(About::class)->findAll();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            $message = (new \Swift_Message('Nouveau Contact'))
                ->setFrom($contact['email'])
                ->setTo('alexisjeandenans@gmail.com')
                ->setBody(
                    $this->renderView(
                        'email/contact.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('message', 'Le message a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'about'  => $about,
            'parcours' => $parcours,
            'competences' => $competence,
            'maquettes' => $maquette,
            'contactForm' => $form->createView(),
        ]);
    }
}
