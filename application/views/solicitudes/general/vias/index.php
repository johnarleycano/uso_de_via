<input class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" value="Agregar vía" type="button" id="btn_via" onClick="javascript:crear()" />
<div id="cont_crear"></div>
<hr>
<div id="cont_lista"></div>

<!-- Modal eliminar -->
<div id="modal_eliminar" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Advertencia</h2>
        <p>¿Está seguro de eliminar el registro?</p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
            <button class="uk-button uk-button-primary" type="button" onClick="javascript:eliminar()">Eliminar</button>
        </p>
        <input type="hidden" id="id_via">
    </div>
</div>

<script type="text/javascript">
	function crear()
	{
		$("#cont_crear").load("<?php echo site_url('solicitud/cargar_interfaz'); ?>", {"tipo": "general_vias_crear"})
		$(`#cont_crear`).show()
		$(`#btn_via`).hide()
	}

	function cerrar_interfaz()
	{
		$(`#btn_via`).show()
		$(`#cont_crear`).hide()
	}

	function eliminar(id = null)
	{
		if(id){
			$("#id_via").val(id)
			UIkit.modal("#modal_eliminar").show()
			return false
		}

		cerrar_notificaciones()
		imprimir_notificacion(`<div uk-spinner></div> Eliminando vía ${$("#id_via").val()}...`)

		// Se elimina el registro
		let eliminar = ajax("<?php echo site_url('solicitud/eliminar'); ?>", {"tipo": "via", "datos": {"Pk_Id": $("#id_via").val()}}, 'HTML')
		
		// Si se elimina
		if(eliminar){
			listar()
			UIkit.modal("#modal_eliminar").hide()

			cerrar_notificaciones();
			imprimir_notificacion(`Registro eliminado con éxito`, `success`)
		}
	}

	/**
	 * Envía a la base de datos la información del elemento
	 * que se está creando
	 * 
	 * @return {int}
	 */
	function guardar()
	{
		cerrar_notificaciones()
        imprimir_notificacion("<div uk-spinner></div> Guardando datos de la vía...")

        // Campos obligatorios
		campos_obligatorios = {
			"select_costado": $("#select_costado").val(),
		}
		// imprimir(campos_obligatorios)

		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)) return false

		let datos = {
	    	"Fk_Id_Costado": $("#select_costado").val(),
	    	"Fk_Id_Tramo": $("#select_tramo").val(),
	    	"Observaciones": $("#input_observaciones").val(),
	    	"Fk_Id_Solicitud": $("#id_solicitud").val(),
	    	"Abscisa_Inicial": $("#input_abscisa_inicial").val(),
	    	"Abscisa_Final": $("#input_abscisa_final").val(),
	    	"Fecha": "<?php echo date("Y-m-d h:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    }
	    // imprimir(datos, "tabla")

	    // Inserción en base de datos vía Ajax
		ajax("<?php echo site_url('solicitud/insertar'); ?>", {"tipo": "via", "datos": datos}, 'HTML');

		cerrar_interfaz()

		cerrar_notificaciones();
		imprimir_notificacion(`El registro se ha agregado correctamente.`, `success`)

		listar()
	}

	/**
	 * Interfaz de listado de registros
	 * 
	 * @return {void}              
	 */
	function listar(tipo)
	{
        cargar_interfaz("cont_lista", "<?php echo site_url('solicitud/cargar_interfaz'); ?>", {"tipo": `general_vias_lista`, "id_solicitud": $("#id_solicitud").val()})
	}

	$(document).ready(function(){
		listar()
	})
</script>