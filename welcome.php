<?php
session_start();
require 'funcs/conexion.php';
require 'funcs/funcs.php';



    //echo $_SESSION["nfc"];



if (!isset($_SESSION["id_usuario"])) {
    header("Location: index.php");
}
$idUsuario = $_SESSION["id_usuario"];
$sql = "SELECT id , nombre FROM usuarios WHERE id = 'id_usuario' ";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>

<html>
    <head>
        <title>Welcome</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" >
        <script src="js/bootstrap.min.js" ></script>

        <style>
            body {
                padding-top: 20px;
                padding-bottom: 20px;
            }
        </style>
        <style type="text/css">
body {  
        background:url('img/descarga.png') repeat 0 0;   
        background-size: 100% 100%;
        background-attachment: fixed;
 }
</style>
    </head>

    <body>
        
        
        <div class="container">

            <nav class='navbar navbar-default' style="background:rgba(0,0,0,0.50) ">
                <div class='container-fluid' style="background:rgba(0,0,0,0.50) ">
                    <div class='navbar-header' style="background:rgba(0,0,0,0.50) ">
                        <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
                            <span class='sr-only'>Men&uacute;</span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                        </button>
                    </div>

                    <div id='navbar' class='navbar-collapse collapse' >
                        <ul  class='nav navbar-nav'>
                            <li ><a href='welcome.php'>Inicio</a></li>			
                        </ul>

                        <?php if ($_SESSION['tipo_usuario'] == 1) { ?>
                            <ul class='nav navbar-nav'>
                                <li><a href='#'>Administrar Usuarios</a></li>
                                <li><a href='registronfc.php'>Agregar Tarjeta</a></li>
                            </ul>
                        
                        <?php } ?>

                        <ul class='nav navbar-nav navbar-right'>
                            <li><a href='logout.php'>Cerrar Sesi&oacute;n</a></li>
                        </ul>
                    </div>
                </div>
            </nav>	

            <div style="background:rgba(0,0,0,0.50); color: white " class="jumbotron">
                <h2> <?php echo 'Bienvenid@ ' . utf8_decode($row["nombre"]); ?> </h2>
                <br />
            </div>
        </div>
    </body>
</html>		