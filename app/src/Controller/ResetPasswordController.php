<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ChangePasswordForm;
use App\Form\ResetPasswordRequestForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    // Constructeur : injection du helper de reset password et de l'entityManager
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Affiche et traite le formulaire de demande de réinitialisation de mot de passe
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        // Création du formulaire de demande de reset
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $email = $form->get('email')->getData();

            // Appelle la méthode pour envoyer le mail de reset
            return $this->processSendingPasswordResetEmail($email, $mailer, $translator);
        }

        // Affiche la page du formulaire
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    /**
     * Page de confirmation après avoir soumis la demande de réinitialisation
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Si pas de token, on en génère un faux pour ne pas indiquer si l'email existe ou non
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        // Affiche la page d'instruction à l'utilisateur
        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validation et traitement du lien de reset reçu par email (avec token)
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, ?string $token = null): Response
    {
        if ($token) {
            // Stocke le token en session et retire-le de l’URL pour des raisons de sécurité
            $this->storeTokenInSession($token);

            // Redirige vers la page de reset sans le token dans l’URL
            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();

        if (null === $token) {
            throw $this->createNotFoundException('Aucun jeton de réinitialisation trouvé dans l’URL ou la session.');
        }

        try {
            /** @var Users $user */
            // Vérifie que le token est valide et récupère l'utilisateur correspondant
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            // En cas d’erreur, affiche un message et redirige vers la demande de reset
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Si token valide, affiche le formulaire de changement de mot de passe
        $form = $this->createForm(ChangePasswordForm::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Le token ne doit servir qu'une fois : on le retire
            $this->resetPasswordHelper->removeResetRequest($token);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hash le nouveau mot de passe et l’enregistre pour l’utilisateur
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $this->entityManager->flush();

            // Nettoie la session après le changement de mot de passe
            $this->cleanSessionAfterReset();

            // Redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // Affiche la page du formulaire pour saisir le nouveau mot de passe
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }

    /**
     * Gère l’envoi de l’email de réinitialisation de mot de passe
     */
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        // Recherche l'utilisateur avec l'adresse email renseignée
        $user = $this->entityManager->getRepository(Users::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Ne jamais révéler si un utilisateur existe ou non
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            // Génère un token de reset
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // En cas de problème (ex : trop de demandes), redirige sans révéler la raison
            return $this->redirectToRoute('app_check_email');
        }

        // Prépare l’email de réinitialisation à envoyer à l’utilisateur
        $email = (new TemplatedEmail())
            ->from(new Address('contact@ogemonte-meuble.fr', 'OgeMonteMeuble')) // L'adresse d'expéditeur
            ->to((string) $user->getEmail())                                   // Destinataire = l'utilisateur
            ->subject('Votre demande de réinitialisation de mot de passe')     // Sujet du mail
            ->htmlTemplate('reset_password/email.html.twig')                   // Template du mail
            ->context([
                'resetToken' => $resetToken,                                  // On transmet le token au template
            ])
        ;

        // Envoie l’email de reset
        $mailer->send($email);

        // Stocke le token en session pour l'utiliser sur la page de confirmation
        $this->setTokenObjectInSession($resetToken);

        // Redirige vers la page "check email" (instructions à l'utilisateur)
        return $this->redirectToRoute('app_check_email');
    }
}