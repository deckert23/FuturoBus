<?php
session_start();
require 'funcs/conexion.php';
require 'funcs/funcs.php';
$errors = array();
if (!isset($_SESSION["id_usuario"]) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: index.php");
}



$prueba = fopen("archivo.txt", "r") or die("Error al leer");


$nombre = fgets($prueba);

if ($nombre == "") {

    $errors[] = "Error al leer Numero Tarjeta / Acercar tarjeta al Lector";
    $errors[] = '<a href="registronfc.php" title="Continuar"><button class="btn btn-warning">Obtener Numero</button></a>';
}

if (nfcExiste($nombre)) {

    $errors[] = "tarjeta registrada / Intente con otra";
    
    $errors[] = '<a href="registronfc.php" title="Continuar"><button class="btn btn-warning">Obtener Numero</button></a>';
    
   
}

if (!empty($_POST) && $nombre != "" && nfcExiste($nombre)!= TRUE) {

    $numero_tarjeta = $mysqli->real_escape_string($nombre);
    $estado_nfc = 0;


    $registro = registraNfc($numero_tarjeta, $estado_nfc);

    if ($registro == 0) {

        $errors[] = "error al registrar Tarjeta";
    } else {
        ?>
        <script>
            alert('Tarjeta registrada');
            window.location.href = 'welcome.php';
        </script>
        <?php
    }
}




?>




        <title>Furturo Bus</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" >
        <script src="js/bootstrap.min.js" ></script>
        <style type="text/css">
            body {  
                background:url('img/descarga.png') repeat 0 0;   
                background-size: 100% 100%;
                background-attachment: fixed;
            }
        </style>
    </head>

    <body>

        <div class="container" >   



            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
                <div class="panel panel-primary" style="background:rgba(0,0,0,0.50) " >
                    <div class="panel-heading" style="background:rgba(0,0,0,0.50)">

                        <div class="panel-title">Registro Tarjeta</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="welcome.php">¿Volver a pagina de Inicio?</a></div>
                    </div>     


                    <div style="padding-top:50px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                        <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

                            <div style="margin-bottom: 20px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                <input disabled type="text" class="form-control" name="numeronfc" value="<?php echo "$nombre"; ?>" required>                                        
                                <input  type="hidden" class="form-control" name="ver" value="1" > 
                            </div>



                            <div style="margin-top:10px" class="form-group">
                                <div class="col-sm-12 controls">
                                    <button id="btn-signup" style="margin-top: 20px"  type="submit" class="btn btn-primary">Registrar</button>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="color: white ;border-top: 1px solid#888; padding-top:15px; font-size:85%;margin-top: 20px" >

                                    </div>
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