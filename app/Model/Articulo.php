<?php
App::uses('AppModel', 'Model');
App::uses('ArticulosPrecio', 'Model');
/**
 * Articulo Model
 *
 */
class Articulo extends AppModel {

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
		),
		'Nivele' => array(
			'className' => 'Nivele',
			'foreignKey' => 'nivele_id'
		),
		'Grado' => array(
			'className' => 'Grado',
			'foreignKey' => 'grado_id'
		),
		'CatalogoFamilia' => array(
			'className' => 'CatalogoFamilia',
			'foreignKey' => 'cat_fam_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ArticulosPrecio' => array(
			'className' => 'ArticulosPrecio',
			'foreignKey' => 'articulo_id',
			'dependent' => false
		),
		'DetalleOrdenCompra' => array(
			'className' => 'DetalleOrdenCompra',
			'foreignKey' => 'articulo_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["imagen"])) $atributos["imagen"] = "NULL";
		if (empty($atributos["colegio_id"])) $atributos["colegio_id"] = "cero";
		if (empty($atributos["nivele_id"])) $atributos["nivele_id"] = "cero";
		if (empty($atributos["grado_id"])) $atributos["grado_id"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["imagen"] == "NULL") $atributos["imagen"] = "";
		if ($atributos["colegio_id"] == "cero") $atributos["colegio_id"] = "0";
		if ($atributos["nivele_id"] == "cero") $atributos["nivele_id"] = "0";
		if ($atributos["grado_id"] == "cero") $atributos["grado_id"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function todosArticulos($condiciones)
	{
		$articulos = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'identificador', 'descripcion', 'imagen', 'colegio_id', 'nivele_id', 'grado_id', 'cat_fam_id'
			),
			'order' => array('identificador')
		));

		return $articulos;
	}
	

//=========================================================================


	public function guardarEnBDD($data, $accion)
	{
		$data["Articulo"] = $this->llenarAtributosVacios($data["Articulo"]);

		$valido = $this->validarInputs($data["Articulo"]);

		if ($valido != 1)
			return $valido;
		else
		{	
			$data["Articulo"] = $this->quitarNulos($data["Articulo"]);

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.articulos
						(identificador, descripcion, imagen, colegio_id, nivele_id, grado_id, cat_fam_id)
					VALUES (
						'".$data["Articulo"]['identificador']."',
						'".$data["Articulo"]['descripcion']."',
						'".$data["Articulo"]['imagen']."',
						".$data["Articulo"]['colegio_id'].",
						".$data["Articulo"]['nivele_id'].",
						".$data["Articulo"]['grado_id'].",
						".$data["Articulo"]['cat_fam_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.articulos
					SET identificador = '".$data["Articulo"]['identificador']."',
						descripcion = '".$data["Articulo"]['descripcion']."',
						imagen = '".$data["Articulo"]['imagen']."',
						colegio_id = ".$data["Articulo"]['colegio_id'].",
						nivele_id = ".$data["Articulo"]['nivele_id'].",
						grado_id = ".$data["Articulo"]['grado_id'].",
						cat_fam_id = ".$data["Articulo"]['cat_fam_id']."
					WHERE id = ".$data["Articulo"]['id']."
				");
			}

			if ($queryExitosa)
			{
				$ArticulosPrecio = new ArticulosPrecio();

				if (empty($data["Articulo"]["id"]))
				{
					$articulo_creado = $this->find("first", array(
						'conditions' => array(
							'identificador' => $data["Articulo"]['identificador'],
							'descripcion' => $data["Articulo"]['descripcion']
						),
						'fields' => array('id')
					));
					$articulo_creado = $articulo_creado["Articulo"]["id"];
				}
				else
					$articulo_creado = $data["Articulo"]["id"];


				if (!empty($data["ArticulosPrecio"]))
				{
					$data["ArticulosPrecio"]["articulo_id"] = $articulo_creado;

					$guardado = $ArticulosPrecio->guardarEnBDD($data["ArticulosPrecio"], $accion);

					if ($guardado != 1)
						return $guardado;
					else
						return 1;
				}
				return 1;
			}
			else
				return 'No se pudo guardar.';
		}
	}

}
