<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Devis;
use App\Form\AvisTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AvisController extends AbstractController
{
    #[Route('/avis/{devis}', name: 'app_avis_add')]
    public function addAvis(Request $request, Devis $devis, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        // Vérifier que ce devis appartient bien à l'utilisateur connecté
        if ($devis->getUsers() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $avis = new Avis();
        $form = $this->createForm(AvisTypeForm::class , $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setUsers($user);
            $avis->setDevis($devis);
            $avis->setCreatedAt(new \DateTimeImmutable());
            $avis->setIsApproved(false);

            $entityManager->persist($avis);
            $entityManager->flush();

            $this->addFlash('success', 'Merci pour votre avis');
            return $this->redirectToRoute('app_home');

        }
        return $this->render('avis/index.html.twig', [
            'form' => $form,
            'devis' => $devis,
        ]);
    }
}
