<?php 

require_once 'config/init.php'; 


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


$userdata = getUserDataByUserId($_SESSION['user_id']);
if($_POST) {
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass1 = $_POST['pass1'];

  if($nombre == "") {
    echo " * Nombre requerido <br />";
  }

  if($apellido == "") {
    echo " * Apellido requerido <br />";
  }

  if($username == "") {
    echo " * Username requerido <br />";
  }

  if($email == "") {
    echo " * Email requerido <br />";
  }

  $errors = array();
  $success = array();
  if($nombre && $apellido && $username && $email) {
    $user_exists = users_exists_by_id($_SESSION['user_id'], $username);
    if($user_exists === TRUE) {
      $errors[] = ' Nombre de usuario existente';
    } else {
      if(updateInfo($_SESSION['user_id']) === TRUE) {
        $success[] = ' Datos editados con éxito.';
      } else {
        $errors[] = ' Error al editar';
      }
    }
  }

  if($pass && $pass1){
    if($pass == $pass1){
      if(changePassword($_SESSION['user_id'], $pass) === TRUE) {
        $success[] = ' Contraseña editada con éxito';
      } else {
        $errors[] = ' Error al editar contraseña';
      }
    }else{
      $errors[] = ' Error al editar contraseña. Las contraseñas no coinciden';
    }

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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="assets/stylecontraseña.css">
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
        <img src="https://img2.freepng.es/20180714/ro/kisspng-computer-icons-user-membership-vector-5b498fc76f2a07.4607730515315475914553.jpg" class="w3-circle w3-margin-right" class="w3-circle w3-margin-right" style="width:46px">
      </div>
      <div class="w3-col s8 w3-bar">
        <span><strong><?php echo $result['Nombre'] . " " . $result['Apellido'] ?></strong></span><br>
        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
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
      <a href="historial.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  Historial</a>
      <button class="w3-button w3-block w3-left-align w3-dark-grey" onclick="myAccFunc()"><i class="fa fa-cog fa-fw"></i> 
        Configuración <i class="fa fa-caret-down"></i>
      </button>
      <div id="demoAcc" class="w3-hide w3-white w3-card">
        <a href="editar.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-wrench fa-fw"></i> Editar Perfil</a>
        <a href="admin_usuarios.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Administrar Usuarios</a>
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
      <h5><b><i class="fa fa-cog"></i> EDITAR PERFIL - DAXA ARGENTINA</b></h5>
    </header>

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
      <div class="form-group" style="margin-left:10px;margin-right: 10px;">
        <label for="inputName"><b>Nombre</b></label>
        <input type="nombre" class="form-control" id="nombre" name="nombre" value="<?php if($_POST) {
          echo $_POST['nombre'];
          } else {
            echo $userdata['Nombre'];
          } ?>">
        </div>
        <div class="form-group" style="margin-left:10px;margin-right: 10px;">
          <label for="inputLastname"><b>Apellido</b></label>
          <input type="apellido" class="form-control" id="userLastname" aria-describedby="emailHelp" name="apellido" value="<?php if($_POST) {
            echo $_POST['apellido'];
            } else {
              echo $userdata['Apellido'];
            } ?>">
          </div>
          <div class="form-group" style="margin-left:10px;margin-right: 10px;">
            <label for="inputLastname"><b>Nombre de Usuario</b></label>
            <input type="username" class="form-control" id="userLastname" aria-describedby="emailHelp" name="username" value="<?php if($_POST) {
              echo $_POST['username'];
              } else {
                echo $userdata['username'];
              } ?>">
            </div>
            <div class="form-group" style="margin-left:10px;margin-right: 10px;">
              <label for="inputMail"><b>Correo electrónico</b></label>
              <input type="email" class="form-control" id="userEmail" aria-describedby="emailHelp" name="email" value="<?php if($_POST) {
                echo $_POST['email'];
                } else {
                  echo $userdata['Email'];
                } ?>">
              </div>
              <div class="form-group">
               <div class="alert alert-info alert-dismissible" role="alert" style="margin-left:10px;margin-right: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>Deje el Campo de Contraseña vacío si no desea cambiarla.</b>
              </div>
            </div>
            <div class="form-group" style="margin-left:10px;margin-right: 10px;">
              <label for="inputPass"><b>Contraseña</b></label>
              <input type="password" class="form-control" id="userPass" name="pass" autocomplete="off">
            </div>
            <div class="form-group" style="margin-left:10px;margin-right: 10px;">
              <label for="inputPass1"><b>Confirmar Contraseña</b></label>
              <input type="password" class="form-control" id="userPass1" name="pass1" autocomplete="off">
            </div>
            <?php if(!empty($success)) {?>
                    <div class="success" style="margin-left:10px;margin-right: 10px;">
                        <?php foreach ($success as $key => $value) {
                            echo $value;
                        } ?>
                    </div>
                <?php } ?>
            <?php if(!empty($errors)) {?>
                    <div class="error" style="margin-left:10px;margin-right: 10px;">
                        <?php foreach ($errors as $key => $value) {
                            echo $value;
                        } ?>
                    </div>
                <?php } ?>
            <br>
            <button type="submit" class="btn btn-primary" style="margin-left:10px;">Guardar cambios</button> 
            <a class="btn btn-warning" href="index.php" role="button">Salir</a>
          </form>


          <hr>
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
