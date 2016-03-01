<?php
/**
 * * * * * * * * * * LICENCIA * * * * * * * * * * * * * * * * * * * * *

Copyright(C) 2015
Instituto Universtiario de Tecnología Dr. Federico Rivero Palacio

Este programa es Software Libre y usted puede redistribuirlo y/o modificarlo
bajo los términos de la versión 3.0 de la Licencia Pública General (GPLv3)
publicada por la Free Software Foundation (FSF), es distribuido sin ninguna
garantía. Usted debe haber recibido una copia de la GPLv3 junto con este
programa, sino, puede encontrarlo en la página web de la FSF, 
específicamente en la dirección http://www.gnu.org/licenses/gpl-3.0.html

 * * * * * * * * * * ARCHIVO * * * * * * * * * * * * * * * * * * * * *

Nombre: PeriodoControlador.php
Diseñador: Juan De Sousa (juanmdsousa@gmail.com)
Programador: Juan De Sousa
Fecha: Diciembre de 2015
Descripción:  
	Controlador del módulo periodo, encargado de procesar todas las peticiones del módulo.

 * * * * * * * * Cambios/Mejoras/Correcciones/Revisiones * * * * * * * *
---                         ----      ---------

 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*/	
	require_once ("modulos/periodo/modelo/PeriodoServicio.php");

	class PeriodoControlador{
		
		/*
		 * Método manejarRequerimiento de CursoControlador.
		 * 
		 * Se encarga de manejar la petición e invocar a la función pertinente a la misma.
		 * 
		 * Obtiene la acción de la vista y llama a la función pertinente dentro
		 * del controlador.
		 */
		
		public static function manejarRequerimiento(){
			try{
				$accion = PostGet::obtenerPostGet("m_accion");
				
				if($accion == "listarPeriodos")
					self::listarPeriodo();
				else if($accion == "mostrar")
					self::mostrar();
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
		/*
		 * Método listarPeriodos de PeriodoControlador.
		 * 
		 * Recibe de la vista un código de periodo a listar.
		 * De estar en null, invoca al método listarPeriodos (para listarlos todos)
		 * De no ser así, lista el periodo perteneciente al código.
		 * 
		 * Asigna "periodos" (resultado de la lista) y muestra la vista.
		 */
		
		public static function listarPeriodo(){
			try{
				$codigo = PostGet::obtenerPostGet("codPensum");
				
				if($codigo == NULL)
					$r = PeriodoServicio::obtenerPeriodos();
				else
					$r = PeriodoServicio::obtenerPeriodoPorPensum($codigo);
					
				Vista::asignarDato("periodos",$r);
					
				Vista::mostrar();
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
		/*
		 * Método agregarPeriodo de PeriodoControlador.
		 * 
		 * Obtiene los datos de un periodo a agregar de la vista.
		 * Invoca al servicio con los datos y asigna el resultado a la vista (código generado); 
		 * luego la muestra.
		 * 
		 */
		
		public static function agregarPeriodo(){
			try{
				$nombre = PostGet::obtenerPostGet("nomPeriodo");
				$codIns = PostGet::obtenerPostGet("codIns");
				$codPen = PostGet::obtenerPostGet("codPen");
				$fecIni = PostGet::obtenerPostGet("fecIni");
				$fecFin = PostGet::obtenerPostGet("fecFin");
				$observaciones = PostGet::obtenerPostGet("observaciones");
				$codEst = PostGet::obtenerPostGet("codEst");
				
				$r = PeriodoServicio::insertarPeriodo($nombre, $codIns, $codPen, $fecIni, $fecFin, $observaciones, $codEst);
				
				Vista::asignarDato("codPeriodo",$r);
				
				Vista::mostrar();
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
		/*
		 * Método modificarPeriodo de PeriodoControlador.
		 * 
		 * Obtiene los datos de un periodo a modificar desde la vista.
		 * Luego invoca al método modificar del servicio y le pasa los parámetros.
		 * 
		 * Muestra la vista.
		 */
		
		public static function modificarPeriodo(){
			try{
				$codigo = PostGet::obtenerPostGet("codPeriodo");
				$nombre = PostGet::obtenerPostGet("nomPeriodo");
				$codIns = PostGet::obtenerPostGet("codInstituto");
				$codPen = PostGet::obtenerPostGet("codPensum");
				$fecIni = PostGet::obtenerPostGet("fecInicio");
				$fecFin = PostGet::obtenerPostGet("fecFinal");
				$observaciones = PostGet::obtenerPostGet("observaciones");
				$codEst = PostGet::obtenerPostGet("codEstado");
				
				PeriodoServicio::modificarPeriodo($codigo, $nombre, $codIns, $codPen, $fecIni, $fecFin, $observaciones, $codEst);
				
				Vista::mostrar();
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
		/*
		 * Método eliminarPeriodo de PeriodoControlador
		 * 
		 * Obtiene un código del periodo desde la vista e invoca al método eliminar
		 * periodo del servicio.
		 * 
		 * Luego muestra la vista.
		 */
		
		public static function eliminarPeriodo(){
			try{
				$codigo = PostGet::obtenerPostGet("codPeriodo");
				
				PeriodoServicio::eliminarPeriodo($codigo);
				
				Vista::mostrar();
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
		public static function mostrar(){
			Vista::mostrar();
		}
	}
?>