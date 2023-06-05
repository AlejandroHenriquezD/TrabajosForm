<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = "smtp.socketlabs.com";                  //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'server41867';                          //SMTP username
    $mail->Password   = 'Sr63ByYt2z9Q8JdLx';                    //SMTP password
    $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('informatica@centraluniformes.com', 'Central Uniformes');
    $mail->addAddress('casimiroherreradavid@gmail.com', 'David User');       //Add a recipient
    $mail->addAddress('javialemanrod@gmail.com', 'Javi user');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Orden de trabajo completada';
    $mail->Body = utf8_decode('
    <p>Su orden de trabajo está disponible en la tienda</p>
    <br />
    <p class="MsoNormal" style="margin-bottom:7.95pt;line-height:11.6pt;background:#fdfdfd"><b><i><u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#c00000">CONFIDENCIALIDAD:</span></u></i></b><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black">&nbsp;</span><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">La información de este correo electrónico es privada, confidencial, y para uso exclusivo del destinatario, tanto el texto como los ficheros anexos. Los mismos contienen información reservada que no puede ser difundida.</span></i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black"><u></u><u></u></span></p>
    <p class="MsoNormal" style="margin-bottom:7.95pt;line-height:11.6pt;background:#fdfdfd"><b><i><u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#c00000">PROTECCIÓN DE DATOS</span></u></i></b><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black">&nbsp;</span><b><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#385623">CENTRAL UNIFORMES, S.L.&nbsp;</span></i></b><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">como responsable del tratamiento le informa que la finalidad del tratamiento es gestionar las comunicaciones realizadas a través del correo electrónico de los servicios prestados, información profesional o de las actividades realizadas por el Responsable. Sus datos se conservarán mientras exista un interés legítimo para ello. La base legitimadora de este tratamiento será el consentimiento del interesado o por la ejecución o desarrollo de un acuerdo. Sus datos no se cederán a terceros, salvo obligación legal o para alcanzar el fin antes expuesto.</span></i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black"><u></u><u></u></span></p>
    <p class="MsoNormal" style="margin-bottom:7.95pt;line-height:11.6pt;background:#fdfdfd"><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">Podrá ejercer sus derechos de acceso, rectificación, supresión, oposición y otros derechos que recoge y desarrolla el Reglamento General de Protección de Datos 679/2016 en la dirección&nbsp;</span></i><b><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#5b5b5b">C/ El Modem, 17- Centro Empresarial de Jinámar - 35220 de Telde, Gran Canaria</span></i></b><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black">&nbsp;</span><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">, o bien en el correo&nbsp;</span></i><u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:blue"><a href="mailto:lopd@centraluniformes.com" target="_blank"><i><span style="color:blue">lopd@centraluniformes.<wbr>com</span></i></a></span></u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black">&nbsp;</span><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">. Si estima que el tratamiento no se ajusta a la normativa vigente podrá presentar una reclamación ante la autoridad nacional de control en&nbsp;</span></i><u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:blue"><a href="http://www.aepd.es/" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://www.aepd.es/&amp;source=gmail&amp;ust=1686042082000000&amp;usg=AOvVaw2LhiW9Q30GAryHl-B61Pb7"><i><span style="color:blue">www.aepd.es.</span></i></a></span></u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black"><u></u><u></u></span></p>
    <p class="MsoNormal" style="margin-bottom:7.95pt;line-height:11.6pt;background:#fdfdfd"><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">Este correo es de uso&nbsp;</span></i><b><i><u><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#1f497d">exclusivamente profesional</span></u></i></b><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black">&nbsp;</span><i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:#9b9b9b">, para su uso laboral y, por tanto, su uso y contenido podrá ser controlable por la entidad responsable del tratamiento. Asimismo, es su responsabilidad comprobar que este mensaje o sus archivos adjuntos no contengan virus informáticos</span></i><span lang="ES-TRAD" style="font-size:10.0pt;font-family:&quot;Open Sans&quot;,sans-serif;color:black"><u></u><u></u></span></p>
    ');
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

echo "
<script type='text/javascript'> 
    window.location = './index.php';
</script>
";

