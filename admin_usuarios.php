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
// --------------
?>
<?php
if (isset($_POST['boton_actualizar'])) { 
  $id=$_POST['ID'];
  $username = $_POST['username'];
  $nombre = $_POST['Nombre'];
  $apellido = $_POST['Apellido'];
  $email = $_POST['Email'];
  $sector = $_POST['Sector'];
  $rol = $_POST['Rol_ID'];
  $pass = 'cambiar';
  $new= 'vacio';


  $errors = array();
  $success = array();

 if ($id =="") {  
    $sql = "SELECT * FROM usuarios WHERE username = ?";    
    $sql_insert = "INSERT INTO usuarios (ID, username, Nombre, Apellido, Email, Contraseña, sector, salt, rol_id) VALUES (?,?,?,?,?,?,?,?,?)";
     $stmt =$connect->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;
            if ($rnum == 0) {
                $stmt->close();
                $stmt = $connect->prepare($sql_insert);
                $stmt->bind_param("sssssssss",$id, $username, $nombre, $apellido, $email, $pass, $sector, $new, $rol);
                $stmt->execute(); 
    $success[] = 'Usuario insertado con éxito.';
  }else{
    $errors[] = 'Usuario ya existe en la base de datos';
  }
  $stmt->close();
  
 }else{
  $sql = "UPDATE usuarios SET username = '$username', Nombre = '$nombre', Apellido = '$apellido', Email = '$email', sector = '$sector', rol_id = '$rol' WHERE ID = $id";
    $query = $connect->query($sql);
    if($query === TRUE) {
        $success[] = ' Datos actualizados con éxito.';
    }
    else{
      echo $connect->error;
      $errors[] = ' Error al actualizar';
      }
 }

   }  else if (isset($_POST['boton_eliminar'])) {   
      $id_delete = $_POST['ID'];
      $errors = array();
      $success = array();
   
      $sql_delete_user = "DELETE FROM usuarios WHERE ID = $id_delete";
      $query = $connect->query($sql_delete_user);   
      if($query === TRUE) {
        $success[] = 'Se elimino el usuario.';
      }else{
      echo $connect->error;
      $errors[] = ' Error al eliminar usuario';
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
        <a href="usuarios.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Administrar Usuarios</a>
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
      <h5><b><i class="fa fa-lock"></i> ADMINISTRAR USUARIOS- DAXA ARGENTINA</b></h5><br>
    </header>
    <div class="w3-container">
      <h4><b>Usuarios</b></h4>
      <div class="w3-responsive">
        <table id="table" class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-color">
          <tr class="w3-green">
            <th>ID</th>
            <th>username</th>
            <th>Nombre</th>
            <th>Apellido</th>            
            <th>Email</th>
            <th>Sector</th>
            
            <th>Rol_ID</th>
          </tr>
          <?php
          $conn = new mysqli("localhost", "root", "", "formularios");
          if ($conn->connect_error) {
            die('Could not connect to the database.');
          }

          $Select = "SELECT ID, username, Nombre, Apellido, Email, Sector, Rol_id FROM usuarios";
          $sql = $conn->query($Select);
          if($sql-> num_rows > 0){
            while($row = $sql-> fetch_assoc()){
              echo "<tr><td>".$row["ID"]."</td><td>".$row["username"]."</td><td>".$row["Nombre"]."</td><td>".$row["Apellido"]."</td><td>".$row["Email"]."</td><td>".$row["Sector"]."</td><td>".$row["Rol_id"]."</td></tr>";
            }
          }else{
            echo "No hay Usuarios";
          }

          ?>
        </table><br>
      </div><hr>
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
      <!-- Datos comprobante -->
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="inputcomp"><b>ID</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="ID" name="ID">
          </div>
          <div class="form-group col-md-3">
            <label for="inputprep"><b>username</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputdes"><b>Nombre</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="Nombre" name="Nombre" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputnro"><b>Apellido</b></label>
            <input type="text" class="form-control" id="Apellido" name="Apellido" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputmot"><b>Email</b></label>
            <input type="text" style="font-style:italic;" class="form-control" id="Email" name="Email" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputcon"><b>Sector</b></label>
            <input type="text" class="form-control" id="Sector" name="Sector" required>
          </div>
          
          <div class="form-group col-md-3">
            <label for="inputtot"><b>Rol_ID</b></label>
            <input type="text" class="form-control" id="Rol_ID" name="Rol_ID" required>
            <!-- <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              
            </div> -->
          </div>
        </div>         

      <!-- Botones -->
      <button type="submit" class="btn btn-primary" name="boton_actualizar" style="margin-left:10px;">Actualizar</button>
      <button type="submit" class="btn btn-danger" name="boton_eliminar" onclick="clicked(event)" style="margin-left:10px;">Eliminar</button>
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

function clicked(e)
  {
    if(!confirm('¿Desea eliminar el usuario?')) {
      e.preventDefault();
    }
  }
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
    document.getElementById("ID").value = this.cells[0].innerHTML;
    document.getElementById("username").value = this.cells[1].innerHTML;
    document.getElementById("Nombre").value = this.cells[2].innerHTML;
    document.getElementById("Apellido").value = this.cells[3].innerHTML;
    document.getElementById("Email").value = this.cells[4].innerHTML;
    document.getElementById("Sector").value = this.cells[5].innerHTML;
    document.getElementById("Rol_ID").value = this.cells[6].innerHTML;
   
  };
}
</script>
</body>
</html>
