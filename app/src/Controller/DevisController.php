<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DevisController extends AbstractController
{
    #[Route('/devis/nouveau', name: 'app_devis_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devis = new Devis();
        $form =$this->createForm(DevisTypeForm::class,$devis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $devis->setUsers($this->getUser());
            $entityManager->persist($devis);
            $entityManager->flush();

            $this->Addflash("success", "Votre devis est bien enregister");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('devis/nouveau.html.twig', [
            'form' => $form
        ]);
    }
}
