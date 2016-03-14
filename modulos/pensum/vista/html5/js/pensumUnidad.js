

function cargarTodasUnidadCurricular(){
		var arr = Array("m_modulo"		,		"unidad",
					"m_accion"		,		"listarUnidades"
					
					);

	ajaxMVC(arr,ssccCargaUnidadesC,errorLista);
}



function cargarUnidadCurricular(){
		var arr = Array("m_modulo"		,		"unidad",
					"m_accion"		,		"buscarUniCurricularPorPensum",
					"codigo" 		, 	$("#codigoPensum").val()
					);

	ajaxMVC(arr,succCargarListT,errorLista);
}



function ObtenerIDPensum(){
	
	var loc = document.location.href;	
	var getString = loc.search("codigoPensum");	
	 if (getString != -1){
       	 getString+=13;
	     var a = loc.substring(getString,getString+25);
	//     console.log(a);
	     $("#codigoPensum").val(a);
	//     alert( $("#codigoPensum").val());
			return true;
	   }else{
	   	alert('No posee codigo Pensum en url');
	   		return false;
	   }
}



function ssccCargaUnidadesC(data){

	console.log(data)
	var ins = data.unidades;
	var cad = "";

	cad += "<select class='selectpicker' id='selUnidad' onchange='obtenerCodigo()' data-live-search='true' data-size='auto' multiple data-max-options='1' title='Seleccione Unidad Curricular'>";

	if(ins){
		for(var i = 0; i < ins.length; i++){
				
			cad += "<option value="+ins[i][0]+"-"+ins[i][9]+">"+ins[i]["nombre"]+"</option>";
		}
		cad += "</select>";
	}
	else{
		cad += "<option></option>";
	}

	$("#selUnidad").selectpicker("destroy");
	$("#divUni").append(cad);
	$("#selUnidad").selectpicker();

//	console.log(data);	
}


function obtenerCodigo(selUnidad){
		$("#codigoUni").val($("#selUnidad").val());	

}


function errorLista(data){
mostrarMensaje("Error Cargando Lista consultar LOG",2);
//console.log(data);
}


function CargarTrayectosPorPensum(){

	var arr = Array("m_modulo"		,	"pensum",
					"m_accion"		,	"buscarPorTrayecto",
					"codigo" 		, 	$("#codigoPensum").val());

//	console.log(arr.toString())
	ajaxMVC(arr,succCargarSelect,errorLista);
}

function succCargarSelect(data){
//	console.log(data)
	var ins = data.pensum;
	var cad = "";

	cad += "<select class='selectpicker' id='seltray' data-live-search='true' data-size='auto' multiple data-max-options='1' title='Seleccione Trayecto'>";

	if(ins){
			cad += "<option value='-1' selected>Sin Asignar</option>";
		for(var i = 0; i < ins.length; i++){

			cad += "<option value="+ins[i][0]+">"+ins[i][2]+"</option>";
		}
		cad += "</select>";
	}
	else{
		cad += "<option></option>";
	}

	$("#seltray").selectpicker("destroy");
	$("#divTra").append(cad);
	$("#seltray").selectpicker();

//	console.log(data);	
}

function asignarUniCodigo(codigo){
	$("#codigoUniTraPen").val(codigo);
}

function succCargarListT(data){
	var cadena="";
	console.log(data)
	var trayectos = data["pensum"];
//	console.log(trayectos)
	if (data["pensum"] != null){
		cadena+=" <div class='trow' style='width:100%; height:200px; overflow:auto;'>";		                			
		cadena+="<table class='table table-hover mbn'><thead><tr class='active'> ";
		cadena+="<th>#N° Trayecto</th> ";
		cadena+="		<th>Tipo</th> "; 
		cadena+="		<th>(codigo)Nombre</th> ";
		cadena+="		<th></th>  ";
		cadena+="<th></th></tr></thead>";
	    cadena+="<tbody> ";
	    for (var i = 0; i < trayectos.length ; i++) {	
	    	if (trayectos[i][10] == null){
	    		numt = "Sin Tray.";
	    	}else {
	    		numt = trayectos[i][10]; }

	    	 cadena+="  <tr class='active' href='#' onclick='verDetalle( "+ trayectos[i]["cod_uni_curricular"]+ "); asignarUniCodigo("+ trayectos[i][8]+ ")' >";
	    	 cadena+="<td style='text-align: left;'># "+ numt  +" ("+trayectos[i][8]+")</td>";
	    	 cadena+="<td style='text-align: left;' >"+ trayectos[i][26]+ "</td>";
	    	  cadena+="<td colspan='2' style='text-align: left;'>("+ trayectos[i]["cod_uni_curricular"]+ ") "+ trayectos[i][15]+ "</td>";
	    	  cadena+="<td></td> ";
	    	  cadena+="</tr>";
	    };
	    cadena+=""
	    cadena+="</tbody></table></div>";
		$("div.trow").replaceWith(cadena);

	}
}



function verDetalle(codigo){	
	var arr = Array ("m_modulo","unidad",
	 				 "m_accion","buscarCodigoUnidadCurricular",
	 				 "codigo",codigo);
	ajaxMVC(arr,consultarDetalleUni,errorBuscarUni); 
}


function errorBuscarUni(){mostrarMensaje("No se puede la unidad curricular en este momento",2);}


/*
consulta todo los detalles
*/

// ver los detalles
function consultarDetalleUni(data) {
//	console.log(data)
	if (data.estatus>0){
		
		unidad=data.unidad[0];
	//	console.log(unidad);
		cadena="";
		
		cadena+="<div id='detallePen' class='third'>";
		cadena+="<table id='pesum' class='table table-bordered table-striped' style='clear: both'><tbody>";
		cadena+="<tr><td style='width: 35%;''>Código:</td><td style='width: 65%;'><a href='#'' >"+unidad[0]+"</a></td></tr>";
		cadena+="<tr><td>Código de Ministerio:</td><td>";
		cadena+="<a href='#'' >"+unidad[1]+"</a></td></tr>";
		cadena+="<tr><td>Nombre:</td><td>";
		cadena+="<a href='#'' >"+unidad[2]+"</a></td></tr>";		
		cadena+="<tr><td>Horas de Trabajo Acompañadas (HTA):</td><td>";
		cadena+="<a href='#'' >"+unidad[3]+"</a></td></tr>";
		cadena+="<tr><td>Horas de Trabajo Independiente (HTI):</td><td>";
		cadena+="<a href='#'' >"+unidad[4]+"</a></td></tr>";	
		cadena+="<tr><td>Unidades de Crédito:</td><td>";
		cadena+="<a href='#'' >"+unidad[5]+"</a></td></tr>";	
		cadena+="<tr><td>Duración de Semanas:</td><td>";
		cadena+="<a href='#'' >"+unidad[6]+"</a></td></tr>";
		cadena+="<tr><td>Nota Mínima Aprobatoria:</td><td>";
		cadena+="<a href='#'' >"+unidad[7]+"</a></td></tr>";	
		cadena+="<tr><td>Nota Máxima:</td><td>";
		cadena+="<a href='#'' >"+unidad[8]+"</a></td></tr>";
		cadena+="<tr><td>Descripción:</td><td>";
		cadena+="<a href='#'' >"+unidad[9]+"</a></td></tr>";
		cadena+="<tr><td>Observación:</td><td>";
		cadena+="<a href='#'' >"+unidad[10]+"</a></td></tr>";
		cadena+="<tr><td>Contenido:</td><td>";
		cadena+="<a href='#'' >"+unidad[11]+"</a></td></tr>";
		cadena+="</tbody></table></div>";
	//	$("#detallePen").html(cadena);
	//	alert(cadena);
		//$(cadena).appendTo('#detallePen');
		$("div.third").replaceWith(cadena);
		$( "#bloqEliEdit" ).removeClass( "inactivoA" );
	}
}


function agregarModificar(){
	//if (validarFrom()){
	//	if ($("#codigo").val() != ''){
			// trayecto
			var tray ;
			if ($("#seltray").val() == -1 ){
				 tray = null;			 
			}else{
				trayecto = 	$("#seltray").val();
				tray = trayecto[0]
			}
			// unidada curricular 
			var codigoUnidad = $("#selUnidad").val(); 
			var a = codigoUnidad[0].search("-");	
		    var UniCod = codigoUnidad[0].substring(0,a);	
		    // tipo	
			var tipo = ($("#selTipo").val())		
			if ($("#codigo").val() == ""){
			var arr = Array (
				"m_modulo","unidad",
				"m_accion","AgregarUniTraPen",
				"pensum",$("#codigoPensum").val(),
				"trayecto", tray,
				"codigoUnidad",UniCod,
				"tipo",tipo[0]
				);	 
			 ajaxMVC(arr,succAgregando,erroAgregando);
			}else{
				var arr = Array (
					"m_modulo","unidad",
					"m_accion","ModificarUniTraPen",
					"codigo", $("#codigo").val(),
					"pensum",$("#codigoPensum").val(),
					"trayecto", tray,
					"codigoUnidad",UniCod,
					"tipo",tipo[0]
				);	 
			  ajaxMVC(arr,succEditAgregando,erroEdit);
			}
		console.log(arr.toString())
	 /*	}else{
	 		var arr = Array (
		    "m_modulo","unidad",
		    "m_accion","AgregarUniTraPen",
			"codMinisterio",$("#codigoMinisterio").val(),
			
			);	
		console.log(arr.toString()) 
	 	}*/
		mostrarMensaje("Exito Agregando Relacion",1)
		
		
//	}else{
	//	errorCamposInvalidos();
	//}
}


function succAgregando(data){
	console.log(data)
	if(data.estatus > 0){
			mostrarMensaje("Exito Agregando Relacion",1)
	}else
		mostrarMensaje("No se pudo agregar la unidad",2)
};

function erroAgregando(data){
	console.log(data.responseText)
	mostrarMensaje("No se puede hacer el cambio Campos Invalidos",2)};

function succEditAgregando(data){
	console.log(data)
	if(data.estatus > 0){
			mostrarMensaje("Exito Editando Relacion",1)
	}else
		mostrarMensaje("No se pudo agregar la unidad",2)
}



function erroEdit(data){
	console.log(data.responseText)
	mostrarMensaje("Error consulte Log ",2)};

function errorCamposInvalidos(){mostrarMensaje("No se puede hacer el cambio Campos Invalidos",3)};



function editarRelacion(){
	console.log($("#seltray").val());
	//$('.selectpicker').selectpicker('refresh');
		var arr = Array (
				"m_modulo","unidad",
				"m_accion","obtenerUniTraPen",
				"codigo",$("#codigoUniTraPen").val()		
				);	 
			 ajaxMVC(arr,succEditconsulta,erroAgregando);

}

function succEditconsulta(data){
	console.log(data)

	console.log(data.UnidadR[0]["cod_tipo"])
	// input codigo
	$("#codigo").val(data.UnidadR[0]["codigo"]);
	$("#codigoUni").val(data.UnidadR[0]["cod_uni_curricular"]);
	// select unidada
	$("#selUnidad").val(data.UnidadR[0]["cod_uni_curricular"]+"-"+data.UnidadR[0]["descripcion"]);
	// select trayecto
	if (data.UnidadR[0]["cod_trayecto"]){
		$("#seltray").val(data.UnidadR[0]["cod_trayecto"]);
	}else{
		$("#seltray").val('-1');
	}
	// 
	$("#selTipo").val(data.UnidadR[0]["cod_tipo"]);

	$(".selectpicker").selectpicker("refresh");

	console.log($("#seltray").val());
}



function ssccEditSelectUniC(data){

/*	console.log(data)
	var ins = data.unidades;
	var cad = "";

	cad += "<select class='selectpicker' id='selUnidad' onchange='obtenerCodigo()' data-live-search='true' data-size='auto' multiple data-max-options='1' title='Seleccione Unidad Curricular'>";

	if(ins){
		for(var i = 0; i < ins.length; i++){
				
			cad += "<option value="+ins[i][0]+"-"+ins[i][9]+">"+ins[i]["nombre"]+"</option>";
		}
		cad += "</select>";
	}
	else{
		cad += "<option></option>";
	}

	$("#selUnidad").selectpicker("destroy");
	$("#divUni").append(cad);
	$("#selUnidad").selectpicker();*/

//	console.log(data);	
}





function eliminarUnidadR(){
	if (confirm("¿Desea eliminar el siguiente Unidad ?")){
		var arr = Array (
			"m_modulo","unidad",
			"m_accion","eliminarUniTraPen",
			"codigo",$("#codigoUniTraPen").val()		
			);	 
		console.log("bandera");
		ajaxMVC(arr,succEliminarUnidad,errorEliminado)
	}
}

function succEliminarUnidad(data){
	alert()
	console.log(data)
	if(data.estatus > 0){
		mostrarMensaje("Exito eliminando Relacion",1)
		cargarUnidadCurricular();
	}else{
		mostrarMensaje("No se pudo eliminar la unidad consultar LOG",2)
	}
}

function errorEliminado(data){
	alert()
	console.log(data)
	console.log(data.responseText)
	mostrarMensaje("No se pudo eliminar la unidad",2)
}