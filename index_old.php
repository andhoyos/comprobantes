<?php 
 
require_once 'config/init.php';
 
// chequear si el usuario NO está logueado
if(empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
 
if(isset($_SESSION['user_id'])) { ?>
 
 
<?php 
$user_id = $_SESSION['user_id'];
 
$sql = "SELECT * FROM usuarios WHERE ID = $user_id";
$query = $connect->query($sql);
$result = $query->fetch_array();

// Querys para Controlar y Autorizar en Side Menubar
$cont1 = "SELECT COUNT(*) as 'cont_controlar' FROM comprobante where Controlado LIKE '' AND Rechazado LIKE ''";
$cont2 = "SELECT COUNT(*) as 'cont_autorizar' FROM comprobante where Controlado NOT LIKE '' AND Rechazado LIKE '' AND Aprobado LIKE ''";
//cont historial
$cont3 = "SELECT COUNT(*) as 'Historial' FROM comprobante where Rechazado NOT LIKE '' or Aprobado NOT LIKE ''";
$query2 = $connect->query($cont1);
$result2 = $query2->fetch_array();
$query3 = $connect->query($cont2);
$result3 = $query3->fetch_array();
$query4 = $connect->query($cont3);
$result4 = $query4->fetch_array();
// --------------
 
// cerrar conexión con base de datos
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
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
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
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Panel</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Cerrar Menú</a>
    <a href="index.php" class="w3-bar-item w3-button w3-padding w3-dark-grey"><i class="fa fa-home fa-fw"></i>  Inicio</a>
    <a href="caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i>  Caja chica</a>
    <a href="cierre_caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i>  Cierre de caja chica</a>
    <a href="liquidacion.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dollar fa-fw"></i>  Liquidación de gastos</a>
    <a href="controlar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  Controlar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result2['cont_controlar'] ?></span></a>
    <a href="autorizar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-lock fa-fw"></i>  Autorizar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result3['cont_autorizar'] ?></span></a>
    <a href="historial.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  Historial<span class="w3-tag w3-blue w3-round w3-right"><?php echo $result4['Historial'] ?></span></a>
    <button class="w3-button w3-block w3-left-align" onclick="myAccFunc()"><i class="fa fa-cog fa-fw"></i> 
    Configuración <i class="fa fa-caret-down"></i>
    </button>
    <div id="demoAcc" class="w3-hide w3-white w3-card">
          <a href="editar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-wrench fa-fw"></i> Editar Perfil</a>
          <a href="usuarios.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Administrar Usuarios</a>
    </div>
    <br><br>
      <a href="logout.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-close fa-fw"></i>  Cerrar sesión</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-home"></i> INICIO - DAXA ARGENTINA</b></h5>
  </header>

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-twothird">
        <h4>Resumen</h4>
        <table class="w3-table w3-striped w3-white">
          <tr>
            <td><i class="fa fa-bell w3-text-red w3-large"></i></td>
            <td>Por confirmar.</td>
            <td><i><?php echo $result3['cont_autorizar'] ?></i></td>
          </tr>
          <tr>
            <td><i class="fa fa-users w3-text-yellow w3-large"></i></td>
            <td>Activos.</td>
            <td><i><?php echo $result3['cont_autorizar'] + $result2['cont_controlar'] ?></i></td>
          </tr>
          <tr>
            <td><i class="fa fa-bookmark w3-text-blue w3-large"></i></td>
            <td>Historial.</td>
            <td><i><?php echo $result4['Historial'] ?></i></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <hr>
  <hr>

  <div class="w3-container">
    <h4>Historial reciente</h4>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <?php
        $conn = new mysqli("localhost", "root", "", "formularios");
        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }

        $Select = "SELECT Comprobante, Presentado, Total, Destino, Nro_comprobante FROM comprobante order by ID desc limit 3";
        $result = $conn->query($Select);
        if($result-> num_rows > 0){
          while($row = $result-> fetch_assoc()){
            echo "<tr><td>".$row["Comprobante"]."</td><td>".$row["Presentado"]."</td><td>".$row["Total"]."</td><td>".$row["Destino"]."</td><td>".$row["Nro_comprobante"]."</td><td>";
          }
        }else{
          echo "0 results";
        }
        $conn->close();

      ?>
    </table><br>
    <a class="w3-button w3-dark-grey" href="historial.php" role="button">Más  <i class="fa fa-arrow-right"></i></a>
  </div>
  <hr>
  <hr>

  <div class="w3-container">
    <h4>Comprobantes Recientes</h4>
        <?php
        $conn = new mysqli("localhost", "root", "", "formularios");
        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }

        $Select = "SELECT Presentado, Fecha, Motivo FROM comprobante order by ID desc limit 3";
        $result = $conn->query($Select);
        if($result-> num_rows > 0){
          while($row = $result-> fetch_assoc()){
            echo "<div class='w3-row'>
              <div class='w3-col m2 text-center'>
                <img class='w3-circle' src='images/login.png' style='width:60px;height:60px'>
              </div>
              <div class='w3-col m10 w3-container'>
                <h4>".$row["Presentado"]. "<span class='w3-opacity w3-medium'>"." ".$row["Fecha"]."</span></h4>
                <p>".$row["Motivo"]."</p><br>
              </div>
            </div>";
          }
        }else{
          echo "0 results";
        }
        $conn->close();

      ?>

  </div>
  <br>

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <div class="w3-bar ">
        <div class="w3-bar-item"> <p>2022 © <a href="http://daxa.com.ar/" target="_blank">Daxa Argentina</a></p></div>
        <div class="w3-bar-item w3-right">Versión 0.1</div>
    </div>
  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
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
</script>

</body>
</html>
<?php
}
?>