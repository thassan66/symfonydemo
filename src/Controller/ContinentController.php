<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Form\ContinentType;
use App\Repository\ContinentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/continent')]
class ContinentController extends AbstractController
{
    #[Route('/', name: 'continent_index', methods: ['GET'])]
    public function index(ContinentRepository $continentRepository): Response
    {
        return $this->render('continent/index.html.twig', [
            'continents' => $continentRepository->findAll(),
        ]);
    }

    #[Route('/continent/new', name: 'add_new', methods: ['GET', 'POST'])]
    public function show(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $continentRepository = $em->getRepository(Continent::class);

        // Find all the data on the Appointments table, filter your query as you need
        $continentQuery = $continentRepository->createQueryBuilder('c')
            ->getQuery();

        // Paginate the results of the query
        $continents = $paginator->paginate(
        // Doctrine Query, not results
            $continentQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),
        );

        // Render the twig view
        return $this->render('continent/show.html.twig', [
            'continents' => $continents
        ]);
    }

    #[Route('/{id}', name: 'continent_show', methods: ['GET'])]
    public function show(Continent $continent): Response
    {
        return $this->render('continent/show.html.twig', [
            'continent' => $continent,
        ]);
    }

    #[Route('/{id}/edit', name: 'continent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Continent $continent): Response
    {
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('continent_index');
        }

        return $this->render('continent/edit.html.twig', [
            'continent' => $continent,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'continent_delete', methods: ['DELETE'])]
    public function delete(Request $request, Continent $continent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $continent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($continent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('continent_index');
    }
}
