<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

/* 
Entrar en: 
http://localhost/centraluniformes/vendor/phpmailer/phpmailer/get_oauth_token.php

Introducir el ClientId y ClientSecret que estan mas abajo

Seguir los pasos de la siguiente página a partir de fetch the token:
https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2
*/
try {
    //Server settings
    $redirectUri = 'http://localhost/phpmailer/get_oauth_token.php';
    $clientId = '370975719595-3ik7ste0j0lvkgfrcmkner9o28kkcjdl.apps.googleusercontent.com';
    $clientSecret = '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';

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = "smtp.gmail.com";                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'javiprueba03@gmail.com';                     //SMTP username
    $mail->Password   = 'rerfds45.';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('javiprueba03@gmail.com', 'Mailer');
    $mail->addAddress('casimiroherreradavid@gmail.com', 'David User');     //Add a recipient
    $mail->addAddress('javialemanrod@gmail.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
