<?php

session_start();

require 'funcs/conexion.php';
require 'funcs/funcs.php';
if (!empty($_GET)) {

$nfc = $_GET['nfc_id'];


    validaget($nfc);
//echo "$nfc";



    if (nfcExiste($nfc)) {

        echo "valor=" . "1" . ";";

        // $resultado2 = mysql_query("SELECT  usuario  FROM usuario WHERE frid='" . $_GET['nfc_id'] . "'", $conexion);
//$nombre = mysql_result($resultado, 0);
        //   $apellido = mysql_result($resultado2, 0);
        // echo "valor=" . "$apellido" . ";";
    } else {
        echo "valor=" . "0" . ";";

        $fi = fopen("archivo.txt", "w");

        fwrite($fi, $_GET['nfc_id']);
        fclose($fi);


        
    }
} else {

    echo "no existe valor";
}
?>
