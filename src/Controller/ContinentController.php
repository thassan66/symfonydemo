<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Form\ContinentType;
use App\Repository\ContinentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/continent')]
class ContinentController extends AbstractController
{


    #[Route('/all', name: 'view_continents', methods: ['GET'])]
    public function show(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $continentRepository = $em->getRepository(Continent::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allCountriesQuery = $continentRepository->createQueryBuilder('c')
            ->getQuery();

        // Paginate the results of the query
        $continents = $paginator->paginate(
        // Doctrine Query, not results
            $allCountriesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),
        );

        // Render the twig view
        return $this->render('continent/show.html.twig', [
            'continents' => $continents
        ]);
    }

    #[Route('/new', name: 'add_continent')]
    public function addCountry(Request $request): Response
    {

        $country = new Continent();

        $form = $this->createFormBuilder($country)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $country = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('view_continents');
        }

        return $this->render('continent/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update_continent')]
    public function edit(Request $request, Continent $continent): Response
    {
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('view_continents');
        }

        return $this->render('continent/edit.html.twig', [
            'continent' => $continent,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'continent_delete')]
    public function delete($id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository(Continent::class)->find($id);

        if (!$country) {
            throw $this->createNotFoundException(
                'There is no country with the following id: ' . $id
            );
        }
        try {

            $em->remove($country);
            $em->flush();
        } catch (\Exception) {
            throw $this->createNotFoundException(
                'Unable to Delete record against id: ' . $id
            );
        }
        return $this->redirectToRoute('view_continents');
    }
}
