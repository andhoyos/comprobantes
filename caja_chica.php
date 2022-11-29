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
$query2 = $connect->query($cont1);
$result2 = $query2->fetch_array();

$cont2 = "SELECT COUNT(*) as 'cont_autorizar' FROM comprobante where Controlado NOT LIKE '' AND Rechazado LIKE '' AND Aprobado LIKE ''";
$query3 = $connect->query($cont2);
$result3 = $query3->fetch_array();

$cont3 = "SELECT COUNT(*) as 'Historial' FROM comprobante where Rechazado NOT LIKE '' or Aprobado NOT LIKE ''";
$query4 = $connect->query($cont3);
$result4 = $query4->fetch_array();

$last_comprobante = "SELECT Nro_comprobante FROM `comprobante` ORDER BY ID DESC LIMIT 1";
$query5 = $connect->query($last_comprobante);
$result5 = $query5->fetch_array();
$cant = 1 + $result5['Nro_comprobante'];

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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    html, body,h1,h2,h3,h4,h5 {
      font-family: "Raleway", sans-serif
    }
  </style>
</head>

<body class="w3-light-grey">

  <!-- Top container -->
  <div class="w3-bar w3-top w3-blue w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i
      class="fa fa-bars"></i>  Menu</button>
      <span class="w3-bar-item w3-right"><img src="images/login.png" style="width: 80px"></span>
    </div>

    <!-- Sidebar/menu -->
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
      <div class="w3-container w3-row">
        <div class="w3-col s4">
          <img src="https://img2.freepng.es/20180714/ro/kisspng-computer-icons-user-membership-vector-5b498fc76f2a07.4607730515315475914553.jpg"
          class="w3-circle w3-margin-right" style="width:46px">
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
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black"
        onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Cerrar Menú</a>
        <a href="index.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-home fa-fw"></i>  Inicio</a>
        <a href="caja_chica.php" class="w3-bar-item w3-button w3-padding w3-dark-grey"><i
          class="fa fa-briefcase fa-fw"></i>  Caja chica</a>
          <a href="cierre_caja_chica.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-briefcase fa-fw"></i> 
          Cierre de caja chica</a>
          <a href="liquidacion.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dollar fa-fw"></i> 
          Liquidación de gastos</a>
          <a href="controlar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  Controlar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result2['cont_controlar'] ?></span></a>
          <a href="autorizar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-lock fa-fw"></i>  Autorizar<span class="w3-tag w3-red w3-round w3-right"><?php echo $result3['cont_autorizar'] ?></span></a>
          <a href="historial.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i> 
          Historial<span class="w3-tag w3-blue w3-round w3-right"><?php echo $result4['Historial'] ?></span></a>
          <button class="w3-button w3-block w3-left-align" onclick="myAccFunc()"><i class="fa fa-cog fa-fw"></i> 
            Configuración <i class="fa fa-caret-down"></i>
          </button>
          <div id="demoAcc" class="w3-hide w3-white w3-card">
            <a href="editar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-wrench fa-fw"></i> Editar
            Perfil</a>
            <a href="admin_usuarios.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Administrar
            Usuarios</a>
          </div><br><br>
          <a href="logout.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-close fa-fw"></i>  Cerrar sesión</a><br><br>
        </div>
      </nav>


      <!-- Overlay effect, sidebar en dispositivos pequeños -->
      <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer"
      title="close side menu" id="myOverlay"></div>

      <!-- !Contenido! -->
      <div class="w3-main" style="margin-left:300px;margin-top:43px;">

        <!-- Header -->
        <header class="w3-container" style="padding-top:22px">
          <h5><b><i class="fa fa-briefcase"></i> CAJA CHICA - DAXA ARGENTINA</b></h5>
        </header>
        <br>
        <?php
        if(!empty($_GET)){
          $estado = $_GET['e'];
          if($estado=="success"){
            echo "<div class='alert alert-info alert-dismissible' role='alert' style='margin-left:10px;margin-right: 10px;'>";
            echo " <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b> Petición exitosa.</b>";
            echo " </div>";
          }else if($estado=="error"){
            echo "<div class='alert alert-info alert-dismissible' role='alert' style='margin-left:10px;margin-right: 10px;'>";
            echo " <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><b> Número de comprobante inválido.</b>";
            echo " </div>";
          }
        }else{
          echo "";
        }
        ?>
        <div class="w3-container">
          <form action="insert.php" method="POST"> <!-- config/PHPMailer/smtp.phps -->
            <div class="row">
              <div class="col-4">
                <div class="mb-3">
                  <label class="form-label" for="InputComprobante"><b>
                    N° de Comprobante
                  </b></label>
                  <input class="form-control" name="nrocomprobante" id="InputComprobante" placeholder="Número de Comprobante" type="comprobante" required autocomplete="off" value="<?php echo $cant ?>">
                </input>
              </div>
            </div>
            <div class="col-4">
              <div class="mb-3">
                <label class="form-label" for="InputLugar"><b>
                  Lugar
                </b></label>
                <input class="form-control" name="lugar" id="InputLugar" placeholder="" type="lugar" required autocomplete="off"> 
              </input>
            </div>
          </div>

          <div class="col-4">
            <div class="mb-3 ">
              <label for="date" class="form-label"><b>Fecha</b></label>
              <input class="form-control" name="fecha" type="date" id="date" required autocomplete="off">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">     
            <div class="mb-3">
              <label class="form-label" for="InputPresentado"><b>
               Presentado por
             </b></label>
             <input class="form-control" name="presentado" style="font-weight:bold;" id="InputPresentado" type=" presentado" required autocomplete="off" value="<?php echo $result['Nombre'] . " " . $result['Apellido']  ?>">
           </input>
         </div></div>
         <div class="col-6">
          <div class="mb-3">
            <label class="form-label" for="InputPresentado"><b>
              Correo Electrónico
           </b></label>
           <input class="form-control" name="correo" style="font-weight:bold;" id="InputEmail" type="correo" required autocomplete="off" value="<?php echo $result['Email']?>">
         </input>
       </div></div></div>
       <div class="row">          
        <div class="col-8">
          <div class="mb-3">
            <label class="form-label" for="InputLugar"><b>
              Destino
            </b></label>
            <input class="form-control" name="destino" id="InputLugar" placeholder="Destino del viaje" type="lugar" required>
          </input>
        </div>
      </div>
      <div class="col-4">
        <label class="form-label"><b>
          Total:
        </b></label>
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">$</span>
          <input type="text" name="total" class="form-control" aria-label="Username" aria-describedby="basic-addon1" required autocomplete="off">
        </div>
      </div>
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label"><b>Motivo</b></label>
      <textarea class="form-control" name="motivo" id="exampleFormControlTextarea1" rows="2"
      placeholder="Motivo de la solicitud" required></textarea>
    </div>
    <div class="mb-3">
      <label for="formFile" class="form-label"><b>Adjuntar Documento</b></label>
      <input class="form-control" type="file" id="formFile">
    </div>
    <div class="mb-3">
      <button class="btn btn-primary" type="submit" value="Submit" name="submit" onclick="clicked(event)">
        Enviar
      </button>
      <a class="btn btn-warning" href="index.php" role="button">
        Salir
      </a>
    </div>
  </form>
</div>

<br><br>

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
  function clicked(e)
  {
    if(!confirm('¿Desea confirmar y enviar esta solicitud?')) {
      e.preventDefault();
    }
  }
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