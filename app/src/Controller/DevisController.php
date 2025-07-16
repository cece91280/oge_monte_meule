<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\DevisPrestations;
use App\Entity\DevisStatus;
use App\Entity\Status;
use App\Form\DevisTypeForm;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class DevisController extends AbstractController
{
    #[Route('/devis/nouveau', name: 'app_devis_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $devis = new Devis();

        $form =$this->createForm(DevisTypeForm::class,$devis);
        $user = $this->getUser();

        if(!$user){
            throw $this->createAccessDeniedException('Vous devez être connecté pour crée un devis');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $devis->setUsers($this->getUser());
            $dateChoisie = $devis->getDateDemenagement();
            // ---- Vérification de la date ----
            $dejaPris = $entityManager->getRepository(Devis::class)->findOneBy(['date_demenagement' => $dateChoisie]);
            if ($dejaPris) {
                $this->addFlash('error', 'Cette date est déjà réservée, merci d\'en choisir une autre.');
                return $this->redirectToRoute('app_devis_nouveau');
            }
            if ($dateChoisie && $dateChoisie->format('w') == 0) {
                $this->addFlash('error', 'Impossible de réserver un dimanche.');
                return $this->redirectToRoute('app_devis_nouveau');
            }
            // ---- FIN vérification date ----

            $total = 0;

            $prestationsChoisies = $form->get('prestations')->getData();

            foreach ($prestationsChoisies as $prestation) {
                $devisPrestation = new DevisPrestations();
                $devisPrestation->setDevis($devis);
                $devisPrestation->setPrestations($prestation);

                $devisPrestation->setDateDebut(new \DateTimeImmutable());
                $devisPrestation->setDateFin(new \DateTimeImmutable('+1 day'));


                $entityManager->persist($devisPrestation);

                $total += $prestation->getPrixDeBase();

            }
            $devis->setPrixEstime($total);
            $entityManager->persist($devis);
            $entityManager->flush();

            $try = 0;
            do{
                $numero = '';
                for($i=0;$i<10;$i++){
                    $numero .= random_int(0, 9);
                }
                $existing = $entityManager->getRepository(Devis::class)->findOneBy(['numero' => $numero]);
                $try++;
                if($try > 50){

                    throw new \Exception('impossible de générer un numéro de devis unique aprés 50 essaie !');
                }
            }while($existing !== null);
            $devis->setNumero($numero);
            $entityManager->flush();

            // Envoie du mail
            $email = (new Email())
                ->from('contact@ogemonte-meuble.fr')
                ->to($user->getEmail())
                ->subject('Votre devis OgeMonteMeuble')
                ->html($this->renderView('emails/devis_client.html.twig', [
                    'devis' => $devis,
                    'user' => $user,
                ]));
            $mailer->send($email);

            $status =$entityManager->getRepository(Status::class)->findOneBy(['nom'=>'En attente']);
            if ($status) {
                $devisStatus = new DevisStatus();
                $devisStatus->setStatus($status);
                $devisStatus->setDevis($devis);
                $devisStatus->setDateStatus(new \DateTimeImmutable());

                $entityManager->persist($devisStatus);
                $entityManager->flush();
            }

            $this->addflash("success", "Votre devis a est bien été enregister");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('devis/nouveau.html.twig', [
            'form' => $form,
            'devis' => $devis,
        ]);
    }
    #[Route('/api/reserved-days', name: 'api_reserved_days')]
    public function reservedDays(DevisRepository $repo): JsonResponse
    {
        $devis = $repo->findAll();
        $dates = [];

        foreach ($devis as $d) {
            if ($d->getDateDemenagement()) {
                $dates[] = $d->getDateDemenagement()->format('Y-n-j');
            }
        }

        return $this->json($dates);
    }
}
