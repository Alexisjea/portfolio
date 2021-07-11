<?php

namespace App\Controller;

use App\Entity\Maquettes;
use Cocur\Slugify\Slugify;
use App\Form\MaquettesType;
use App\Repository\MaquettesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/maquettes')]
class MaquettesController extends AbstractController
{
    #[Route('/', name: 'maquettes_index', methods: ['GET'])]

    public function index(MaquettesRepository $maquettesRepository): Response
    {
        return $this->render('maquettes/index.html.twig', [
            'maquettes' => $maquettesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'maquettes_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $maquette = new Maquettes();
        $form = $this->createForm(MaquettesType::class, $maquette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slugify = new Slugify();
            $apercu = $form->get('apercu')->getData();
            $fileName = $slugify->slugify($maquette->getTitre()) .  '.' . $apercu->guessExtension();

            try {
                $apercu->move($this->getParameter('maquettes_assets_dir'), $fileName);
            } catch (FileException $e) {
                //... gèrer les exeptions survenues lors duu téléchargement de l'image 
            }
            $maquette->setApercu($fileName);
            // fin du traitement image
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maquette);
            $entityManager->flush();
            return $this->redirectToRoute('maquettes_index');
        }

        return $this->render('maquettes/new.html.twig', [

            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'maquettes_show', methods: ['GET'])]
    public function show(Maquettes $maquette): Response
    {
        return $this->render('maquettes/show.html.twig', [
            'maquette' => $maquette,
        ]);
    }

    #[Route('/{id}/edit', name: 'maquettes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maquettes $maquette): Response
    {
        $form = $this->createForm(MaquettesType::class, $maquette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('maquettes_index');
        }

        return $this->render('maquettes/edit.html.twig', [
            'maquette' => $maquette,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'maquettes_delete', methods: ['POST'])]
    public function delete(Request $request, Maquettes $maquette): Response
    {
        if ($this->isCsrfTokenValid('delete' . $maquette->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($maquette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('maquettes_index');
    }
}
