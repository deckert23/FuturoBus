<?php

session_start();

require 'funcs/conexion.php';
require 'funcs/funcs.php';
if (!empty($_GET)) {
    $nfc = $_GET['nfc_id'];
    validaget($nfc);
    if (nfcExiste($nfc)) {
        echo "valor=" . "1" . ";";
        $fi = fopen("archivo.txt", "w");

        fwrite($fi, $_GET['nfc_id']);
        fclose($fi);
    } else {
        echo "valor=" . "0" . ";";

        $fi = fopen("archivo.txt", "w");

        fwrite($fi, $_GET["nfc_id"]);
        fclose($fi);
    }
} else {

    echo "no existe valor";
}


$nfc2 = $_GET["nfc_id"];
///INSERTA CONTROL DE ENTRADA Y SALIDA

global $mysqli;

$stmt = $mysqli->prepare("SELECT id_tarjeta FROM tarjeta WHERE numero_tarjeta = ? LIMIT 1");
$stmt->bind_param('s', $nfc2);
$stmt->execute();
$stmt->bind_result($_id);
$stmt->store_result();
$stmt->fetch();
$num = $stmt->num_rows;

$date = date("Y/m/d");
$hora = date('H:i:s', time());

echo $_id;


if ($num > 0) {
    
     ///recupero numero id de la tarjeta
    $stmt = $mysqli->prepare("SELECT control FROM control WHERE control=1 AND id_tarjeta = ? LIMIT 1 ");
    $stmt->bind_param('i', $_id);
    $stmt->execute();
    $stmt->bind_result($_control);
    $stmt->store_result();
    $stmt->fetch();

    
     /// inserto datos en base de datos
    if (!$_control) {
        $aux=1;
         $stmt2 = $mysqli->prepare("INSERT INTO control (control,fecha,hora_ingreso,id_tarjeta) VALUES(?,?,?,?)");
    $stmt2->bind_param("issi", $num, $date, $hora, $_id);
    $stmt2->execute();
    }
   
  if ($_control == 2) {


 $aux=1;
         $stmt2 = $mysqli->prepare("INSERT INTO control (control,fecha,hora_ingreso,id_tarjeta) VALUES(?,?,?,?)");
    $stmt2->bind_param("issi", $num, $date, $hora, $_id);
    $stmt2->execute();
    }
   



    if ($_control == 1) {
        $cambio = 2;
        $stmt4 = $mysqli->prepare("UPDATE control SET hora_salida='$hora' , control= $cambio WHERE control=1 AND id_tarjeta = ? LIMIT 1");
        $stmt4->bind_param('s', $_id);
        $stmt4->execute();
    }
}


$stmt->close();
?>
