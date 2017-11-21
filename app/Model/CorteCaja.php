<?php
App::uses('AppModel', 'Model');
/**
 * CorteCaja Model
 *
 */
class CorteCaja extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Asociado' => array(
			'className' => 'Asociado',
			'foreignKey' => 'cajero_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Pago' => array(
			'className' => 'Pago',
			'foreignKey' => 'corte_caja_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["cajero_id"])) $atributos["cajero_id"] = "cero";
		if (empty($atributos["fecha_ap"])) $atributos["fecha_ap"] = "NULL";
		if (empty($atributos["fecha_cierre"])) $atributos["fecha_cierre"] = "NULL";
		if (empty($atributos["ciclo"])) $atributos["ciclo"] = "NULL";
		if (empty($atributos["importe_ap"])) $atributos["importe_ap"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["cajero_id"] == "cero") $atributos["cajero_id"] = "0";
		if ($atributos["fecha_ap"] == "NULL") $atributos["fecha_ap"] = "";
		if ($atributos["fecha_cierre"] == "NULL") $atributos["fecha_cierre"] = "";
		if ($atributos["ciclo"] == "NULL") $atributos["ciclo"] = "";
		if ($atributos["importe_ap"] == "cero") $atributos["importe_ap"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerCortes($condiciones)
	{
		$corte_cajas = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'CorteCaja.id', 'CorteCaja.cajero_id', 'CorteCaja.ciclo', 'CorteCaja.fecha_ap', 'CorteCaja.fecha_cierre', 'CorteCaja.importe_ap', 'Asociado.nombre', 'Asociado.a_paterno', 'Asociado.a_materno', 'Asociado.mail', 'Asociado.celular', 'Asociado.colegio_id'
			),
			'order' => array('CorteCaja.cajero_id')
		));

		return $corte_cajas;
	}
	

//=========================================================================


	public function traerUnico($condiciones)
	{
		$corte_caja = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'CorteCaja.id', 'CorteCaja.cajero_id', 'CorteCaja.ciclo', 'CorteCaja.fecha_ap', 'CorteCaja.fecha_cierre', 'CorteCaja.importe_ap', 'Asociado.nombre', 'Asociado.a_paterno', 'Asociado.a_materno', 'Asociado.mail', 'Asociado.celular', 'Asociado.colegio_id'
			)
		));

		return $corte_caja;
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
					INSERT INTO CCM.corte_cajas
						(cajero_id, fecha_ap, fecha_cierre, ciclo, importe_ap)
					VALUES (
						".$atributos['cajero_id'].",
						'".$atributos['fecha_ap']."',
						'".$atributos['fecha_cierre']."',
						'".$atributos['ciclo']."',
						".$atributos['importe_ap']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.corte_cajas
					SET cajero_id = ".$atributos['cajero_id'].",
						fecha_ap = '".$atributos['fecha_ap']."',
						fecha_cierre = '".$atributos['fecha_cierre']."',
						ciclo = '".$atributos['ciclo']."',
						importe_ap = ".$atributos['importe_ap']."
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
