<?php
require_once ("PHPMailer/clsMail.php");
require_once 'config/init.php';

$mailSend = new clsMail();

if (isset($_POST['submit'])) {
    if (isset($_POST['nrocomprobante']) && isset($_POST['lugar']) &&
        isset($_POST['fecha']) && isset($_POST['destino']) &&
        isset($_POST['total']) && isset($_POST['motivo'])) {
        
        $nrocomprobante = $_POST['nrocomprobante'];
        $lugar = $_POST['lugar'];
        $fecha = $_POST['fecha'];
        $presentado = $_POST['presentado'];
        $destino = $_POST['destino'];
        $total = $_POST['total'];
        $motivo = $_POST['motivo'];
        $correo = $_POST['correo'];
        $comprobante = "Caja chica";
        $nombrecontrol =  'Guillermo Panetta';
         //$subject    = 'Comprobante de Caja';

        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "formularios";
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
        if ($conn->connect_error) {
            die('Error. No se puede conectar con la base de datos.');
        }
        else {
            $Select = "SELECT Nro_comprobante FROM comprobante WHERE Nro_comprobante = ? LIMIT 1";
            $Insert = "INSERT INTO comprobante(Comprobante, Presentado, Destino, Motivo, Lugar, Fecha, Nro_comprobante, Total) values(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($Select);
            $stmt->bind_param("s", $nrocomprobante);
            $stmt->execute();
            $stmt->bind_result($resultEmail);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;
            if ($rnum == 0) {
                $stmt->close();
                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("ssssssss",$comprobante, $presentado, $destino, $motivo, $lugar, $fecha, $nrocomprobante, $total);
                if ($stmt->execute()) {
                    $mensajeGerencia = mensajeGerencia($nombrecontrol, $presentado, $nrocomprobante, $destino, $motivo, $total, $fecha, $lugar, $correo, $comprobante);
                    $mensajePreparo = mensajePreparo($presentado,$comprobante,$nrocomprobante,$destino,$motivo,$total,$lugar,$fecha,$correo);

                    $enviarGerente = $mailSend-> Enviar("Comprobantes |DAXA|",$presentado,"silvaguillermo38@gmail.com","Nueva solicitud de comprobante de caja", $mensajeGerencia);
                    //$enviarPreparo = $mailSend-> Enviar("Comprobantes |DAXA|",$presentado,$correo,"Nueva solicitud de comprobante de caja", $mensajePreparo);

                    if($enviarGerente){
                        header("Location: caja_chica.php?e=success");
                    }else {
                        echo ("Error al enviar el correo");
                    }
                    
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                echo "Nrocomprobante ya existe.";
                header("Location: caja_chica.php?e=error");
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "Todos los campos son requeridos.";
        die();
    }
}
else {
    echo "Botón submit no está colocado";
}
?>