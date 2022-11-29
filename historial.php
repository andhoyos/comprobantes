<?php 

require_once 'config/init.php';

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
    #busqueda {
      background-image: url('images/search.png');
      background-position: 10px 10px;
      background-repeat: no-repeat;
      width: 100%;
      font-size: 16px;
      padding: 9px 1px 1px 40px;
      border: 1px solid #ddd;
      margin-bottom: 12px;
    }table th{
      cursor: pointer;transition: all .25s ease-in-out;
    }
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
        <a href="#demoAcc" class="w3-bar-item w3-button" onclick="myAccFunc()"><i class="fa fa-cog"></i></a>
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
      <a href="autorizar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-lock fa-fw"></i>  Autorizar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result3['cont_autorizar'] ?></span></a>
      <a href="historial.php" class="w3-bar-item w3-button w3-padding w3-dark-grey"><i class="fa fa-history fa-fw"></i>  Historial<span class="w3-tag w3-blue w3-round w3-right"><?php echo $result4['Historial'] ?></span></a>
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


  <!-- Overlay Effect -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- !Contenido de la página! -->
  <div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <!-- Encabezado -->
    <header class="w3-container" style="padding-top:22px">
      <h5><b><i class="fa fa-history"></i> HISTORIAL - DAXA ARGENTINA</b></h5>
    </header>

    <div class="w3-container">
      <h3><b>Historial</b></h3>
      <h4><b>Aprobados</b></h4>
      <!-- <input type="text" id="busqueda" onkeyup="buscar()" placeholder="Búsqueda por nombre"> -->
      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-color" id="tabla1">
        <tr class="w3-green">
          <th onclick="sortTable(0)">Comprobante</th>
          <th onclick="sortTable(1)">Presentado por</th>
          <th onclick="sortTable(2)">Destino</th>
          <th onclick="sortTable(3)">Motivo</th>
          <th onclick="sortTable(4)">Lugar</th>
          <th onclick="sortTable(5)">Fecha</th>
          <th onclick="sortTable(6)">Comprobante N°</th>
          <th onclick="sortTable(7)">Total</th>
          <th onclick="sortTable(8)">Controló</th>
          <th onclick="sortTable(9)">Autorizó</th>
        </tr>
        <?php
        $conn = new mysqli("localhost", "root", "", "formularios");
        if ($conn->connect_error) {
          die('Error al conectar con base de datos.');
        }
        $Select = "SELECT Comprobante, Presentado, Destino, Motivo, Lugar, Fecha, Nro_comprobante, Total, Controlado, Aprobado FROM comprobante where Aprobado NOT LIKE ''";
        $sql = $conn->query($Select);
        if($sql-> num_rows > 0){
          while($row = $sql-> fetch_assoc()){
            echo "<tr><td>".$row["Comprobante"]."</td><td>".$row["Presentado"]."</td><td>".$row["Destino"]."</td><td>".$row["Motivo"]."</td><td>".$row["Lugar"]."</td><td>".$row["Fecha"]."</td><td>".$row["Nro_comprobante"]."</td><td>".$row["Total"]."</td><td>".$row["Controlado"]."</td><td>".$row["Aprobado"]."</td></tr>";
          }
        }else{
          echo "No hay comprobantes pendientes";
        }
        ?>
      </table><br>
      <h4><b>Rechazados</b></h4>
      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-color" id="tabla2">
        <tr class="w3-dark-grey">
          <th onclick="sortTable2(0)">Comprobante</th>
          <th onclick="sortTable2(1)">Presentado por</th>
          <th onclick="sortTable2(2)">Destino</th>
          <th onclick="sortTable2(3)">Motivo</th>
          <th onclick="sortTable2(4)">Lugar</th>
          <th onclick="sortTable2(5)">Fecha</th>
          <th onclick="sortTable2(6)">Comprobante N°</th>
          <th onclick="sortTable2(7)">Total</th>
          <th onclick="sortTable2(8)">Controló</th>
          <th onclick="sortTable2(9)">Rechazó</th>
        </tr>
        <?php
        $conn = new mysqli("localhost", "root", "", "formularios");
        if ($conn->connect_error) {
          die('Error al conectar con base de datos.');
        }
        $Select = "SELECT Comprobante, Presentado, Destino, Motivo, Lugar, Fecha, Nro_comprobante, Total, Controlado, Rechazado FROM comprobante where Rechazado NOT LIKE ''";
        $sql = $conn->query($Select);
        if($sql-> num_rows > 0){
          while($row = $sql-> fetch_assoc()){
            echo "<tr><td>".$row["Comprobante"]."</td><td>".$row["Presentado"]."</td><td>".$row["Destino"]."</td><td>".$row["Motivo"]."</td><td>".$row["Lugar"]."</td><td>".$row["Fecha"]."</td><td>".$row["Nro_comprobante"]."</td><td>".$row["Total"]."</td><td>".$row["Controlado"]."</td><td>".$row["Rechazado"]."</td></tr>";
          }
        }else{
          echo "No hay comprobantes pendientes";
        }
        ?>
      </table><br>
    </div><br>
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
//boton configuracion
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
//ordenar tabla
function sortTable(n, t) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("tabla1");
  switching = true;
  //Setear dirección de ordenado
  dir = "asc"; 
  //loop que continúa hasta el último ajuste
  while (switching) {
    switching = false;
    rows = table.rows;
    //loop por todas las filas excepto la primera
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      /*los elementos que se desea comparar, uno
      de la fila actual y uno de la siguiente*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      //chequear si debe cambiar de lugar
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //si deben 
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }


    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
function sortTable2(n, t) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("tabla2");
  switching = true;
  //Setear dirección de ordenado
  dir = "asc"; 
  //loop que continúa hasta el último ajuste
  while (switching) {
    switching = false;
    rows = table.rows;
    //loop por todas las filas excepto la primera
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      /*los elementos que se desea comparar, uno
      de la fila actual y uno de la siguiente*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      //chequear si debe cambiar de lugar
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //si deben 
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }


    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

</body>
</html>
