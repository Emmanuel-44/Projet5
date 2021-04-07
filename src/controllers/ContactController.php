<?php
namespace controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ContactController
{
    /**
     * Email sending
     *
     * @return void
     */
    public function emailSend()
    {
        if (!empty($_POST['name']) && !empty($_POST['email']) 
            && !empty($_POST['subject']) && !empty($_POST['message']) 
            && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
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
                $mail->setFrom(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['name']));
                $mail->addAddress('monadresse@gmail.com', 'Emmanuel');      //Add a recipient
                $mail->addReplyTo(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['name']));

                //Content
            $mail->isHTML(true);                                            //Set email format to HTML
                $mail->Subject = htmlspecialchars($_POST['subject']);
                $mail->Body    = '<b>Auteur : </b>' . $_POST['name'] 
                    . '<br /><b>Email : </b>' . htmlspecialchars($_POST['email'])
                    . '<br /><b>Sujet : </b>' . htmlspecialchars($_POST['subject'])
                    . '<br /><b>Message : </b>' . htmlspecialchars($_POST['message']);
                $mail->AltBody = htmlspecialchars($_POST['message']);

                $mail->send();
                echo 'Votre message a bien été envoyé';
            } catch (Exception $e) {
                echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Tous les champs doivent être remplis avec un format email valide !";
        }  
    }
}