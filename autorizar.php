<?php 
require_once 'config/init.php';
require_once ("PHPMailer/clsMail.php");

$mailSend = new clsMail();
// chequear si el usuario NO está logueado
if(empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}else if ($_SESSION['user_rol_id'] =="3") {
  header('location:index_users.php');   
    
  exit();
}
$user_id = $_SESSION['user_id'];
 
$sql = "SELECT * FROM usuarios WHERE ID = $user_id";
$query = $connect->query($sql);
$result = $query->fetch_array();
 
// Querys para Controlar y Autorizar en Side Menubar
$cont1 = "SELECT COUNT(*) as 'cont_controlar' FROM comprobante where Controlado LIKE '' AND Rechazado LIKE ''";
$cont2 = "SELECT COUNT(*) as 'cont_autorizar' FROM comprobante where Controlado NOT LIKE '' AND Rechazado LIKE '' AND Aprobado LIKE ''";
$query2 = $connect->query($cont1);
$result2 = $query2->fetch_array();
$query3 = $connect->query($cont2);
$result3 = $query3->fetch_array();
$cont3 = "SELECT COUNT(*) as 'Historial' FROM comprobante where Rechazado NOT LIKE '' or Aprobado NOT LIKE ''";
$query4 = $connect->query($cont3);
$result4 = $query4->fetch_array();
// --------------
?>
<?php
if (isset($_POST['boton_autorizar'])) { 
  $nrocomprobante = $_POST['nrocomprobante'];
  $autorizo = $_POST['autorizo'];
  $detalles = $_POST['detalles'];
  $controlo = $_POST['controlo'];
  $fecha = $_POST['fecha'];
  $presentado = $_POST['preparo'];
  $destino = $_POST['destino'];
  $total = $_POST['total'];
  $motivo = $_POST['motivo'];
  $comprobante = $_POST['comprobante'];
  $subject    = 'Comprobante de Caja';

  list($nombre1, $apellido1) = explode(" ", $presentado);
  $sql_correo = "SELECT Email FROM usuarios WHERE Nombre LIKE '%$nombre1%' AND Apellido LIKE '%$apellido1%'";
  $query_sql = $connect->query($sql_correo);
  $result_sql = $query_sql->fetch_array();

  $sql_lugar = "SELECT lugar FROM `comprobante` WHERE Nro_comprobante = $nrocomprobante";
  $query_sql2 = $connect->query($sql_lugar);
  $result_sql2 = $query_sql2->fetch_array();

  $correo = $result_sql['Email'];
  $lugar =  $result_sql2['lugar'];

  $sql1 = "UPDATE comprobante SET Aprobado = '$autorizo', detalles = '$detalles' WHERE Nro_comprobante = '$nrocomprobante'";
  if($query = $connect->query($sql1)){
    echo "exito";
       $message = '<html><head>';
       $message .='
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
              </tr>';
      $message.=' <tr>
                <td style="background-color: #ecf0f1">
                  <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                    <h2 style="color: #e67e22; margin: 0 0 7px">Para: '.$presentado.'</h2>
                    <p style="margin: 2px; font-size: 15px">
                      Su solicitud de '.$subject.' ha sido aprobada por '.$autorizo.'. <br>Ingresar a la página para comprobar los datos.<br><br>
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
                        <td>'.$controlo.'</td>
                      </tr>
                      <tr>
                        <td style="padding:0 30px 0 1px;">Autorizó:</td>
                        <td>'.$autorizo.'</td>
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
                        <td style="padding:0 30px 0 1px;">Lugar: CABA</td>
                        <td>Fecha: '.$fecha.'</td>
                      </tr>
                      <tr>
                        <td style="padding:0 30px 0 1px;">Correo:</td>
                        <td>'.$correo.'</td>
                      </tr>
                      <tr>
                        <td style="padding:0 30px 0 1px;">Detalles:</td>
                        <td>'.$detalles.'</td>
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
            $message.='</table>'; //se envía una notificación a la persona que solicitó
      $enviado = $mailSend-> Enviar("Comprobantes |DAXA|",$presentado,$correo,"Solicitud aprobada", $message);
      if($enviado){
          echo "<meta http-equiv='refresh' content='0'>";
      }else {
          echo ("Error al enviar el correo, por favor contacte con su administrador");
      }
    }else{
      echo $connect->error;
    }

} else if (isset($_POST['boton_rechazar'])) {
  $nrocomprobante = $_POST['nrocomprobante'];
  $rechazo = $_POST['autorizo'];
  $detalles = $_POST['detalles'];
  $controlo = $_POST['controlo'];
  $fecha = $_POST['fecha'];
  $presentado = $_POST['preparo'];
  $destino = $_POST['destino'];
  $total = $_POST['total'];
  $motivo = $_POST['motivo'];
  $comprobante = $_POST['comprobante'];
  $subject    = 'Comprobante de Caja';

  list($nombre1, $apellido1) = explode(" ", $presentado);
  $sql_correo = "SELECT Email FROM usuarios WHERE Nombre LIKE '%$nombre1%' AND Apellido LIKE '%$apellido1%'";
  $query_sql = $connect->query($sql_correo);
  $result_sql = $query_sql->fetch_array();

  $sql_lugar = "SELECT lugar FROM `comprobante` WHERE Nro_comprobante = $nrocomprobante";
  $query_sql2 = $connect->query($sql_lugar);
  $result_sql2 = $query_sql2->fetch_array();

  $correo = $result_sql['Email'];
  $lugar =  $result_sql2['lugar'];

  $sql2 = "UPDATE comprobante SET Rechazado = '$rechazo', detalles = '$detalles' WHERE Nro_comprobante = '$nrocomprobante'";
  if($query = $connect->query($sql2)){
    echo "exito";
         $message = '<html><head>';
         $message .='
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
                </tr>';
        $message.=' <tr>
       <td style="background-color: #ecf0f1">
          <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
            <h2 style="color: #e67e22; margin: 0 0 7px">Para: '.$presentado.'</h2>
           <p style="margin: 2px; font-size: 15px">
             Se le informa que su solicitud de '.$comprobante.' ha sido rechazada por '.$rechazo.'. Ingrese al sistema para solicitar un nuevo pedido o comuníquese con su gerente del sector. <br><br><br>
           <i><b>Motivo y resumen:</b></i></p>
          <table style="color: #34495e; font-size: 13px;  margin: 10px; border: 1px solid">
          <tr>
            <td style="padding:0 30px 0 1px;">Comprobante.N°:</td>
            <td>'.$nrocomprobante.'</td>
          </tr>
          <tr>
            <td style="padding:0 30px 0 1px;">Controló:</td>
            <td>'.$controlo.'</td>
          </tr>
          <tr>
            <td style="padding:0 30px 0 1px;">Rechazó:</td>
            <td>'.$rechazo.'</td>
          </tr>
          <tr>
            <td style="padding:0 30px 0 1px;">Motivo de rechazo:</td>
            <td>'.$detalles.'</td>
          </tr>
          <tr>
            <td style="padding:0 30px 0 1px;">Destino del viaje:</td>
            <td>'.$destino.'</td>
          </tr>
          <tr>
            <td style="padding:0 30px 0 1px;">Motivo del viaje:</td>
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
              $message.='</table>'; //se envía una notificación a la persona que solicitó
        $enviado = $mailSend-> Enviar("Comprobantes |DAXA|",$presentado,$correo,"Rechazo de solicitud DAXA", $message);
          if($enviado){
              echo "<meta http-equiv='refresh' content='0'>";
          }else {
              echo ("Error al enviar el correo, por favor contacte con su administrador");
          }
        }else{
          echo $connect->error;
        }
    }
$connect->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daxa Comprobantes</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Roboto", sans-serif}
    table tr:not(:first-child){
      cursor: pointer;transition: all .25s ease-in-out;
    }
    table tr:not(:first-child):hover{background-color: #ddd;}
  </style>
</head>
<body class="w3-light-grey">

  <!-- Top container -->
  <div class="w3-bar w3-top w3-blue w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
    <span class="w3-bar-item w3-right"><img src="images/login.png" style="width: 80px"></span>
  </div>

  <!-- Sidebar/menu -->
  <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
    <div class="w3-container w3-row">
      <div class="w3-col s4">
        <img src="https://img2.freepng.es/20180714/ro/kisspng-computer-icons-user-membership-vector-5b498fc76f2a07.4607730515315475914553.jpg" class="w3-circle w3-margin-right" style="width:46px">
      </div>
      <div class="w3-col s8 w3-bar">
        <span><strong><?php echo $result['Nombre'] . " " . $result['Apellido'] ?></strong></span><br>
        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
        <a href="editar.php" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
        <a href="#demoAcc" class="w3-bar-item w3-button"onclick="myAccFunc()"><i class="fa fa-cog"></i></a>
      </div>
    </div>
    <hr>
    <div class="w3-container">
      <h5>Panel</h5>
    </div>
    <div class="w3-bar-block">
      <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Cerrar Menú</a>
      <a href="index.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-home fa-fw"></i>  Inicio</a>
      <?php
        $link_caja_chica = '<a href="caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i>  Caja chica</a>';        
        if ($_SESSION['user_rol_id'] == 2) {
          echo $link_caja_chica;
        }       

    ?>
      <!-- <a href="caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i>  Caja chica</a> -->
      <a href="cierre_caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i>  Cierre de caja chica</a>
      <a href="liquidacion.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dollar fa-fw"></i>  Liquidación de gastos</a>
      <a href="controlar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  Controlar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result2['cont_controlar'] ?></span></a>
      <a href="autorizar.php" class="w3-bar-item w3-button w3-padding w3-dark-grey"><i class="fa fa-lock fa-fw"></i>  Autorizar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result3['cont_autorizar'] ?></span></a>
      <a href="historial.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  Historial<span class="w3-tag w3-blue w3-round w3-right"><?php echo $result4['Historial'] ?></span></a>
      <button class="w3-button w3-block w3-left-align" onclick="myAccFunc()"><i class="fa fa-cog fa-fw"></i> 
        Configuración <i class="fa fa-caret-down"></i>
      </button>
      <div id="demoAcc" class="w3-hide w3-white w3-card">
        <a href="editar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-wrench fa-fw"></i> Editar Perfil</a>
        <a href="admin_usuarios.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Administrar Usuarios</a>
      </div><br><br>
      <a href="logout.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-close fa-fw"></i>  Cerrar sesión</a><br><br>
    </div>
  </nav>


  <!-- Efecto overlay -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- ! Contenido ! -->
  <div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Encabezado -->
    <header class="w3-container" style="padding-top:22px">
      <h5><b><i class="fa fa-lock"></i> AUTORIZAR - DAXA ARGENTINA</b></h5><br>
    </header>
    <div class="w3-container">
      <h4><b>Pendientes</b></h4>
      <div class="w3-responsive">
        <table id="table" class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-color">
          <tr class="w3-green">
            <th>Comprobante</th>
            <th>Presentada por</th>
            <th>Destino</th>
            <th>Motivo</th>
            <th>Fecha</th>
            <th>Comprobante N°</th>
            <th>Total</th>
            <th>Controlado por</th>
          </tr>
          <?php
          $conn = new mysqli("localhost", "root", "", "formularios");
          if ($conn->connect_error) {
            die('Could not connect to the database.');
          }

          $Select = "SELECT Comprobante, Presentado, Destino, Motivo, Fecha, Nro_comprobante, Total, Controlado FROM comprobante where Controlado NOT LIKE '' AND Rechazado LIKE '' AND Aprobado LIKE ''";
          $sql = $conn->query($Select);
          if($sql-> num_rows > 0){
            while($row = $sql-> fetch_assoc()){
              echo "<tr><td>".$row["Comprobante"]."</td><td>".$row["Presentado"]."</td><td>".$row["Destino"]."</td><td>".$row["Motivo"]."</td><td>".$row["Fecha"]."</td><td>".$row["Nro_comprobante"]."</td><td>".$row["Total"]."</td><td>".$row["Controlado"]."</td></tr>";
            }
          }else{
            echo "No hay comprobantes pendientes";
          }

          ?>
        </table><br>
      </div><hr>

      <!-- Datos comprobante -->
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="inputcomp"><b>Comprobante</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="comprobante" name="comprobante" required>
          </div>
          <div class="form-group col-md-3">
            <label for="inputprep"><b>Preparó</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="preparo" name="preparo" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputdes"><b>Destino</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="destino" name="destino" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputnro"><b>N° Comprobante</b></label>
            <input type="text" class="form-control" id="nrocomprobante" name="nrocomprobante" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputmot"><b>Motivo</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="motivo" name="motivo" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputcon"><b>Controló</b></label>
            <input type="text" class="form-control" id="controlo" name="controlo" required>
          </div>
          <div class="form-group col-md-3">
            <label for="inputfec"><b>Fecha</b></label>
            <input type="text" class="form-control" id="fecha" name="fecha" requireds>
          </div>
          <div class="form-group col-md-3">
            <label for="inputtot"><b>Total</b></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="text" class="form-control" id="total" name="total" required>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label><b>Autorizó/Rechazó</b></label>
            <input type="text" class="form-control" style="font-weight:bold;" id="autorizo" value="<?php echo $result['Nombre'] . " " . $result['Apellido'] ?>" name="autorizo">
          </div>
          <div class="form-group col-md-9">
            <label><b>Detalles</b></label>
            <input type="text" class="form-control" id="detalles" placeholder="Detalles de la operación / Motivo del rechazo" name="detalles">
          </div>
        </div>

      <!-- Botones -->
      <button type="submit" class="btn btn-primary" name="boton_autorizar" style="margin-left:10px;">Autorizar</button>
      <button type="submit" class="btn btn-danger" name="boton_rechazar" style="margin-left:10px;">Rechazar</button>
      <a class="btn btn-warning" href="index.php" role="button" style="margin-left:10px;">Salir</a>
      <br>
      </form>
    </div><hr>

    <!-- Footer -->
    <footer class="w3-container w3-padding-16 w3-light-grey">
      <div class="w3-bar ">
          <div class="w3-bar-item"> <p>2022 © <a href="http://daxa.com.ar/" target="_blank">Daxa Argentina</a></p></div>
          <div class="w3-bar-item w3-right">Versión 0.1</div>
      </div>
    </footer>

    <!-- Fin de página -->
  </div>

  <script>
// Get sidebar
var mySidebar = document.getElementById("mySidebar");

// Get Div con efecto overlay
var overlayBg = document.getElementById("myOverlay");

// Intercambiar entre mostrar y desactivar barra lateral, con efecto de overlay
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Cerrar sidebar con botón close
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
// Función de Configuración Sidebar
function myAccFunc() {
  var x = document.getElementById("demoAcc");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-dark-grey";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-dark-grey", "");
  }
}
// Click en la tabla
var table = document.getElementById('table');
for(var i = 1; i < table.rows.length; i++)
{
 table.rows[i].onclick = function()
 {
    //rIndex = this.rowIndex;
    document.getElementById("comprobante").value = this.cells[0].innerHTML;
    document.getElementById("preparo").value = this.cells[1].innerHTML;
    document.getElementById("destino").value = this.cells[2].innerHTML;
    document.getElementById("motivo").value = this.cells[3].innerHTML;
    document.getElementById("fecha").value = this.cells[4].innerHTML;
    document.getElementById("nrocomprobante").value = this.cells[5].innerHTML;
    document.getElementById("total").value = this.cells[6].innerHTML;
    document.getElementById("controlo").value = this.cells[7].innerHTML;
  };
}
</script>
</body>
</html>
