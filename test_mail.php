<?php  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer\Exception.php';
require 'PHPMailer\PHPMailer.php';
require 'PHPMailer\SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->Host = "smtp.googlemail.com";
$mail->From = 'formularios.caja.it@gmail.com';
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Username = "formularios.caja.it@gmail.com";
$mail->Password = "sqvpijfpefyqsskr";
$mail->SMTPSecure = "tls";
$mail->Port = 587;

$mail->AddAddress("and0318@hotmail.com");
$mail->SMTPDebug  = 1;   //Muestra las trazas del mail, 0 para ocultarla
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

/*if ($archivoName != "") {
    $mail->AddAttachment($archivoTemp, $archivoName);
}*/
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}


} catch (Exception $e) {
    echo "No se pudo enviar el mensaje. Mailer Error: {$mail->ErrorInfo}";
}
?>

