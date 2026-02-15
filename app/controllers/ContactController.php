<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ContactMessage;
use App\Services\Mailer;

final class ContactController extends Controller
{
    public function show(): void
    {
        $this->view('pages/contact');
    }

    public function send(): void
    {
        $email = trim($_POST['email'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');

        ContactMessage::create($email, $title, $message);
        Mailer::send(app()['config']['mail_to'], 'Nouveau message de contact', "De: {$email}\n{$title}\n{$message}");

        flash('success', 'Votre message a bien été envoyé.');
        redirect('/contact');
    }
}
