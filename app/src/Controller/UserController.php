<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function utilisateur(DevisRepository $devisRepository): Response
    {
        $users = $this->getUser();

        if (!$users) {
            return $this->redirectToRoute('app_login');
        }
        $devisList = $devisRepository->findBy(['users' => $users]);
        $devisAvecStatut = [];

        foreach ($devisList as $devis) {
            $statuses = $devis->getDevisStatus()->toArray();
            usort($statuses, fn($a,$b) => $b->getDateStatus() <=> $a->getDateStatus());

            $dernierStatus = $statuses[0] ?? null;
            $statutNom = $dernierStatus ? $dernierStatus->getStatus()->getNom() : 'nom défini';

            $devisAvecStatut[] = [
                'devis' => $devis,
                'statut' => $statutNom,
            ];
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'devis' => $devisAvecStatut,
        ]);
    }
}
