<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       18 de enero de 2018
 * Programa:    Uso de vía | Módulo inicial
 *              Permite visualizar el resumen o estado de las 
 *              solicitudes de uso de vía
 * Email:       johnarleycano@hotmail.com
 */
class Inicio extends CI_Controller {
	/**
     * Interfaz inicial
     * 
     * @return [void]
     */
	function index()
	{
        $this->data['titulo'] = 'Inicio';
        $this->data['contenido_principal'] = 'inicio/index';
        $this->load->view('core/template', $this->data);
	}
}
/* Fin del archivo Inicio.php */
/* Ubicación: ./application/controllers/Inicio.php */