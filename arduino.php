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



///INSERTA CONTROL DE ENTRADA Y SALIDA

global $mysqli;

$sql = "SELECT control  FROM usuarios,id_tarjeta WHERE id_tarjeta = '$nfc'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$num = $stmt->num_rows;
		
		
		if ($num > 0 ){
			$stmt = $mysqli->prepare("INSERT INTO control (control, fecha, hora_ingreso,id_tarjeta) VALUES(?,?,?,?)");
		$stmt->bind_param('issi',0, 2010-05-05, 1201, 3);
                $stmt->execute();
			} else {
			
		}

		$stmt->close();
		
		
		
        


?>
