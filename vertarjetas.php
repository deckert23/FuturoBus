<?php
session_start();
require 'funcs/conexion.php';
require 'funcs/funcs.php';



//echo $_SESSION["nfc"];
$archivo = fopen("archivo.txt", "w");
fclose($archivo);


?>




<html>
    <head>
        <title>Welcome</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/bootstrap.min.css" >
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" >


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

                    <div id='navbar'  class='navbar-collapse collapse' >
                        <ul  class='nav navbar-nav'>
                            <li ><a href='welcome.php'>Inicio</a></li>			
                        </ul>

                        <?php if ($_SESSION['tipo_usuario'] == 1) { ?>
                            <ul class='nav navbar-nav' >
                                <li ><a  href='#'>Administrar Usuarios</a></li>
                                <li class="dropdown" >
                                    <a style="background:rgba(0,0,0,0.50) " class="dropdown-toggle" data-toggle="dropdown" href="#">Administrar trarjetas
                                        <span style="background:rgba(0,0,0,0.50) " class="caret"></span></a>
                                    <ul class="dropdown-menu" style="background:rgba(0,0,0,0.50) ">
                                        <li style="background: gray"  ><a style="" href="registronfc.php">Agregar Tarjeta</a></li>
                                        <li style="background: gray" ><a href="vertarjetas.php">Ver Tarjetas</a></li>
                                        <li style="background: gray" ><a href="#">Otra</a></li>
                                    </ul>
                                </li>
                            </ul>        
                        <?php } ?>

                        <ul class='nav navbar-nav navbar-right'>
                            <li><a href='logout.php'>Cerrar Sesi&oacute;n</a></li>
                        </ul>
                    </div>
                </div>
            </nav>	

            <div style="background:rgba(0,0,0,0.50); color: white " class="jumbotron">
                <h2> <?php echo 'Bienvenid@ ' . $row['nombre']; ?> </h2>


                <br />
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" ></script>

    </body>
</html>		