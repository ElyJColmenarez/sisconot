<?php
try{

	require_once '../../../base/clases/basededatos/Conexion.php';

	require_once 'InstitutoServicio.php';
	Conexion::iniciar("localhost","bd_scnfinal","5432","postgres","5455");
	echo "<pre>";
	
	echo "----------lista de institutos -----------------------\n";
	
	$ins = InstitutoServicio::listarInstitutos();

	 var_dump($ins);

	
	echo "----------------obtener por codigo un instituto-------------------\n";
	
	// $ins2 = InstitutoServicio::obtener(3);

	//	var_dump($ins2);

	echo "----------------Agregar un instituto-------------------\n";


	//$a   = InstitutoServicio::agregarInstituto("iut federico palacio","iut3", "km8");
	//echo "Código del instituto agregado:   $a\n";
	

	echo "----------------Eliminar un instituto-------------------\n";

	// InstitutoServicio::eliminar(10);

	echo "----------------Modificar un instituto-------------------\n";


//	 InstitutoServicio::modificarInstituto(10,"iut federico 3","iut3 mod", "km8");


	echo "</pre>";

}catch(Exception $e){
	echo $e;
	
}





?>
