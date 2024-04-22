<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoritesController extends AbstractController
{
    #[Route('/favorites', name: 'favorites')]
    public function viewFavorites(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $favorites = $entityManager->getRepository(Favorites::class)->findBy(['user' => $user]);

        return $this->render('user/favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/favorites/add/{id}', name: 'add_to_favorites')]
    public function addToFavorites(Car $car, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $existingFavorite = $entityManager->getRepository(Favorites::class)->findOneBy([
            'user' => $user,
            'car' => $car,
        ]);

        if (!$existingFavorite) {
            $favorite = new Favorites();
            $favorite->setUser($user);
            $favorite->setCar($car);

            $entityManager->persist($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favorites');
    }

    #[Route('/favorites/remove/{id}', name: 'remove_from_favorites')]
    public function removeFromFavorites(Favorites $favorite, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === $favorite->getUser()) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favorites');
    }
}