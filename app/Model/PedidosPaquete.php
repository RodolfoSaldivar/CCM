<?php
App::uses('AppModel', 'Model');
/**
 * PedidosPaquete Model
 *
 */
class PedidosPaquete extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'pedido_id'
		),
		'Paquete' => array(
			'className' => 'Paquete',
			'foreignKey' => 'paquete_id'
		)
	);
	

//=========================================================================


	public function traerPedidosPaquetes($condiciones)
	{
		$ids = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'MIN(PedidosPaquete.id) as id'
			),
			'group' => array(
				'PedidosPaquete.pedido_id',
				'PedidosPaquete.paquete_id'
			)
		));
		
		$buenos = array();
		foreach ($ids as $key => $id)
			array_push($buenos, $id[0]["id"]);

		$ped_paq = $this->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				'PedidosPaquete.id' => $buenos
			),
			'fields' => array(
				'PedidosPaquete.id', 'PedidosPaquete.paquete_id', 'PedidosPaquete.pedido_id', 'PedidosPaquete.cantidad', 'PedidosPaquete.importe', 'Paquete.id', 'Paquete.identificador', 'Paquete.descripcion', 'Pedido.id', 'Pedido.estatus', 'Pedido.fecha_cancelado', 'Pedido.fecha_facturado'
			)
		));

		return $ped_paq;
	}
	

//=========================================================================


	// public function traerPedidosPaquetes($condiciones)
	// {
	// 	$ped_paq = $this->find('all', array(
	// 		'recursive' => 0,
	// 		'conditions' => $condiciones,
	// 		'fields' => array(
	// 			'PedidosPaquete.id', 'PedidosPaquete.paquete_id', 'PedidosPaquete.pedido_id', 'PedidosPaquete.cantidad', 'PedidosPaquete.importe', 'Paquete.id', 'Paquete.identificador', 'Paquete.descripcion', 'Pedido.id', 'Pedido.estatus', 'Pedido.fecha_cancelado', 'Pedido.fecha_facturado'
	// 		)
	// 	));

	// 	return $ped_paq;
	// }
	

//=========================================================================


	public function guardarEnBDD($atributos, $accion)
	{
		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{
			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.pedidos_paquetes
						(cantidad, importe, paquete_id, pedido_id)
					VALUES (
						".$atributos['cantidad'].",
						".$atributos['importe'].",
						".$atributos['paquete_id'].",
						".$atributos['pedido_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.pedidos_paquetes
					SET cantidad = ".$atributos['cantidad'].",
						importe = ".$atributos['importe']."
					WHERE id = ".$atributos['id']."
				");
			}

			if ($queryExitosa)
				return 1;
			else
				return 'No se pudo guardar.';
		}
	}

}
