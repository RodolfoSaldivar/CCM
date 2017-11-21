<?php
App::uses('AppModel', 'Model');
/**
 * OrdenCompra Model
 *
 */
class OrdenCompra extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'colegio_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'DetalleOrdenCompra' => array(
			'className' => 'DetalleOrdenCompra',
			'foreignKey' => 'orden_compra_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["uuid"])) $atributos["uuid"] = "NULL";
		if (empty($atributos["ciclo"])) $atributos["ciclo"] = "NULL";
		if (empty($atributos["fecha_pedido"])) $atributos["fecha_pedido"] = "NULL";
		if (empty($atributos["fecha_facturado"])) $atributos["fecha_facturado"] = "NULL";
		if (empty($atributos["fecha_modificado"])) $atributos["fecha_modificado"] = "NULL";
		if (empty($atributos["forma_pago"])) $atributos["forma_pago"] = "NULL";
		if (empty($atributos["fecha_pago"])) $atributos["fecha_pago"] = "NULL";
		if (empty($atributos["importe_compra"])) $atributos["importe_compra"] = "cero";
		if (empty($atributos["pago_final"])) $atributos["pago_final"] = "cero";
		if (empty($atributos["colegio_id"])) $atributos["colegio_id"] = "cero";
		if (empty($atributos["ajuste_iva"])) $atributos["ajuste_iva"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["uuid"] == "NULL") $atributos["uuid"] = "";
		if ($atributos["ciclo"] == "NULL") $atributos["ciclo"] = "";
		if ($atributos["fecha_pedido"] == "NULL") $atributos["fecha_pedido"] = "";
		if ($atributos["fecha_facturado"] == "NULL") $atributos["fecha_facturado"] = "";
		if ($atributos["fecha_modificado"] == "NULL") $atributos["fecha_modificado"] = "";
		if ($atributos["forma_pago"] == "NULL") $atributos["forma_pago"] = "";
		if ($atributos["fecha_pago"] == "NULL") $atributos["fecha_pago"] = "";
		if ($atributos["importe_compra"] == "cero") $atributos["importe_compra"] = "0";
		if ($atributos["pago_final"] == "cero") $atributos["pago_final"] = "0";
		if ($atributos["colegio_id"] == "cero") $atributos["colegio_id"] = "0";
		if ($atributos["ajuste_iva"] == "cero") $atributos["ajuste_iva"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerOrdenCompra($condiciones)
	{
		$orden = $this->find('first', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'uuid', 'ciclo', 'fecha_pedido', 'fecha_facturado', 'fecha_modificado', 'importe_compra', 'pago_final', 'forma_pago', 'colegio_id', 'fecha_pago', 'ajuste_iva'
			)
		));

		return $orden;
	}
	

//=========================================================================


	public function conInfoColegio($condiciones)
	{
		$orden = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'OrdenCompra.id', 'OrdenCompra.uuid', 'OrdenCompra.ciclo', 'OrdenCompra.fecha_pedido', 'OrdenCompra.fecha_facturado', 'OrdenCompra.fecha_modificado', 'OrdenCompra.importe_compra', 'OrdenCompra.pago_final', 'OrdenCompra.forma_pago', 'OrdenCompra.colegio_id', 'OrdenCompra.fecha_pago', 'OrdenCompra.ajuste_iva', 'Colegio.id', 'Colegio.identificador', 'Colegio.nombre', 'Colegio.asociado_id'
			)
		));

		return $orden;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos, $accion)
	{
		$atributos = $this->llenarAtributosVacios($atributos);

		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{	
			$atributos = $this->quitarNulos($atributos);

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.orden_compras
						(uuid, ciclo, fecha_pedido, fecha_facturado, fecha_modificado, forma_pago, fecha_pago, importe_compra, pago_final, colegio_id, ajuste_iva)
					VALUES (
						'".$atributos['uuid']."',
						'".$atributos['ciclo']."',
						'".$atributos['fecha_pedido']."',
						'".$atributos['fecha_facturado']."',
						'".$atributos['fecha_modificado']."',
						'".$atributos['forma_pago']."',
						'".$atributos['fecha_pago']."',
						".$atributos['importe_compra'].",
						".$atributos['pago_final'].",
						".$atributos['colegio_id'].",
						".$atributos['ajuste_iva']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.orden_compras
					SET uuid = '".$atributos['uuid']."',
						ciclo = '".$atributos['ciclo']."',
						fecha_pedido = '".$atributos['fecha_pedido']."',
						fecha_facturado = '".$atributos['fecha_facturado']."',
						fecha_modificado = '".$atributos['fecha_modificado']."',
						forma_pago = '".$atributos['forma_pago']."',
						fecha_pago = '".$atributos['fecha_pago']."',
						importe_compra = ".$atributos['importe_compra'].",
						pago_final = ".$atributos['pago_final'].",
						colegio_id = ".$atributos['colegio_id'].",
						ajuste_iva = ".$atributos['ajuste_iva']."
					WHERE id = ".$atributos['id']."
				");
			}

			if ($queryExitosa)
			{
				return 1;
			}
			else
				return 'No se pudo guardar el asociado.';
		}
	}

}
