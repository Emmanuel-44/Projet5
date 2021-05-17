<?php
namespace controllers;

use core\Controller;
use models\PostManager;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ContactController extends Controller
{
    /**
     * Email sending
     *
     * @return void
     */
    public function emailSend()
    {
        if (!empty(filter_input(INPUT_POST, 'name')) && !empty(filter_input(INPUT_POST, 'email')) 
            && !empty(filter_input(INPUT_POST, 'subject')) && !empty(filter_input(INPUT_POST, 'message')) 
            && filter_var(filter_input(INPUT_POST, 'email'), FILTER_VALIDATE_EMAIL)) 
        {
            //Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = '5ebda5f0253edf';                       //SMTP username
                $mail->Password   = 'c195b6d5eb252b';                       //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom(htmlspecialchars(filter_input(INPUT_POST, 'email')), htmlspecialchars(filter_input(INPUT_POST, 'name')));
                $mail->addAddress('monadresse@gmail.com', 'Emmanuel');      //Add a recipient
                $mail->addReplyTo(htmlspecialchars(filter_input(INPUT_POST, 'email')), htmlspecialchars(filter_input(INPUT_POST, 'name')));

                //Content
                $mail->isHTML(true);                                            //Set email format to HTML
                $mail->Subject = htmlspecialchars(filter_input(INPUT_POST, 'subject'));
                $mail->Body    = '<b>Auteur : </b>' . filter_input(INPUT_POST, 'name') 
                    . '<br /><b>Email : </b>' . htmlspecialchars(filter_input(INPUT_POST, 'email'))
                    . '<br /><b>Sujet : </b>' . htmlspecialchars(filter_input(INPUT_POST, 'subject'))
                    . '<br /><b>Message : </b>' . htmlspecialchars(filter_input(INPUT_POST, 'message'));
                $mail->AltBody = htmlspecialchars(filter_input(INPUT_POST, 'message'));

                $mail->send();
                echo 'Votre message a bien été envoyé.';
            } catch (Exception $e) {
                return false;
            }
        }
    }
}