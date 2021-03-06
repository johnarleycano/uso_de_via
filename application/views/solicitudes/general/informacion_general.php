<!-- Si tiene id de solicitud, la consulta -->
<?php $solicitud = ($id_solicitud > 0) ? $this->solicitud_model->obtener("solicitud", $id_solicitud) : null ; ?>

<div class="uk-column-1-2@m uk-column-divider">
	<div class="uk-margin">
        <label class="uk-form-label" for="select_tipo">Tipo de solicitud *</label>
        <div class="uk-form-controls">
            <select class="uk-select" id="select_tipo" title="Tipo de solicitud" autofocus>
                <?php foreach($this->configuracion_model->obtener("tipos_solicitudes") as $tipo){ ?>
                    <option value="<?php echo $tipo->Pk_Id; ?>"><?php echo $tipo->Nombre; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="select_proyecto">Proyecto *</label>
        <div class="uk-form-controls">
            <select class="uk-select" id="select_proyecto" title="Proyecto">
                <option value="">Elija un proyecto</option>

                <?php foreach($this->configuracion_model->obtener("proyectos") as $proyecto){ ?>
	                <option value="<?php echo $proyecto->Pk_Id; ?>"><?php echo $proyecto->Nombre_Completo; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="select_municipio">Municipio *</label>
        <div class="uk-form-controls">
            <select class="uk-select" id="select_municipio">
                <option value="">Elija un municipio</option>

                <?php foreach($this->configuracion_model->obtener("municipios") as $municipio){ ?>
	                <option value="<?php echo $municipio->Pk_Id; ?>"><?php echo $municipio->Nombre; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="select_sector">Sector / Vereda / Barrio *</label>
        <div class="uk-form-controls">
            <select class="uk-select" id="select_sector" title="Sector">
                <option value="">Elija primero un municipio</option>
            </select>
        </div>
    </div>
</div>

<div class="uk-column-1-2@m uk-column-divider">
    <div class="uk-margin">
        <label class="uk-form-label" for="input_objeto">Objeto *</label>
        <textarea class="uk-textarea" id="input_objeto" rows="6" title="Objeto"><?php echo ($solicitud) ? $solicitud->Objeto : "" ; ?></textarea>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="input_alcance">Alcance *</label>
        <textarea class="uk-textarea" id="input_alcance" rows="6" title="Alcance"><?php echo ($solicitud) ? $solicitud->Alcance : "" ; ?></textarea>
    </div>
</div>

<script type="text/javascript">
    /**
     * Envía a la base de datos la información del elemento
     * que se está creando
     * 
     * @return {int}
     */
	function guardar()
	{
		cerrar_notificaciones()
		imprimir_notificacion("<div uk-spinner></div> Guardando información general...")

        // Campos obligatorios
		let campos_obligatorios = {
			"select_proyecto": $("#select_proyecto").val(),
			"select_sector": $("#select_sector").val(),
			"input_objeto": $("#input_objeto").val(),
			"input_alcance": $("#input_alcance").val(),
		}
		// imprimir(campos_obligatorios, "tabla")

		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)) return false

		let datos = {
    		"Fk_Id_Tipo_Solicitud": $("#select_tipo").val(),
    		"Fk_Id_Proyecto": $("#select_proyecto").val(),
	    	"Fk_Id_Sector": $("#select_sector").val(),
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    	"Objeto": $("#input_objeto").val(),
	    	"Alcance": $("#input_alcance").val(),
    		"Fecha": "<?php echo date("Y-m-d h:i:s"); ?>",
		}
		// imprimir(datos, "tabla")

		// Se verifica si guarda o actualiza el registro
	    if ($("#id_solicitud").val() == "0"){
            // Inserción en base de datos
		    id = ajax("<?php echo site_url('solicitud/insertar'); ?>", {"tipo": "solicitud", "datos": datos}, 'HTML')

		    // Se pone el id en un campo para validar la demás información que se la asocie
	    	$("#id_solicitud").val(id)
		} else {
            // Actualización de registro en base de datos
		    ajax("<?php echo site_url('solicitud/actualizar'); ?>", {"tipo": "solicitud", "datos": datos, "id_solicitud": $("#id_solicitud").val()}, 'HTML')
		}

		cerrar_notificaciones()
		imprimir_notificacion("Los cambios se actualizaron correctamente.", "success")
	}

	$(document).ready(function(){
		// Se ponen algunos valores por defecto
        select_por_defecto("select_proyecto", 1)

        // Cuando se elija el municipio, se cargan los sectores de ese municipio
		$("#select_municipio").on("change", function(){
			datos = {
				url: "<?php echo site_url('configuracion/obtener'); ?>",
				tipo: "sectores_municipios",
				id: $(this).val(),
				elemento_padre: $("#select_municipio"),
				elemento_hijo: $("#select_sector"),
				mensaje_padre: "Elija primero un municipio...",
				mensaje_hijo: "Elija el sector o corregimiento..."
			}
			cargar_lista_desplegable(datos)
		})
    })
</script>

<!-- Cuando tiene una solicitud -->
<?php if ($solicitud) { ?>
	<script type="text/javascript">
        $(document).ready(function(){
        	// Valores por defecto
        	select_por_defecto("select_tipo", <?php echo $solicitud->Fk_Id_Tipo_Solicitud; ?>)
            select_por_defecto("select_proyecto", <?php echo $solicitud->Fk_Id_Proyecto; ?>)
            select_por_defecto("select_municipio", <?php echo $solicitud->Fk_Id_Municipio; ?>)

            datos = {
                url: "<?php echo site_url('configuracion/obtener'); ?>",
                tipo: "sectores_municipios",
                id: "<?php echo $solicitud->Fk_Id_Municipio; ?>",
                elemento_padre: $("#select_municipio"),
                elemento_hijo: $("#select_sector"),
                mensaje_padre: "Elija primero un municipio...",
                mensaje_hijo: "Elija el sector o corregimiento..."
            }
            cargar_lista_desplegable(datos)
            select_por_defecto("select_sector", <?php echo $solicitud->Fk_Id_Sector; ?>)
        })
    </script> 
<?php } ?>