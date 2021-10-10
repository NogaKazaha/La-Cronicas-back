<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail() {
        $details = [
            'title' => 'Password Reset Mail',
            'body' => 'Testing'
        ];
        Mail::to('nogakazahawork@gmail.com')->send(new PasswordResetMail($details));
        return "Email sent";
    }
}
