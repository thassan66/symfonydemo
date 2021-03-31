<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Entity\Country;
use App\Form\CountryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class CountryController extends AbstractController
{


    /**
     * @Route("/country/all", name="view_countries")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function show(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $countriesRepository = $em->getRepository(Country::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allCountriesQuery = $countriesRepository->createQueryBuilder('c')
            ->getQuery();

        // Paginate the results of the query
        $countries = $paginator->paginate(
        // Doctrine Query, not results
            $allCountriesQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),
        );

        // Render the twig view
        return $this->render('country/show.html.twig', [
            'countries' => $countries
        ]);
    }

    /**
     * @Route("/country/new",name="add_country")
     * @param Request $request
     * @return Response
     */
    public function addCountry(Request $request): Response
    {

        $country = new Country();

        $form = $this->createFormBuilder($country)
            ->add('name', TextType::class)
            ->add('alpha2Code', TextType::class)
            ->add('continent', ChoiceType::class, [
                'choices' => [
                    $this->getDoctrine()
                        ->getRepository(Continent::class)
                        ->findAll()
                ]])
            ->add('currencyCode', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        if (isset($form)) {
            $form->handleRequest($request);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $country = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('view_countries');
        }

        return $this->render('country/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/country/{id}", name="view_country")
     * @param $id
     * @return Response
     */
    public
    function getCountryById($id): Response
    {

        $country = $this->getDoctrine()
            ->getRepository(Country::class)
            ->find($id);

        if (!$country) {
            throw $this->createNotFoundException(
                'There is no country with the following id: ' . $id
            );
        }

        return $this->render(
            'country/view.html.twig',
            array('country' => $country)
        );

    }


    /**
     * @Route("/country/delete/{id}", name="delete_country")
     */
    public
    function deleteCountry($id): RedirectResponse
    {

        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository(Country::class)->find($id);

        if (!$country) {
            throw $this->createNotFoundException(
                'There is no country with the following id: ' . $id
            );
        }

        $em->remove($country);
        $em->flush();

        return $this->redirect('/show-countries');

    }


    /**
     * @Route("/country/update/{id}", name="update_country")
     * @return RedirectResponse|Response
     */
    public function updateCountry(Request $request, $id): RedirectResponse|Response
    {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository(Country::class)->find($id);
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($country);
            $em->flush();
            return $this->redirectToRoute('view_countries');
        }

        return $this->render('country/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

}
