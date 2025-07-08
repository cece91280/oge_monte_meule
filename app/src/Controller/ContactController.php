<?php

namespace App\Controller;

use App\Form\ContactTypeForm;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactTypeForm::class );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Préparer le contenu du mail
            $body = "<h3>Nouveau message via le formulaire de contact</h3>";
            $body .= "<ul>";
            $body .= "<li><strong>Nom :</strong> {$data['nom']}</li>";
            $body .= "<li><strong>Prenom :</strong> {$data['prenom']}</li>";
            $body .= "<li><strong>Email :</strong> {$data['email']}</li>";
            $body .= "<li><strong>Catégorie :</strong> {$data['categorie']}</li>";
            $body .= "<li><strong>Message :</strong><br>" . nl2br(htmlspecialchars( $data['message'])) . "</li>";
            $body .= "</ul>";

            //Envoyer l'email
            $email = (new Email())
                ->from($data['email'])
                ->to('contact@ogemonte-meuble.fr')
                ->subject('Contact ' . $data['categorie'])
                ->html($body);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien envoyé');
            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
