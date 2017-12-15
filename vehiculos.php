<?php
// conectando a base de datos
require 'funcs/conexion.php';
require 'funcs/funcs.php';
//aca se almacenaran los errores
$errors = array();

if (!empty($_POST)) {

    $rut = $mysqli->real_escape_string($_POST['rut']);
    $dv = $mysqli->real_escape_string($_POST['dv']);
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $apellido = $mysqli->real_escape_string($_POST['apellido']);
    $telefono = $mysqli->real_escape_string($_POST['telefono']);
    $correo = $mysqli->real_escape_string($_POST['correo']);
    $captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);
    //declaracion de variables
    

        //variable para el captcha
    $secret = '6LdT5DcUAAAAABSzcOxTplQtYrlWrHD_MgQYYP52';


    if (!$captcha) {

        $errors[] = "Por favor revisa el captcha";
    }


    if (isNull($rut, $nombre, $apellido, $telefono, $correo)) {

        $errors[] = "Debe llenar todos los campos";
    }

    
    


    if (rutExiste($rut)) {

        $errors[] = "El Rut  ya esta registrado";
    }

    


    if (count($errors) == 0) {

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");

        $arr = json_decode($response, TRUE);

        if ($arr ['success']) {

           
            $registro = registraConductor($rut, $dv, $nombre, $apellido, $telefono, $correo);
            if ($registro > 0) {
$errors[] = "Error al registrar";
                

                
            } else {
                header("Location: welcome.php");
            }
        } else {

            $errors[] = 'Error al comprobar captcha';
        }
    }
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Futuro Bus</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/estilos.css" rel="stylesheet">
        <script src='https://www.google.com/recaptcha/api.js'></script>

    </head>

    <body>
        

        <div class="container">
            <div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
               <div class="panel panel-info"  >
                    <div class="panel-heading" >
                        <div class="panel-title">Reg&iacute;stro Conductor</div>
                        <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="welcome.php">Volver</a></div>
                    </div>  

                    <div class="panel-body"  >

                        <form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                            <div id="signupalert" style="display:none" class="alert alert-danger">
                                <p>Error:</p>
                                <span></span>
                            </div>

                            <div class="form-group">
                                <label for="rut" class="col-md-3 control-label">Rut:</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="rut" placeholder="Rut" value="<?php if (isset($rut)) echo $rut; ?>" required >
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="dv" placeholder="Dv" value="<?php if (isset($dv)) echo $dv; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nombre" class="col-md-3 control-label">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="nombre" placeholder="Usuario" value="<?php if (isset($nombre)) echo $nombre; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="apellido" class="col-md-3 control-label">Apellido</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="apellido" placeholder="Apellido" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telefono" class="col-md-3 control-label">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="telefono" placeholder="Telefono" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="correo" class="col-md-3 control-label">Correo</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="correo" placeholder="Correo" value="<?php if (isset($correo)) echo $correo; ?>" required>
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
