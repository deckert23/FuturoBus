
<?php
// conectando a base de datos
require 'funcs/conexion.php';
require 'funcs/funcs.php';
//aca se almacenaran los errores
$errors = array();

if (!empty($_POST)) {

    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $con_password = $mysqli->real_escape_string($_POST['con_password']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);
    //declaracion de variables
    //usuario activo
    $activo = 0;
    //tipo de usuario por defecto Usuario normal no administrador

    $tipo_usuario = 2;

    //variable para el captcha
    $secret = '6LdT5DcUAAAAABSzcOxTplQtYrlWrHD_MgQYYP52';


    if (!$captcha) {

        $errors[] = "Por favor revisa el captcha";
    }


    if (isNull($nombre, $usuario, $password, $con_password, $email)) {

        $errors[] = "Debe llenar todos los campos";
    }

    if (!isEmail($email)) {

        $errors[] = "Direccion de correo invlida";
    }
    if (!validaPassword($password, $con_password)) {

        $errors[] = "las contraseñas no coinciden";
    }


    if (usuarioExiste($usuario)) {

        $errors[] = "El nombre de usuario $usuario ya esta registrado";
    }

    if (emailExiste($email)) {

        $errors[] = "El correo $email ya existe";
    }


    if (count($errors) == 0) {

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");

        $arr = json_decode($response, TRUE);

        if ($arr ['success']) {

            $pass_hash = hashPassword($password);
            $token = generateToken();
            $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);
            if ($registro > 0) {

                $url = 'http://' . $_SERVER["SERVER_NAME"] .
                        '/FuturoBus/activar.php?id=' . $registro . '&val=' . $token;
                $asunto = 'Activar cuenta - Sistema de usuario';
                $cuerpo = "Estimado $nombre: <br/><br/> Para contunuar con el proceso de registro,"
                        . "es indispensable de click en el siguiente link <a href='$url'> Activar Cuenta</a> ";

                if (enviarEmail($email, $nombre, $asunto, $cuerpo)) {
                    echo "para terminar con el proceso de registro siga las"
                    . "instrucciones que le hemos enviado a la direccion de correo : $email";

                    echo "<br><a href ='index.php'> Iniciar secion </a>";
                    exit;
                } else {

                    $errors[] = "Error al enviar mail";
                }
            } else {
                $errors[] = "Error al registrar";
            }
        } else {

            $errors[] = 'Error al comprobar captcha';
        }
    }
}
?>

<html>
    <head>
        <title>Registro</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" >
        <script src="js/bootstrap.min.js" ></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <style type="text/css">
            body {  
                background:url('img/descarga.png') repeat 0 0;   
                background-size: 100% 100%;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
               <div class="panel panel-primary" style="background:rgba(0,0,0,0.50) " >
                    <div class="panel-heading" style="background:rgba(0,0,0,0.50)">
                        <div class="panel-title">Reg&iacute;strate</div>
                        <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="index.php">Iniciar Sesi&oacute;n</a></div>
                    </div>  

                    <div class="panel-body" style=" color: white" >

                        <form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                            <div id="signupalert" style="display:none" class="alert alert-danger">
                                <p>Error:</p>
                                <span></span>
                            </div>

                            <div class="form-group">
                                <label for="nombre" class="col-md-3 control-label">Nombre:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" required >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="usuario" class="col-md-3 control-label">Usuario</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if (isset($usuario)) echo $usuario; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="captcha" class="col-md-3 control-label"></label>
                                <div class="g-recaptcha col-md-9" data-sitekey="6LdT5DcUAAAAAAe3ec7wP1NPE_VzDLzs00wWAAGA"></div>
                            </div>

                            <div class="form-group">                                      
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i>Registrar</button> 
                                </div>
                            </div>
                        </form>
                        <?php
                        echo resultBlock($errors);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>													