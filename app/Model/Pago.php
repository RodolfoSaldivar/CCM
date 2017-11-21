<?php
App::uses('AppModel', 'Model');
App::uses('Pedido', 'Model');
/**
 * Pago Model
 *
 */
class Pago extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CorteCaja' => array(
			'className' => 'CorteCaja',
			'foreignKey' => 'corte_caja_id'
		),
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'pedido_id'
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["importe"])) $atributos["importe"] = "cero";
		if (empty($atributos["forma_pago"])) $atributos["forma_pago"] = "NULL";
		if (empty($atributos["referencia"])) $atributos["referencia"] = "NULL";
		if (empty($atributos["fecha_pago"])) $atributos["fecha_pago"] = "NULL";
		if (empty($atributos["fecha_cancelacion"])) $atributos["fecha_cancelacion"] = "NULL";
		if (empty($atributos["pedido_id"])) $atributos["pedido_id"] = "cero";
		if (empty($atributos["usuario_cancelo"])) $atributos["usuario_cancelo"] = "cero";
		if (empty($atributos["corte_caja_id"])) $atributos["corte_caja_id"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["importe"] == "cero") $atributos["importe"] = "0";
		if ($atributos["forma_pago"] == "NULL") $atributos["forma_pago"] = "";
		if ($atributos["referencia"] == "NULL") $atributos["referencia"] = "";
		if ($atributos["fecha_pago"] == "NULL") $atributos["fecha_pago"] = "";
		if ($atributos["fecha_cancelacion"] == "NULL") $atributos["fecha_cancelacion"] = "";
		if ($atributos["pedido_id"] == "cero") $atributos["pedido_id"] = "0";
		if ($atributos["usuario_cancelo"] == "cero") $atributos["usuario_cancelo"] = "0";
		if ($atributos["corte_caja_id"] == "cero") $atributos["corte_caja_id"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerPagos($condiciones)
	{
		$pagos = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Pago.id', 'Pago.importe', 'Pago.forma_pago', 'Pago.referencia', 'Pago.fecha_pago', 'Pago.fecha_cancelacion', 'Pago.pedido_id', 'Pago.usuario_cancelo', 'Pago.corte_caja_id', 'CorteCaja.cajero_id', 'CorteCaja.ciclo', 'CorteCaja.fecha_ap', 'CorteCaja.fecha_cierre', 'CorteCaja.importe_ap', 'Pedido.importe', 'Pedido.fecha_pedido', 'Pedido.fecha_facturado', 'Pedido.fecha_cancelado', 'Pedido.padre_id', 'Pedido.ciclo_hijo_id'
			)
		));

		return $pagos;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos, $accion)
	{
		$Pedido = new Pedido();

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
					INSERT INTO CCM.pagos
						(importe, forma_pago, referencia, fecha_pago, fecha_cancelacion, pedido_id, usuario_cancelo, corte_caja_id)
					VALUES (
						".$atributos['importe'].",
						'".$atributos['forma_pago']."',
						'".$atributos['referencia']."',
						'".$atributos['fecha_pago']."',
						'".$atributos['fecha_cancelacion']."',
						".$atributos['pedido_id'].",
						".$atributos['usuario_cancelo'].",
						".$atributos['corte_caja_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.pagos
					SET importe = ".$atributos['importe'].",
						forma_pago = '".$atributos['forma_pago']."',
						referencia = '".$atributos['referencia']."',
						fecha_cancelacion = '".$atributos['fecha_cancelacion']."'
					WHERE id = ".$atributos['id']."
				");
			}

			if ($queryExitosa)
			{
				$pagos = $this->find('all', array(
					'recursive' => 0,
					'conditions' => array('Pago.pedido_id' => $atributos['pedido_id']),
					'fields' => array('Pedido.importe', 'Pago.importe')
				));

				$pedido_importe = $pagos[0]["Pedido"]["importe"];
				$pagado_al_momento = 0;

				foreach ($pagos as $key => $pago)
					$pagado_al_momento+= $pago["Pago"]["importe"];

				if ($pagado_al_momento >= $pedido_importe)
				{
					$pedido = $Pedido->traerPedidos(array('id' =>$atributos['pedido_id']));
					
					$pedido[0]["Pedido"]["estatus"] = 1;

					$Pedido->guardarEnBDD($pedido[0]["Pedido"], "editar");
				}

				return 1;
			}
			else
				return 'No se pudo guardar.';
		}
	}

}
