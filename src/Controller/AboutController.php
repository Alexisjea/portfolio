<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\AboutType;
use App\Repository\AboutRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/about")
 * @IsGranted("ROLE_ADMIN", statusCode=404, message="Cette page n'existe pas ! Erreur 404")
 */
class AboutController extends AbstractController
{
    #[Route('/', name: 'about_index', methods: ['GET'])]
    public function index(AboutRepository $aboutRepository): Response
    {
        return $this->render('about/index.html.twig', [
            'abouts' => $aboutRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'about_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $about = new About();
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($about);
            $entityManager->flush();

            return $this->redirectToRoute('about_index');
        }

        return $this->render('about/new.html.twig', [
            'about' => $about,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'about_show', methods: ['GET', 'POST'])]
    public function show(About $about, $id): Response
    {
        $about = $this->getDoctrine()->getRepository(About::class)->find($id);

        return $this->render('about/show.html.twig', [
            'about' => $about,
        ]);
    }

    #[Route('/{id}/edit', name: 'about_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, About $about): Response
    {
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('about_index');
        }

        return $this->render('about/edit.html.twig', [
            'about' => $about,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'about_delete', methods: ['POST'])]
    public function delete(Request $request, About $about): Response
    {
        if ($this->isCsrfTokenValid('delete' . $about->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($about);
            $entityManager->flush();
        }

        return $this->redirectToRoute('about_index');
    }
}
