<?php 
require_once ("../PHPMailer/clsMail.php");
$mailSend = new clsMail();

$nrocomprobante = $_POST['nrocomprobante'];
$rechazo = $_POST['controlo'];
$detalles = $_POST['detalles'];
$preparo = $_POST['preparo'];

$lugar = $_POST['lugar'];
$fecha = $_POST['fecha'];

$destino = $_POST['destino'];
$total = $_POST['total'];
$motivo = $_POST['motivo'];
$correo = $_POST['correo'];

$comprobante = "Caja chica";
$nombrecontrol =  'Andrea Caviglia';
$subject    = 'Comprobante de Caja';

$message.=' <tr>
	<td style="background-color: #ecf0f1">
	<div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
	<h2 style="color: #e67e22; margin: 0 0 7px">Estimado/a '.$nombrecontrol.'</h2>
	<p style="margin: 2px; font-size: 15px">
	Una nueva solicitud de '.$subject.' ha sido ingresada a nombre de <b>'.$presentado.'</b>. <br>Por favor ingrese a la página para controlar la solicitud.<br><br>
	<i><b>Resumen del Comprobante:</b></i></p>
	<table style="color: #34495e; font-size: 13px;  margin: 10px">
	<tr>
	<td style="padding:0 30px 0 1px;">N° Comprobante:</td>
	<td>'.$nrocomprobante.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">-</td>
	<td>-</td>
	</tr> 
	<tr>
	<td style="padding:0 30px 0 1px;">Presentado por:</td>
	<td>'.$presentado.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">Destino del viaje:</td>
	<td>'.$destino.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">Motivo:</td>
	<td>'.$motivo.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">Total:</td>
	<td>'.$total.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">-</td>
	<td>-</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">Lugar: '.$lugar.'</td>
	<td>Fecha: '.$fecha.'</td>
	</tr>
	<tr>
	<td style="padding:0 30px 0 1px;">Correo:</td>
	<td>'.$correo.'</td>
	</tr>
	</table>
	<br>
	<br>
	<div style="width: 100%; text-align: center">
	<a style="text-decoration: none; border-radius: 5px; padding: 11px 23px; color: white; background-color: #3498db"
	href="https://daxa.com.ar/">Ir a la página</a>
	</div>
	<p style="color: #b3b3b3; font-size: 12px; text-align: center;margin: 30px 0 0">Daxa Argentina 2022
	</p>
	</div>
	</td>
	</tr>';
$message.='</table>';


$enviado = $mailSend-> Enviar("Comprobantes |DAXA|",$preparo,"guillermo.silva@daxa.com.ar","Nueva solicitud de comprobante de caja", $message);

if($enviado){
	echo "<meta http-equiv='refresh' content='0'>";
}else {
	echo ("Error al enviar el correo");
}

?>