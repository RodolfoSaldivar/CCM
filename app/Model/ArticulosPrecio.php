<?php
App::uses('AppModel', 'Model');
/**
 * ArticulosPrecio Model
 *
 */
class ArticulosPrecio extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Articulo' => array(
			'className' => 'Articulo',
			'foreignKey' => 'articulo_id'
		)
	);
	

//=========================================================================


	public function todosArticulosPrecios($condiciones)
	{
		$precios = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'ciclo', 'costo_ccm', 'precio_venta', 'precio_publico_default', 'iva'
			),
			'order' => array(
				'ciclo' => 'desc'
			)
		));

		return $precios;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos, $accion)
	{
		if (!$atributos["iva"]) $atributos["iva"] = "cero";

		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{
			if ($atributos["iva"] == "cero") $atributos["iva"] = 0;

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.articulos_precios
						(ciclo, costo_ccm, precio_venta, precio_publico_default, iva, articulo_id)
					VALUES (
						".$atributos['ciclo'].",
						".$atributos['costo_ccm'].",
						".$atributos['precio_venta'].",
						".$atributos['precio_publico'].",
						".$atributos['iva'].",
						".$atributos['articulo_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.articulos_precios
					SET ciclo = ".$atributos['ciclo'].",
						costo_ccm = ".$atributos['costo_ccm'].",
						precio_venta = ".$atributos['precio_venta'].",
						precio_publico_default = ".$atributos['precio_publico'].",
						iva = ".$atributos['iva'].",
						articulo_id = ".$atributos['articulo_id']."
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
