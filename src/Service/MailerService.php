<?php

namespace App\Service;

use App\Entity\Activite;
use App\Entity\Candidature;
use App\Entity\Offre;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use App\Security\EmailVerifier;

class MailerService
{

	private string $email;
	private string $appName;

	public function __construct(private MailerInterface $mailer, private EmailVerifier $emailVerifier)
	{
		$this->email = $_ENV['SITE_EMAIL'];
		$this->appName = $_ENV['APP_NAME'];
	}

	public function sendCodeToConfirmEmail(User $user)
	{
		$this->emailVerifier->sendEmailConfirmation(
			'app_verify_email',
			$user,
			(new TemplatedEmail())
				->from(new Address($this->email, $this->appName))
				->to($user->getEmail())
				->subject('Veuillez confirmer votre email')
				->htmlTemplate('mails/confirmation_email.html.twig')
		);
	}

	public function sendReinitialisationPasswordCode($to, $code)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($to)
			->subject("Code de réinitialisation du mot de passe")
			->htmlTemplate('mails/_code_reinitialisation.html.twig')
			->context([
				'code' => $code,
			]);

		return $this->mailer->send($email);
	}

	public function sendStatutCompteChanged($to, $lastStatut, $newStatut, Activite $activite)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($to)
			->subject("Changement de statut de votre compte")
			->htmlTemplate('mails/_compte_change.html.twig')
			->context([
				'lastStatut' => $lastStatut,
				'newStatut' => $newStatut,
				'activite' => $activite,
			]);

		return $this->mailer->send($email);
	}

	public function sendCodeToConfirmEmailCode($to, $code)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($to)
			->subject("Code de confirmation de l'adresse email")
			->htmlTemplate('mails/_code_comfirm_email.html.twig')
			->context([
				'code' => $code,
			]);

		return $this->mailer->send($email);
	}

	public function sendContact($nom, $useremail, $sujet, $message)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($this->email)
			->subject("Message de contact depuis " . $this->appName)
			->htmlTemplate('mails/_contact.html.twig')
			->context([
				'nom' => $nom,
				'useremail' => $useremail,
				'sujet' => $sujet,
				'message' => $message,
			]);

		return $this->mailer->send($email);
	}

	public function signaler($subjet, $message, $auteur)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($this->email)
			->subject($subjet)
			->htmlTemplate('mails/_signaler.html.twig')
			->context([
				'abus' => $subjet,
				'message' => $message,
				'auteur' => $auteur,
			]);

		return $this->mailer->send($email);
	}

	public function sendCandidatEmail(Candidature $candidature)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($candidature->getUser()->getEmail())
			->subject("Confirmation de soumission de candidature pour " . $candidature->getOffre()->getIntitulePoste())
			->htmlTemplate('mails/_candidat.html.twig')
			->context([
				'candidature' => $candidature,
			]);

		return $this->mailer->send($email);
	}

	public function sendCandidatureEmployerEmail(Candidature $candidature)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($candidature->getOffre()->getUser()->getEmail())
			->subject("Nouvelle candidature pour " . $candidature->getOffre()->getIntitulePoste())
			->htmlTemplate('mails/_employer.html.twig')
			->context([
				'candidature' => $candidature,
			]);

		return $this->mailer->send($email);
	}

	public function sendContactUserMail(string $sendername, string $senderemail, string $contenu, User $destinataire)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($destinataire->getEmail())
			->subject("Message de contact depuis " . $this->appName)
			->htmlTemplate('mails/_contact_user.html.twig')
			->context([
				'sujet' => "Message de contact depuis " . $this->appName,
				'sendername' => $sendername,
				'senderemail' => $senderemail,
				'contenu' => $contenu,
				'destinataire' => $destinataire,
			]);

		return $this->mailer->send($email);
	}

	public function sendCandidatEmailCallBack(Candidature $candidature, string $message)
	{

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($candidature->getOffre()->getUser()->getEmail())
			->subject("Notification concernant votre candidature pour le poste de " . $candidature->getOffre()->getIntitulePoste())
			->htmlTemplate('mails/_candidat_callback.html.twig')
			->context([
				'candidature' => $candidature,
				'message' => $message,
			]);
		return $this->mailer->send($email);
	}

	public function sendAlertEmail(Offre $offre, User $user)
	{
		$intitulePoste = $offre->getIntitulePoste();

		$email = (new TemplatedEmail())
			->from(new Address($this->email, $this->appName))
			->to($user->getEmail())
			->subject("Découvrez une nouvelle offre d‘emploi pour \" $intitulePoste \"")
			->htmlTemplate('mails/_alerte.html.twig')
			->context([
				'offre' => $offre,
			]);

		return $this->mailer->send($email);
	}
}
