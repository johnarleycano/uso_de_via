<input class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" value="Agregar participante" type="button" id="btn_participante" onClick="javascript:crear()" />
<div id="cont_crear"></div>
<hr>
<div id="cont_lista"></div>

<!-- Modal eliminar -->
<div id="modal_eliminar" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Advertencia</h2>
        <p>¿Está seguro de eliminar el participante?</p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
            <button class="uk-button uk-button-primary" type="button" onClick="javascript:eliminar()">Eliminar</button>
        </p>
        <input type="hidden" id="id_participante">
    </div>
</div>

<script type="text/javascript">
	/**
	 * Interfaz de creación
	 * 
	 * @return {}
	 */
	function crear()
	{
		// Carga de interfaz
		$("#cont_crear").load("<?php echo site_url('solicitud/cargar_interfaz'); ?>", {"tipo": "general_participantes_crear"})

		// Mostrar y ocultar
		$(`#cont_crear`).show()
		$(`#btn_participante`).hide()
	}

	/**
	 * Interfaz de cierre
	 * 
	 * @return {}
	 */
	function cerrar_interfaz()
	{
		$(`#btn_participante`).show()
		$(`#cont_crear`).hide()
	}

	function eliminar(id = null)
	{
		if(id){
			$("#id_participante").val(id)
			UIkit.modal("#modal_eliminar").show()
			return false
		}

		cerrar_notificaciones()
		imprimir_notificacion(`<div uk-spinner></div> Eliminando registro ${$("#id_participante").val()}...`)

		// Se elimina el registro
		let eliminar = ajax("<?php echo site_url('solicitud/eliminar'); ?>", {"tipo": "participante", "datos": {"Pk_Id": $("#id_participante").val()}}, 'HTML')
		
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
        imprimir_notificacion("<div uk-spinner></div> Guardando datos del participante...")

        // Campos obligatorios
        let campos_obligatorios = {
			"select_funcionario": $("#select_funcionario").val(),
		}
		// imprimir(campos_obligatorios);

		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)) return false

		// Si ya existe el participante en esa solicitud, no se puede agregar.
		let existe = ajax("<?php echo site_url('solicitud/obtener'); ?>", {"tipo": "participante", "id_solicitud": $("#id_solicitud").val(), "id_funcionario": $("#select_funcionario").val()}, 'JSON')

		// Si existe el participante
		if (existe) {
			cerrar_notificaciones();
			imprimir_notificacion( `Ya es participante de esta solicitud. No se puede agregar nuevamente.`, "danger");

			return false;
		}

		let datos = {
	    	"Fk_Id_Funcionario": $("#select_funcionario").val(),
	    	"Fk_Id_Solicitud": $("#id_solicitud").val(),
	    	"Fecha": "<?php echo date("Y-m-d h:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    }

	    // Inserción en base de datos vía Ajax
		ajax("<?php echo site_url('solicitud/insertar'); ?>", {"tipo": "participante", "datos": datos}, 'HTML')

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
		// Carga de interfaz
    	cargar_interfaz("cont_lista", "<?php echo site_url('solicitud/cargar_interfaz'); ?>", {"tipo": `general_participantes_lista`, "id_solicitud": $("#id_solicitud").val()})
	}

	$(document).ready(function(){
		listar()
	})
</script>