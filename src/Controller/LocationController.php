<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/location', name: 'location.')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(LocationRepository $repository): Response
    {
        $locations = $repository->findAll();
        return $this->render('location/index.html.twig', [
            'locations' => $locations
        ]);
    }

    #[Route('/edit/{id?}', name: 'edit')]
    public function edit(?Location $location, Request $request, ManagerRegistry $doctrine): Response
    {
        if (!$location) {
            $location = new Location();
        }

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Location saved!');

            return $this->redirect($this->generateUrl('location.index'));
        }

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Location $post, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Location was removed');

        return $this->redirect($this->generateUrl('location.index'));
    }
}
