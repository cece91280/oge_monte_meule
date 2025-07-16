<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisTypeForm;
use App\Form\UsersTypeForm;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function utilisateur(Request $request, DevisRepository $devisRepository, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager): Response
    {
        $users = $this->getUser();

        if (!$users) {
            return $this->redirectToRoute('app_login');
        }
        $devisList = $devisRepository->findBy(['users' => $users]);
        $devisAvecStatut = [];

        foreach ($devisList as $devis) {
            $avisUser = null;
            $statuses = $devis->getDevisStatus()->toArray();
            usort($statuses, fn($a,$b) => $b->getDateStatus() <=> $a->getDateStatus());

            $dernierStatus = $statuses[0] ?? null;
            $statutNom = $dernierStatus ? $dernierStatus->getStatus()->getNom() : 'nom défini';

            $avis = $devis->getAvis();
            $avisForm = null;

            if ($statutNom === 'Terminé') {
                $avisUser = $avis->filter(fn($a) => $a->getUsers() === $users)->first();
            }
            if (!$avisUser){
                $nouvelAvis = new Avis();
                $nouvelAvis->setUsers($users);
                $nouvelAvis->setDevis($devis);
                $nouvelAvis->setCreatedAt(new \DateTimeImmutable());

                $form = $formFactory->createNamed(
                    'avis_form_' . $devis->getId(),
                    AvisTypeForm::class,
                    $nouvelAvis
                );
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($nouvelAvis);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre avis bien ajouter');
                    return $this->redirectToRoute('app_profil');
                }
                $avisForm = $form->createView();
            }

            $devisAvecStatut[] = [
                'devis' => $devis,
                'statut' => $statutNom,
                'avis' => $avis,
                'avisUser' => $avisUser,
                'avisForm' => $avisForm,
            ];
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'devis' => $devisAvecStatut,
        ]);
    }

    #[Route('/profil/modifier', name: 'app_edit_profil')]
public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }
    $form =$this->createForm(UsersTypeForm::class , $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'profil mis à jour !');
        return $this->redirectToRoute('app_profil');
    }
    return $this->render('user/edit.html.twig', [
        'form' => $form
    ]);
}
}
