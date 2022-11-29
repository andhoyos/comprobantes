 <?php 
 
// check conexión
// require_once 'config/db_connect.php';
 
// session_start();
 
// check if user ya está logueado
if(isset($_SESSION['user_id'])&&isset($_SESSION['user_rol_id'])) {  

    switch ($_SESSION['user_rol_id']) {
                    case "3":
                        header('location:index_users.php');
                        
                        break; 
                    default:
                        header('location:index.php');
                        break;
                }
    
    
    exit();
}
 
 
if( !empty($_POST) ) {
    $errors = array();
 
    $username = $_POST['username'];
    $password = $_POST['password'];
 
    if( empty($username) == true OR empty($password) == true ) {
        $errors[] = '*Nombre de usuario/contraseña es requerido';
    } 
    else {
        // Si existe el nombre de usuario
        $sql = "SELECT * FROM usuarios WHERE username = '$username'";
        $query = $connect->query($sql);
        if( $query->num_rows > 0 ) {
            // check nombre de usuario y contraseña
            $password = ($password);
 
            $sql = "SELECT * FROM usuarios WHERE username = '$username' AND contraseña = '$password'";
            $query = $connect->query($sql);
            $result = $query->fetch_array();
 
            $connect->close();
 
            if($query->num_rows == 1) {              
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $result['ID'];
                $_SESSION['user_rol_id'] = $result['rol_id'];

             switch ($_SESSION['user_rol_id']) {
                      
                    case "3":
                        header('location:index_users.php');
                        
                        break; 
                    default:
                        header('location:index.php');
                        break;
                }
               
                exit();
            }   
            else {
                $errors[] = ' * La combinación de nombre de usuario/contraseña es incorrecta';
            }
        }   
        else {
            $errors[] = ' * Usuario no existe';
        }
    }
 
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Iniciar Sesión - Daxa Argentina</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Add icon library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<body>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" style="max-width:500px;margin:auto;margin-top: 80px;">
   <div class="imgcontainer">
    <img src="images/glpi.png" alt="daxa">
  </div>
  <?php if(!empty($errors)) {?>
          <div class="error">
              <?php foreach ($errors as $key => $value) {
                  echo $value;
              } ?>
          </div>
      <?php } ?>
  <div class="input-container">
    <i class="fa fa-user icon"></i>
    <input class="input-field" type="text" placeholder="Nombre de usuario" name="username" autocomplete="off">
  </div>
  <div class="input-container">
    <i class="fa fa-key icon"></i>
    <input class="input-field" type="password" placeholder="Contraseña" name="password" autocomplete="off">
  </div>

  <button type="submit" class="btn">Ingresar</button>
</form>

</body>
</html>
