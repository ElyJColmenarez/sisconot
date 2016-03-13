$( document ).ready(function() {
	verTipUniCurricular();
	$("#nota").hide();
	activarCal();
});

function verTipUniCurricular(){
	
	var arr=Array("m_modulo"	,		"curso",
				  "m_accion"	,		"tipoUniCurrilular"
				);

	ajaxMVC(arr,succTipUniCurricular,errAjax)
}

function succTipUniCurricular (data){
	var cad ='';
	$("#IdSelectTipoUni").remove();

	cad="<div id='IdSelectTipoUni'> Tipo";	
	cad+= "<select class='selectpicker' id='selectTipoUni' title='tipo unidad curricular' data-live-search='true' data-size='auto' data-max-options='12' >";
	cad+= "<option value='-1' >Seleccionar</option>";
	if(data.tipUniCurrilular){
		for(var x=0; x<data.tipUniCurrilular.length;x++){
			cad+= "<option value='"+data.tipUniCurrilular[x]['codigo']+"' >"+data.tipUniCurrilular[x]['nombre']+"</option>";
		}
	}
	cad+="</select>";
	cad+="</div>";
	$(cad).appendTo('#tipoUni');
	activarSelect();
}

function verPensum(codigo=null){

	var arr=Array("m_modulo"	,		"pensum",
				  "m_accion"	,		"pensumEstActivo",
				  "codigo"		,		codigo
				);
	ajaxMVC(arr,succPensumEst,errAjax)

}

function succPensumEst (data){
	var cad ='';
	$("#IdPensumEst").remove();
	cad="<div id='IdPensumEst'> Pensum";	
	cad+= "<select onchange='verTrayecto();'class='selectpicker' id='pensumEst' title='tipo unidad curricular' data-live-search='true' data-size='auto' data-max-options='12' >";
	cad+= "<option value='-1' >Seleccionar</option>";
	if(data.pensum){
		for(var x=0; x<data.pensum.length;x++){
			cad+= "<option value='"+data.pensum[x]['pensum_codigo']+"' >"+data.pensum[x]['nom_corto']+"</option>";
		}
	}
	cad+="</select>";
	cad+="</div>";
	$(cad).appendTo('#pensum');
	activarSelect();	
}

function verTrayecto(){

	var arr=Array("m_modulo"	,		"trayecto",
				  "m_accion"	,		"listarTrayectoPensum",
				  "codigo"		,		$("#pensumEst").val()
				);

	ajaxMVC(arr,succTrayecto,errAjax);
}

function succTrayecto (data){
	var cad ='';
	$("#IdTrayecto").remove();

	cad="<div id='IdTrayecto'> Trayecto";	
	cad+= "<select class='selectpicker' id='trayectoEst' title='tipo unidad curricular' data-live-search='true' data-size='auto' data-max-options='12' >";
	cad+= "<option value='-1' >Seleccionar</option>";
	if(data.trayecto){
		for(var x=0; x<data.trayecto.length;x++){
			cad+= "<option value='"+data.trayecto[x]['codigo']+"' >"+data.trayecto[x]['num_trayecto']+"</option>";
		}
	}
	cad+="</select>";
	cad+="</div>";
	$(cad).appendTo('#trayecto');
	activarSelect();
}

function guardarConvalidacion (){
	
	var arr=Array(	"m_modulo"	,		"curso",
				  	"m_accion"	,		"insertarElectiva",
				  	"cod_estudiante",		$("#codigoEstudiante").val(),
				  	"con_nota"		,		$('input:radio[name=nota]:checked').val(),
					"nota"			,		$("#nota").val(),
					"fecha"			,		$("#fecha").val(),
					"cod_tip_uni_curricular",$("#selectTipoUni").val(),
					"cod_pensum"	,		$("#pensumEst").val(),
					"cod_trayecto"	,		$("#trayectoEst").val(),
					"cod_uni_curricular",	$("#codUni").val(),
					"descripcion"	,		$("#descripcionText").val()
				);
	ajaxMVC(arr,succGuardarConvalidacion,errAjax)
}

function succGuardarConvalidacion(data){
	
	console.log(data);
	//alert(data.mensaje);
	if(data.estatus>0)
		mostrarMensaje(data.mensaje,1);
	else
		mostrarMensaje(data.mensaje,2);
}

function autoCompletarEstudiante(estado){

	$(".estudiante").autocomplete({
			delay: 200,  //milisegundos
			minLength: 1,
			source: function( request, response ) {

				var a=Array("m_modulo"		,		"estudiante",
							"m_accion"		,		"buscarEstudiante",
							"patron"		,		request.term,
							"instituto"		,		1,
							"estado"		,		estado
							);
				ajaxMVC(a,function(data){
							return response(data);
						  },
						  function(data){
						  	console.log(data);
							return response([{"label": "Error de conexión", "value": {"nombreCorto":""}}]);

						   }
						);

			},
			select: function (event, ui){
				if(ui.item.value == "null"){
					$(this).val("");
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val("");
					//verConvalidadasEstudiante();
				}
				else{
					$(this).val(ui.item.label);
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val(ui.item.value);
					$("#codigoEstudiante").val(ui.item.value);
					$("#nombre").val(ui.item.nombre);
					$("#cedula").val(ui.item.cedula);
					//alert(ui.item.value+"---");				
					verPensum(ui.item.value);
					verConvalidadasEstudiante(ui.item.value);
				}


			},
			focus: function (event, ui){
				if(ui.item.value == "null"){
					$(this).val("");
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val("");
				}
				else{
					$(this).val(ui.item.label);
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val(ui.item.value);
					$("#codigoEstudiante").val(ui.item.value);
					$("#nombre").val(ui.item.nombre);
					$("#cedula").val(ui.item.cedula);
				}
			}
	});

}


function autoCompletarUniCurricular(){

	$(".uniCurricular").autocomplete({
			delay: 200,  //milisegundos
			minLength: 1,
			source: function( request, response ) {

				var a=Array("m_modulo"		,		"unidad",
							"m_accion"		,		"ListarUnidadesPorPenTraPatron",
							"patron"		,		request.term,
							"trayecto"		,		$("#trayectoEst").val(),
							"pensum"		,		$("#pensumEst").val()
							);

				ajaxMVC(a,function(data){
							return response(data);
						  },
						  function(data){
						  	console.log(data);
							return response([{"label": "Error de conexión", "value": {"nombreCorto":""}}]);

						   }
						);

			},
			select: function (event, ui){
				if(ui.item.value == "null"){
					$(this).val("");
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val("");
				}
				else{
					$(this).val(ui.item.label);
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val(ui.item.value);
					var codigo =ui.item.value;
					verDetalle(codigo);
					$("#codUni").val(codigo);
				}


			},
			focus: function (event, ui){
				if(ui.item.value == "null"){
					$(this).val("");
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val("");
				}
				else{
					$(this).val(ui.item.label);
					event.preventDefault();
					$("#doc"+$(this).attr("id")).val(ui.item.value);
					var codigo =ui.item.value;
					verDetalle(codigo);
				}
			}
	});
}

function verConvalidadasEstudiante(codigo=null){
	var arr= Array(
					"m_modulo" , "curso",
					"m_accion" , "convalidaciones",
					"codigo"   , codigo
					);
	ajaxMVC(arr,succConvalidadas,errAjax); 
}

function succConvalidadas(data){
	var cadena="";
	
	if(data.convalidaciones){
		$("#convalida").remove();
		var dat=data.convalidaciones;
		var conNota="";
		var nota="";
		cad="<tbody id='convalida'>";
		for(var x=0; x<dat.length;x++){
			data=dat[x];
			if(data['con_nota']==true)
				conNota="SI";
			else
				conNota="NO";

			nota="";
			if(data['nota'])
				nota=data['nota'];
			else
				nota=" - ";
			
			cad+="	<tr id="+data['codigo']+" onclick='buscarConvalidacion("+data['codigo']+");'>";
			cad+="	  <td>"+x+"</td>";
			cad+="	  <td>"+data['codigo']+"</td>";
			cad+="	  <td>"+data['nom_corto']+"</td>";
			cad+="	  <td>"+data['num_trayecto']+"</td>";
			cad+="	  <td>"+data['tipo']+"</td>";
			cad+="	  <td>"+data['nombre']+"</td>";
			cad+="	  <td>"+conNota+" "+nota+"</td>";
			cad+="	  <td>"+data['descripcion']+"</td>";
			cad+="	</tr>";
		}
	}
	cad+="</tbody>"
	$(cad).appendTo('#tablaConvalidaciones');
}

function buscarConvalidacion(codigo=null){
	var arr= Array(
					"m_modulo" , "curso",
					"m_accion" , "buscarConvalidacionCodigo",
					"codigo"   , codigo 
					);
	ajaxMVC(arr,succBuscarConvalidacion,errAjax); 
}

function succBuscarConvalidacion (data){
	//alert(JSON.stringify(data));
	if(data.convalidacion){
		dat=data.convalidacion[0];
		$("#nombre").val(dat['nomPersona']);
		$("#codigoEstudiante").val(dat['cod_estudiante']);
		$("#cedula").val(dat['cedula']);
		$("#codConvalidacion").val(dat['codigo']);
		$("#selectTipoUni").val(dat['cod_tip_uni_curricular']);
		$("#fecha").val(dat['fecha']);
		if(dat['con_nota']==true){
			$("#si").prop("checked", true);
			$("#nota").val(dat['nota']);
		}
		else
			$("#no").prop("checked", true);

		$("#trayectoEst").val(dat['cod_trayecto']);
		$("#pensumEst").val(dat['cod_pensum']);
		$("#unidadCurricular").val(dat['nombre']);
		$("#descripcionText").val(dat['descripcion']);
		verDetalle(dat['cod_uni_curricular']);
	}
}

function notaHide(){
	$("#nota").hide();
}

function notaShow(){
	$("#nota").show();
}

function errAjax(data){
	console.log(data);
	//alert(data.mensaje);
	mostrarMensaje("Error de comunicación con el servidor.",2);
}



function verDetalle(codigo){	
	var arr = Array ("m_modulo","unidad",
	 				 "m_accion","buscarCodigoUnidadCurricular",
	 				 "codigo",codigo
	 				 );
	ajaxMVC(arr,consultarDetalleUni,errAjax); 
}

function consultarDetalleUni(data) {
//	console.log(data)
	if (data.estatus>0){
		console.log(data);
		unidad=data.unidad[0];
	//	console.log(unidad);
		cadena="";
		$("#detallePen").remove();
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
		$(cadena).appendTo('#detalle');
		//$("detallePen").replaceWith(cadena);
	}
}





