<?php
  date_default_timezone_set('America/Caracas');

  if(buscar_contrato(4)) {
    echo "verdadero";
  } else { echo "falso"; }

  function conectar() {
    $controlador = 'mysql';
    $bd_nombre = 'poo';
    $bd_usuario = 'root';
    $bd_clave = '';
    $bd_host = 'localhost';

    try {
      $conexion = new PDO( "$controlador:host=$bd_host;dbname=$bd_nombre", $bd_usuario, $bd_clave );
      $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      return $conexion;
    } catch( PDOException $e ) {
      echo $e->getMessage();
      die();
    }
  }
  
  function buscar_contrato( $customer_id ) {
    // LLAMO A LA FUNCION CONECTAR QUE ME DEVUELVE LA CONEXION CON LA BD
    $conexion = conectar(); 
    // PREPARO LA SENTENCIA SQL CON PDO Y LA EJECUTO
    $result = $conexion->prepare( "SELECT contrato_pdf FROM contratos WHERE id = $customer_id" ); 
    $result->execute(); 
    // OBTENGO TODOS LOS DATOS DE LA CONSULTA EN UN OBJETO 
    $datos = $result->fetch(PDO::FETCH_OBJ);
    
    // SI NO EXISTE CONTRATO DEVUELVO VERDADERO
    if( !$datos ) return true; 
    // SI EL CAMPO ESTÁ VACÍO DEVUELVO VERDADERO
    if( $datos->contrato_pdf == '' ) return true; 

    //OBTENGO LA FECHA DEL NOMBRE SUPONIENDO QUE TIENE UN FORMATO 'NOMBRE_ARCHIVO FECHA' DONDE EL DELIMITADOR ES UN ESPACIO EN BLANCO
    $fecha = explode(" ", $datos->contrato_pdf); 
    
    // OBTENGO LA FECHA EN LA POSICION NUMERO 1 SUPONIENDO QUE LA ESTRUCTURA DEL NOMBRE ES 'NOMBRE_ARCHIVO FECHA'
    $fecha_contrato = strtotime($fecha[0]); 
    // LA FECHA ACTUAL DEL SISTEMA
    $fecha_actual = strtotime(date('Y-m-d')); 
    
    // SI LA FECHA DEL CONTRATO ES ANTERIOR A LA DE HOY DEVUELVO VERDADERO
    if( $fecha_contrato < $fecha_actual ) return true; 
    // EN CASO CONTRARIO DEVUELVO FALSO
    else return false; 
  } 
  
?>