<?php
App::uses('AppModel', 'Model');
App::uses('ArticulosPrecio', 'Model');
App::uses('CatalogoFamilia', 'Model');
/**
 * ArticulosPaquete Model
 *
 */
class ArticulosPaquete extends AppModel {

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
		),
		'Paquete' => array(
			'className' => 'Paquete',
			'foreignKey' => 'paquete_id'
		)
	);
	

//=========================================================================


	public function precios($condiciones)
	{
		$ArticulosPrecio = new ArticulosPrecio();

		$art_paq = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'ArticulosPaquete.id', 'ArticulosPaquete.precio_publico', 'ArticulosPaquete.cantidad', 'Paquete.id', 'Paquete.identificador', 'Paquete.descripcion', 'Paquete.ciclo', 'Paquete.colegio_id', 'Paquete.nivele_id', 'Paquete.grado_id', 'Articulo.id', 'Articulo.identificador', 'Articulo.descripcion', 'Articulo.cat_fam_id'
			)
		));

		$total_costo_ccm = 0;
		$total_precio_venta = 0;
		$total_precio_publico = 0;
		
		foreach ($art_paq as $key => $articulo)
		{
			$condiciones = array(
				'articulo_id' => $articulo["Articulo"]["id"],
				'ciclo' => $articulo["Paquete"]["ciclo"]
			);

			$precios = $ArticulosPrecio->todosArticulosPrecios($condiciones);

			$total_costo_ccm+= $precios[0]["ArticulosPrecio"]["costo_ccm"] * $articulo["ArticulosPaquete"]["cantidad"];
			$total_precio_venta+= $precios[0]["ArticulosPrecio"]["precio_venta"] * $articulo["ArticulosPaquete"]["cantidad"] ;
			$total_precio_publico+= $articulo["ArticulosPaquete"]["precio_publico"] * $articulo["ArticulosPaquete"]["cantidad"];
		}

		$total_precios = array(
			'costo_ccm' => $total_costo_ccm,
			'precio_venta' => $total_precio_venta,
			'precio_publico' => $total_precio_publico
		);

		return $total_precios;
	}
	

//=========================================================================


	public function traerArticulosEnPaquete($condiciones)
	{
		$ArticulosPrecio = new ArticulosPrecio();
		$CatalogoFamilia = new CatalogoFamilia();

		$art_paq = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'ArticulosPaquete.id', 'ArticulosPaquete.precio_publico', 'ArticulosPaquete.cantidad', 'Paquete.id', 'Paquete.ciclo', 'Articulo.id', 'Articulo.identificador', 'Articulo.descripcion', 'Articulo.cat_fam_id'
			)
		));
		
		foreach ($art_paq as $key => $articulo)
		{
			$condiciones = array(
				'articulo_id' => $articulo["Articulo"]["id"],
				'ciclo' => $articulo["Paquete"]["ciclo"]
			);

			$precios = $ArticulosPrecio->todosArticulosPrecios($condiciones);

			$art_paq[$key]["Articulo"]["costo_ccm"] = $precios[0]["ArticulosPrecio"]["costo_ccm"];
			$art_paq[$key]["Articulo"]["precio_venta"] = $precios[0]["ArticulosPrecio"]["precio_venta"];
			$art_paq[$key]["Articulo"]["iva"] = $precios[0]["ArticulosPrecio"]["iva"];

			$familia_nombre = $CatalogoFamilia->familiaEspecifica($articulo["Articulo"]["cat_fam_id"]);
			$art_paq[$key]["Articulo"]["familia_nombre"] = $familia_nombre["CatalogoFamilia"]["nombre"];
			$art_paq[$key]["Articulo"]["familia_id"] = $familia_nombre["CatalogoFamilia"]["id"];
		}

		return $art_paq;
	}
	

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
					INSERT INTO CCM.articulos_paquetes
						(precio_publico, cantidad, paquete_id, articulo_id)
					VALUES (
						".$atributos['precio_publico'].",
						".$atributos['cantidad'].",
						".$atributos['paquete_id'].",
						".$atributos['articulo_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.articulos_paquetes
					SET precio_publico = ".$atributos['precio_publico'].",
						cantidad = ".$atributos['cantidad'].",
						paquete_id = ".$atributos['paquete_id'].",
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
