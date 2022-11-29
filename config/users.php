<?php  
require_once 'config/db_connect.php';
function userdata($username) {
    global $connect;
    $sql = "SELECT * FROM usuarios WHERE username = '$username'";
    $query = $connect->query($sql);
    $result = $query->fetch_assoc();
    if($query->num_rows == 1) {
        return $result;
    } else {
        return false;
    }
     
    $connect->close();
 
}
function logged_in() {
    if(isset($_SESSION['id'])) {
        return true;
    } else {
        return false;
    }
}
function not_logged_in() {
    if(isset($_SESSION['id']) === FALSE) {
        return true;
    } else {
        return false;
    }
}
 
function getUserDataByUserId($id) {
    global $connect;
 
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $query = $connect->query($sql);
    $result = $query->fetch_assoc();
    return $result;
 
    $connect->close();
}
function users_exists_by_id($id, $username) {
    global $connect;
 
    $sql = "SELECT * FROM usuarios WHERE username = '$username' AND id != $id";
    $query = $connect->query($sql);
    if($query->num_rows >= 1) {
        return true;
    } else {
        return false;
    }
 
    $connect->close();
}
 
function updateInfo($id) {
    global $connect;
 
    $username = $_POST['username'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
 
    $sql = "UPDATE usuarios SET username = '$username', nombre = '$nombre', apellido = '$apellido', email = '$email' WHERE id = $id";
    $query = $connect->query($sql);
    if($query === TRUE) {
        return true;
    } else {
        return false;
    }
}



function passwordMatch($id, $password) {
    global $connect;
 
    $userdata = getUserDataByUserId($id);
 
    $makePassword = makePassword($password, $userdata['salt']);
 
    if($makePassword == $userdata['password']) {
        return true;
    } else {
        return false;
    }
 
    // close connection
    $connect->close();
}
 
function changePassword($id, $password) {
    global $connect;
 
    //$salt = salt(32);
    //$makePassword = makePassword($password, $salt);
 
    //$sql = "UPDATE usuarios SET password = '$makePassword', salt = '$salt' WHERE id = $id";
    $sql = "UPDATE usuarios SET Contraseña = '$password' WHERE ID = $id";
    $query = $connect->query($sql);
 
    if($query === TRUE) {
        return true;
    } else {
        return false;
    }
}
function salt($length) {
    return mcrypt_create_iv($length);
}
 
function makePassword($password, $salt) {
    return hash('sha256', $password.$salt);
}

function mensajeGerencia($nombrecontrol, $presentado, $nrocomprobante, $destino, $motivo, $total, $fecha, $lugar, $correo, $comprobante){
    $message.= '<html><head>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
         <style> table, th, td { border: 1px solid; }</style>
         </head><body>
         <table style="max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;">
                <tr><td style="background-color: #ecf0f1; text-align: left; padding: 0">
                    <a href="https://daxa.com.ar">
                      <img width="20%" style="display:block; margin: 1.5% 3%" src="http://daxa.com.ar/images/logo.png">
                    </a>
                  </td>
                </tr>
                <tr>
                  <td style="background-color: #ecf0f1">
                    <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                      <h2 style="color: #e67e22; margin: 0 0 7px">Estimado '.$nombrecontrol.'</h2>
                      <p style="margin: 2px; font-size: 15px">
                        Una nueva solicitud de '.$comprobante.' ha sido ingresada por <b>'.$presentado.'</b>. <br>Por favor, ingrese a la página para controlar la solicitud.<br><br>
                        <i><b>Resumen del Comprobante:</b></i></p>
                        <table style="color: #34495e; font-size: 13px;  margin: 10px; border: 1px solid">
                        <tr>
                          <td style="padding:0 30px 0 1px;">Comprobante.N°:</td>
                          <td>'.$nrocomprobante.'</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Preparó:</td>
                          <td>'.$presentado.'</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Controló:</td>
                          <td>n/a</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Autorizó:</td>
                          <td>n/a</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Destino:</td>
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
                </tr>
              </table>';
    return $message;
}
function mensajePreparo($presentado,$comprobante,$nrocomprobante,$destino,$motivo,$total,$lugar,$fecha,$correo){
    $message.= '<html><head>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
         <style> table, th, td { border: 1px solid; }</style>
         </head><body>
         <table style="max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;">
                <tr><td style="background-color: #ecf0f1; text-align: left; padding: 0">
                    <a href="https://daxa.com.ar">
                      <img width="20%" style="display:block; margin: 1.5% 3%" src="http://daxa.com.ar/images/logo.png">
                    </a>
                  </td>
                </tr>
                <tr>
                  <td style="background-color: #ecf0f1">
                    <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                      <h2 style="color: #e67e22; margin: 0 0 7px">Estimado '.$presentado.'</h2>
                      <p style="margin: 2px; font-size: 15px">
                        Su solicitud de '.$comprobante.' ha sido procesada con éxito. En caso de ser aprobada se le informará por este medio.<br><br>
                        <i><b>Resumen del Comprobante:</b></i></p>
                        <table style="color: #34495e; font-size: 13px;  margin: 10px; border: 1px solid">
                        <tr>
                          <td style="padding:0 30px 0 1px;">Comprobante.N°:</td>
                          <td>'.$nrocomprobante.'</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Preparó:</td>
                          <td>'.$presentado.'</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Controló:</td>
                          <td>n/a</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Autorizó:</td>
                          <td>n/a</td>
                        </tr>
                        <tr>
                          <td style="padding:0 30px 0 1px;">Destino:</td>
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
                </tr>
              </table>';
    return $message;
}


?>