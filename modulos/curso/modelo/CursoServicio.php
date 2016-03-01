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

Nombre: CursoServicio.php
Diseñador: Juan De Sousa (juanmdsousa@gmail.com)
Programador: Juan De Sousa
Fecha: Diciembre de 2015
Descripción:
	Este es el servicio del módulo Curso. Encargado de todas las operaciones que involucren la base de datos.

 * * * * * * * * Cambios/Mejoras/Correcciones/Revisiones * * * * * * * *
---                         ----      ---------

 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*/

	class CursoServicio{

		/*
		 * Método obtenerCursos de CursoServicio.
		 *
		 * Devuelve una lista con todos los cursos de la base de datos.
		 * Si no hay cursos en la base de datos, retorna null.
		 *
		 * Valores de retorno:
		 * 		Arreglo con información de cursos.
		 * 		Null
		 */

		public static function obtenerCursos(){
			try{
				$conexion = Conexion::conectar();
				$consulta = "select sis.f_curso_sel('cursos')";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->execute();

				$cursors = $ejecutar->fetchAll();
				$ejecutar->closeCursor();

				$results = array();
				$ejecutar = $conexion->query('FETCH ALL IN cursos;');
				$results = $ejecutar->fetchAll();
				$ejecutar->closeCursor();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		/*
		 * Método obtenerCursoPorCodigo de CursoServicio.
		 *
		 * Recibe un código de curso y retorna la información del mismo.
		 * Si no encuentra información, retorna null.
		 *
		 * Valores de entrada:
		 * 		$codigo					Código de curso a consultar.
		 *
		 * Valores de retorno:
		 * 		Arreglo con información de curso.
		 * 		Null
		 *
		 */

		public static function obtenerCursoPorCodigo($codigo){
			try{
				$conexion = Conexion::conectar();
				$consulta = "select sis.f_curso_sel('cursos',:codigo)";
				$ejecutar = $conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$conexion->beginTransaction();
				$ejecutar->execute();
				$cursors = $ejecutar->fetchAll();
				$ejecutar->closeCursor();

				$results = array();
				$ejecutar = $conexion->query('FETCH ALL IN cursos;');
				$results = $ejecutar->fetchAll();
				$ejecutar->closeCursor();
				$conexion->commit();
				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		/*
		 * Método insertarCurso de CursoServicio
		 *
		 * Recibe la información de un curso a ser insertado.
		 * Si se realiza la inserción, devuelve el código del curso que generó.
		 * De no ser así, devuelve cero (y lanza una excepción en la base de datos)
		 *
		 * Valores de entrada:
		 * 		$codPeriodo					Código del periodo del curso a ser agregado.
		 * 		$codUniCurricular			Código de unidad curricular del curso a ser agregado.
		 * 		$codDocente					Código del docente que dictará el curso.
		 * 		$seccion					Sección a la que pertenece el curso.
		 * 		$fecInicio					Fecha de inicio del curso.
		 * 		$fecFinal					Fecha del final del curso.
		 * 		$capacidad					Capacidad del curso.
		 * 		$observaciones				Observaciones del curso.
		 *
		 * Valores de retorno
		 * 		Código que se generó para el curso.
		 */

		public static function insertarCurso($codPeriodo, $codUniCurricular, $codDocente, $seccion, $fecInicio, $fecFinal, $capacidad, $observaciones){
			try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_curso_ins(:p_cod_periodo, :p_cod_uni_curricular, :p_cod_docente, :p_seccion, :p_fec_inicio, :p_fec_final, :p_capacidad, :p_observaciones)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':p_cod_periodo',$codPeriodo, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_uni_curricular',$codUniCurricular, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_docente',$codDocente, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_seccion',$seccion, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_fec_inicio',$fecInicio, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_fec_final',$fecFinal, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_capacidad',$capacidad, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_observaciones',$observaciones, PDO::PARAM_STR);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$codigo = $ejecutar->fetchColumn(0);
				return $codigo;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		/*
		 * Método modificarCurso de CursoServicio
		 *
		 * Recibe la información de un curso a ser modificado.
		 * Si no se pudo modificar, arroja una excepción.
		 *
		 * Valores de entrada:
		 * 		$codigo						Código del curso a ser modificado.
		 * 		$codPeriodo					Código del periodo del curso a ser modificado.
		 * 		$codUniCurricular			Código de unidad curricular del curso a ser modificado.
		 * 		$codDocente					Código del docente que dictará el curso.
		 * 		$seccion					Sección a la que pertenece el curso.
		 * 		$fecInicio					Fecha de inicio del curso.
		 * 		$fecFinal					Fecha del final del curso.
		 * 		$capacidad					Capacidad del curso.
		 * 		$observaciones				Observaciones del curso.
		 *
		 */


		public static function modificarCurso($codigo, $codPeriodo, $codUniCurricular, $codDocente, $seccion, $fecInicio, $fecFinal, $capacidad, $observaciones){
			try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_curso_mod(:codigo, :p_cod_periodo, :p_cod_uni_curricular, :p_cod_docente, :p_seccion, :p_fec_inicio, :p_fec_final, :p_capacidad, :p_observaciones)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_periodo',$codPeriodo, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_uni_curricular',$codUniCurricular, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_docente',$codDocente, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_seccion',$seccion, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_fec_inicio',$fecInicio, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_fec_final',$fecFinal, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_capacidad',$capacidad, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_observaciones',$observaciones, PDO::PARAM_STR);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$row = $ejecutar->fetchColumn(0);

				if ($row == 0)
					throw new Exception("No se puede modificar el curso");
			}
			catch(Exception $e){
				throw $e;
			}
		}

		/*
		 * Método eliminarCurso de CursoServicio.
		 *
		 * Recibe un código de curso y lo elimina de la base de datos.
		 * Si no se puede eliminar, lanza una excepción.
		 *
		 * Valores de entrada:
		 * 		$codigo					Código del curso a ser eliminado.
		 */

		public static function eliminarCurso($codigo){
			try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_curso_del(:codigo)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$row = $ejecutar->fetchColumn(0);

				if ($row == 0)
					throw new Exception("No se pudo eliminar el curso.");
			}
			catch(Exception $e){
				throw $e;
			}
		}

		/*
		 *  CURSO_ESTUDIANTE
		 */

		 /*
		 * Método obtenerCurEst de CursoServicio.
		 *
		 * Devuelve una lista con todos los registros
		 * de cur-estudiante de la base de datos.
		 * Si no hay, retorna null.
		 *
		 * Valores de retorno:
		 * 		Arreglo con información de curEstudiantes.
		 * 		Null
		 */
		 public static function obtenerCurEst(){
			 try{
				$conexion = Conexion::conectar();
				$consulta = "select sis.f_cur_estudiante_sel('curest')";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->execute();

				$cursors = $ejecutar->fetchAll();
				$ejecutar->closeCursor();

				$results = array();
				$ejecutar = $conexion->query('FETCH ALL IN curest;');
				$results = $ejecutar->fetchAll();
				$ejecutar->closeCursor();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		 }

		 /*
		 * Método obtenerCursoPorCodigo de CursoServicio.
		 *
		 * Recibe un código de curso y retorna la información del mismo.
		 * Si no encuentra información, retorna null.
		 *
		 * Valores de entrada:
		 * 		$codigo					Código de curso a consultar.
		 *
		 * Valores de retorno:
		 * 		Arreglo con información de curso.
		 * 		Null
		 *
		 */

		 public static function obtenerCurEstPorCodigo(){
			 try{
				$conexion = Conexion::conectar();
				$consulta = "select sis.f_cur_estudiante_sel_por_codigo('curest',:codigo)";
				$ejecutar = $conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$conexion->beginTransaction();
				$ejecutar->execute();
				$cursors = $ejecutar->fetchAll();
				$ejecutar->closeCursor();

				$results = array();
				$ejecutar = $conexion->query('FETCH ALL IN curest;');
				$results = $ejecutar->fetchAll();
				$ejecutar->closeCursor();
				$conexion->commit();
				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		 }

		 /*
		 * Método insertarCurEst de CursoServicio
		 *
		 * Recibe la información de un cur-estudiante a ser insertado.
		 * Si se realiza la inserción, devuelve el código del cur-estudiante que generó.
		 * De no ser así, devuelve cero (y lanza una excepción en la base de datos)
		 *
		 * Valores de entrada:
		 * 		$codEstudiante				Código del estudiante dentro del curso.
		 * 		$codCurso					Código del curso al que pertenece el estudiante.
		 * 		$porAsistencia				Porcentaje de asistencia del estudiante en el curso.
		 * 		$nota						Nota del estudiante en el curso.
		 * 		$codEstado					Código del estado del estudiante dentro del curso.
		 * 		$observaciones				Observaciones del estudiante dentro del curso.
		 *
		 * Valores de retorno
		 * 		Código que se generó para el curEstudiante.
		 */

		 public static function insertarCurEst($codEstudiante, $codCurso, $porAsistencia, $nota, $codEstado, $observaciones){
			 try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_cur_estudiante_ins(:p_cod_estudiante,:p_cod_curso,:p_por_asistencia,:p_nota,:p_cod_estado,:p_observaciones)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':p_cod_estudiante',$codEstudiante, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_curso',$codCurso, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_por_asistencia',$porAsistencia, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_nota',$nota, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_cod_estado',$codEstado, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_observaciones',$observaciones, PDO::PARAM_STR);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$codigo = $ejecutar->fetchColumn(0);
				return $codigo;
			}
			catch(Exception $e){
				throw $e;
			}
		 }

		 /*
		 * Método modificarCurEst de CursoServicio
		 *
		 * Recibe la información de un cur-estudiante a ser modificado.
		 * Si no se realiza la modificación, lanza una excepción.
		 *
		 * Valores de entrada:
		 * 		$codigo						Código del curEstudiante a modificarse.
		 * 		$codEstudiante				Código del estudiante dentro del curso.
		 * 		$codCurso					Código del curso al que pertenece el estudiante.
		 * 		$porAsistencia				Porcentaje de asistencia del estudiante en el curso.
		 * 		$nota						Nota del estudiante en el curso.
		 * 		$codEstado					Código del estado del estudiante dentro del curso.
		 * 		$observaciones				Observaciones del estudiante dentro del curso.
		 *
		 */

		 public static function modificarCurEst($codigo, $codEstudiante, $codCurso, $porAsistencia, $nota, $codEstado, $observaciones){
			 try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_cur_estudiante_mod(:codigo, :p_cod_estudiante,:p_cod_curso,:p_por_asistencia,:p_nota,:p_cod_estado,:p_observaciones)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_estudiante',$codEstudiante, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_curso',$codCurso, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_por_asistencia',$porAsistencia, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_nota',$nota, PDO::PARAM_INT);
				$ejecutar->bindParam(':p_cod_estado',$codEstado, PDO::PARAM_STR);
				$ejecutar->bindParam(':p_observaciones',$observaciones, PDO::PARAM_STR);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$row = $ejecutar->fetchColumn(0);

				if ($row == 0)
					throw new Exception("No se puede modificar el estudiante en este curso.");
			}
			catch(Exception $e){
				throw $e;
			}
		 }

		 /*
		  * Método eliminarCurEst de CursoServicio
		  *
		  * Elimina la información de un curEstudiante
		  * dentro de la base de datos. Recibe como parámetro el código
		  * del curEstudiante a eliminar.
		  * Si no se pudo eliminar, lanzar una excepción.
		  *
		  * Valor de entrada:
		  * 		$codigo					Código del curestudiante a ser eliminado.
		  */

		 public static function eliminarCurEst($codigo){
			 try{
				$conexion=Conexion::conectar();
				$consulta="select sis.f_cur_estudiante_del(:codigo)";
				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->setFetchMode(PDO::FETCH_ASSOC);

				$ejecutar->execute();

				$row = $ejecutar->fetchColumn(0);

				if ($row == 0)
					throw new Exception("No se pudo eliminar el estudiante en este curso.");
			}
			catch(Exception $e){
				throw $e;
			}
		 }

		public static function listarSeccionPorTrayecto($codigo){
		 	try{
				$conexion=Conexion::conectar();

				$consulta="select 	distinct(cur.seccion)
									from sis.t_uni_tra_pensum as utp
									inner join sis.t_curso as cur
									on utp.cod_uni_curricular = cur.cod_uni_curricular
									where utp.cod_trayecto = :codigo
									order by cur.seccion;";

				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$conexion->beginTransaction();
				$ejecutar->execute();

				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
		 	}
		 	catch(Exception $e){
				throw $e;
		 	}
		}

		public static function listarUniCurricular($seccion, $pensum, $trayecto, $periodo){
		 	try{
				$conexion=Conexion::conectar();

				$consulta=" select 	cur.codigo, uni.nombre
									from sis.t_curso cur
									inner join sis.t_uni_tra_pensum utp
										on cur.cod_uni_curricular = utp.cod_uni_curricular
									inner join sis.t_uni_curricular uni
										on cur.cod_uni_curricular = uni.codigo
									inner join sis.t_periodo per
										on utp.cod_pensum = per.cod_pensum
									where cur.seccion = :seccion and utp.cod_pensum = :pensum
									and utp.cod_trayecto = :trayecto and per.codigo = :periodo
							 			order by uni.nombre;";

				$ejecutar=$conexion->prepare($consulta);

				$ejecutar->bindParam(':seccion',$seccion, PDO::PARAM_STR);
				$ejecutar->bindParam(':pensum',$pensum, PDO::PARAM_INT);
				$ejecutar->bindParam(':trayecto',$trayecto, PDO::PARAM_INT);
				$ejecutar->bindParam(':periodo',$periodo, PDO::PARAM_INT);

				$conexion->beginTransaction();
				$ejecutar->execute();

				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
		 	}
		 	catch(Exception $e){
				throw $e;
		 	}
		}

		public static function listarCursos($codIns,$codPen,$codPer,$patron){
			try{
				$conexion=Conexion::conectar();

				$consulta=" select 	cur.codigo,
									tra.num_trayecto,
									cur.seccion,
									uni.nombre,
									pers.nombre1 || ' ' || pers.apellido1 as nombredocente,
									t1.t c,
									cur.capacidad,
									cur.fec_inicio,
									cur.fec_final
									from sis.t_periodo per
									inner join sis.t_curso cur
										on cur.cod_periodo = per.codigo
									inner join sis.t_uni_curricular uni
										on uni.codigo = cur.cod_uni_curricular
									inner join sis.t_uni_tra_pensum utp
										on utp.cod_pensum = per.cod_pensum
									inner join sis.t_trayecto tra
										on tra.codigo = utp.cod_trayecto
									left join sis.t_persona pers
										on pers.codigo = cur.cod_docente
									left join sis.t_cur_estudiante curest
										on curest.cod_curso = cur.codigo
									left join (SELECT ce.cod_curso, COUNT(*) t FROM sis.t_cur_estudiante ce group by ce.cod_curso) t1
										on t1.cod_curso = cur.codigo
									where per.cod_instituto = ? and per.cod_pensum = ? and per.codigo = ?
									and (upper(uni.nombre) like upper(?) or upper(pers.nombre1) like upper(?) or upper(pers.nombre2) like upper(?)
																	or upper(pers.apellido1) like upper(?) or upper(pers.apellido2) like upper(?))
									group by
										t1.t,
										cur.codigo,
										tra.num_trayecto,
										cur.seccion,
										uni.nombre,
										pers.nombre1 || ' ' || pers.apellido1,
										cur.capacidad,
										cur.fec_inicio,
										cur.fec_final
									order by
										tra.num_trayecto,
										cur.seccion,
										uni.nombre;";

				$ejecutar=$conexion->prepare($consulta);
				$conexion->beginTransaction();
				$patron = "%".$patron."%";
				$ejecutar->execute(Array($codIns,$codPen,$codPer,$patron,$patron,$patron,$patron,$patron));
				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}


		public static function obtenerDatosDeCurso($codigo){
			try{
				$conexion=Conexion::conectar();

				$consulta="select 	per.cod_instituto,
									per.cod_pensum,
									per.codigo,
									utp.cod_trayecto,
									cur.seccion,
									cur.cod_uni_curricular,
									cur.cod_docente,
									ins.nombre nombreins,
									pen.nombre nombrepen,
									tra.num_trayecto numtrayecto,
									uni.nombre nombreuni,
									per.nombre nombreperiodo,
									pers.nombre1 || ' ' || pers.apellido2 as nombredocente
									from sis.t_curso cur
									inner join sis.t_periodo per
										on per.codigo = cur.cod_periodo
									inner join sis.t_uni_tra_pensum utp
										on utp.cod_pensum = per.cod_pensum and utp.cod_uni_curricular = cur.cod_uni_curricular
									inner join sis.t_instituto ins
										on ins.codigo = per.cod_instituto
									inner join sis.t_pensum pen
										on pen.codigo = per.cod_pensum
									inner join sis.t_trayecto tra
										on tra.codigo = utp.cod_trayecto
									inner join sis.t_uni_curricular uni
										on uni.codigo = cur.cod_uni_curricular
									left join sis.t_persona pers
										on pers.codigo = cur.cod_docente
									where cur.codigo = :codigo;";

				$ejecutar=$conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->execute();
				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		public static function obtenerCursosPensum($seccion, $trayecto, $periodo){
			try{
				$conexion=Conexion::conectar();

				$consulta="select 	cur.codigo codCurso,
									uni.nombre,
									cur.capacidad,
									uni.codigo,
									pers.codigo,
									pers.nombre1 || ' ' || pers.apellido1 nombredocente,
									cur.fec_inicio,
									cur.fec_final,
									cur.observaciones
									from sis.t_periodo as per
									inner join sis.t_uni_tra_pensum as utp
										on utp.cod_pensum = per.cod_pensum
									left join sis.t_curso as cur
										on cur.cod_uni_curricular = utp.cod_uni_curricular
									left join sis.t_persona as pers
										on cur.cod_docente = pers.codigo
									inner join sis.t_uni_curricular as uni
										on uni.codigo = utp.cod_uni_curricular
									where utp.cod_trayecto = :trayecto and per.codigo = :periodo or cur.seccion = :seccion
									order by
										cur.codigo;
";

				$ejecutar=$conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->bindParam(':seccion',$seccion, PDO::PARAM_STR);
				$ejecutar->bindParam(':trayecto',$trayecto, PDO::PARAM_INT);
				$ejecutar->bindParam(':periodo',$periodo, PDO::PARAM_INT);
				$ejecutar->execute();
				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		public static function obtenerEstusEstudiante(){
			try{
				$conexion = Conexion::conectar();

				$consulta = "select 	codigo,
										nombre
										from sis.t_est_cur_estudiante
										where nombre <> 'Retirado'
										order by nombre;";

				$ejecutar=$conexion->prepare($consulta);

				$conexion->beginTransaction();
				$ejecutar->execute();
				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		public static function retirarEstudiante($est, $curso){
			try{
				$conexion = Conexion::conectar();

				$consulta = "update sis.t_cur_estudiante set cod_estado = 'R' where cod_estudiante = :est and cod_curso = :curso;";

				$ejecutar=$conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->bindParam(':est',$est, PDO::PARAM_INT);
				$ejecutar->bindParam(':curso',$curso, PDO::PARAM_INT);
				$ejecutar->execute();
				$conexion->commit();
			}
			catch(Exception $e){
				throw $e;
			}
		}

		public static function obtenerCursosPorEstudiante($codigo){
			try{
				$conexion = Conexion::conectar();

				$consulta = "select 	cur.codigo,
										uni.nombre,
										per.apellido1 || ' ' || per.nombre1 as nombredoc
										from sis.t_cur_estudiante as curest
										inner join sis.t_curso as cur
											on cur.codigo = curest.cod_curso
										inner join sis.t_uni_curricular as uni
											on uni.codigo = cur.cod_uni_curricular
										left join sis.t_persona as per
											on per.codigo = cur.cod_docente
										where curest.cod_estudiante = :codigo and curest.cod_estado = 'C';";

				$ejecutar=$conexion->prepare($consulta);

				$conexion->beginTransaction();
				$ejecutar->bindParam(':codigo',$codigo, PDO::PARAM_INT);
				$ejecutar->execute();
				$results = $ejecutar->fetchAll();
				$conexion->commit();

				if(count($results) > 0)
					return $results;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}

		public static function eliminarEstudiante($est, $curso){
			try{
				$conexion = Conexion::conectar();

				$consulta = "delete from sis.t_cur_estudiante where cod_estudiante = :est and cod_curso = :curso;";

				$ejecutar=$conexion->prepare($consulta);
				$conexion->beginTransaction();
				$ejecutar->bindParam(':est',$est, PDO::PARAM_STR);
				$ejecutar->bindParam(':curso',$curso, PDO::PARAM_INT);
				$ejecutar->execute();
				$conexion->commit();
			}
			catch(Exception $e){
				throw $e;
			}
		}

	}
?>