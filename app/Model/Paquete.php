<?php
App::uses('AppModel', 'Model');
App::uses('ArticulosPaquete', 'Model');
App::uses('PedidosPaquete', 'Model');
App::uses('Colegio', 'Model');
App::uses('Nivele', 'Model');
App::uses('Pedido', 'Model');
App::uses('Grado', 'Model');
App::uses('Hijo', 'Model');
/**
 * Paquete Model
 *
 */
class Paquete extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ArticulosPaquete' => array(
			'className' => 'ArticulosPaquete',
			'foreignKey' => 'paquete_id',
			'dependent' => false
		),
		'PedidosPaquete' => array(
			'className' => 'PedidosPaquete',
			'foreignKey' => 'paquete_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["identificador"])) $atributos["identificador"] = "NULL";
		if (empty($atributos["descripcion"])) $atributos["descripcion"] = "NULL";
		if (empty($atributos["imagen"])) $atributos["imagen"] = "NULL";
		if (empty($atributos["ciclo"])) $atributos["ciclo"] = "NULL";
		if (empty($atributos["colegio_id"])) $atributos["colegio_id"] = "cero";
		if (empty($atributos["nivele_id"])) $atributos["nivele_id"] = "cero";
		if (empty($atributos["grado_id"])) $atributos["grado_id"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["identificador"] == "NULL") $atributos["identificador"] = "";
		if ($atributos["descripcion"] == "NULL") $atributos["descripcion"] = "";
		if ($atributos["imagen"] == "NULL") $atributos["imagen"] = "";
		if ($atributos["ciclo"] == "NULL") $atributos["ciclo"] = "";
		if ($atributos["colegio_id"] == "cero") $atributos["colegio_id"] = "0";
		if ($atributos["nivele_id"] == "cero") $atributos["nivele_id"] = "0";
		if ($atributos["grado_id"] == "cero") $atributos["grado_id"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerPaquetes($condiciones)
	{
		$paquetes = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'identificador', 'descripcion', 'imagen', 'ciclo', 'colegio_id', 'nivele_id', 'grado_id', 'estatus'
			),
			'order' => array(
				'identificador' => 'asc'
			)
		));

		return $paquetes;
	}
	

//=========================================================================


	public function traerNombresCNG($atributos)
	{
		$Colegio = new Colegio();
		$Nivele = new Nivele();
		$Grado = new Grado();

		$condiciones = array('id' => $atributos["colegio_id"]);
		$colegio_nombre = $Colegio->todosColegios($condiciones);
		if ($colegio_nombre)
			$atributos["colegio_nombre"] = $colegio_nombre[0]["Colegio"]["nombre"];
		else
			$atributos["colegio_nombre"] = "Todos";

		$nivele_nombre = $Nivele->nivelEspecifico($atributos["nivele_id"]);
		if ($nivele_nombre)
			$atributos["nivele_nombre"] = $nivele_nombre["CatalogoNivele"]["nombre"];
		else
			$atributos["nivele_nombre"] = "Todos";

		$grado_nombre = $Grado->gradoEspecifico($atributos["grado_id"]);
		if ($grado_nombre)
			$atributos["grado_nombre"] = $grado_nombre["CatalogoGrado"]["nombre"];
		else
			$atributos["grado_nombre"] = "Todos";

		return $atributos;
	}
	

//=========================================================================


	public function traerSintesis($ciclo, $colegio_id)
	{
		$PedidosPaquete = new PedidosPaquete();
		$Colegio = new Colegio();
		$Nivele = new Nivele();
		$Pedido = new Pedido();
		$Grado = new Grado();

		$sintesis = array();

		$colegio_nombre = $Colegio->find('first', array(
			'conditions' => array('id' => $colegio_id),
			'fields' => array('nombre')
		));
		$colegio_nombre = $colegio_nombre["Colegio"]["nombre"];

		$niveles = $Nivele->todosNiveles($colegio_id);

		foreach ($niveles as $key => $nivel)
		{
			$grados = $Grado->todosGrados($nivel["Nivele"]["id"]);
			$niveles[$key]["Grados"] = $grados;
		}


		foreach ($niveles as $keyN => $nivel)
		{
			foreach ($nivel["Grados"] as $keyG => $grado)
			{
				$condiciones = array(
					'CicloHijo.colegio_id' => $colegio_id,
					'CicloHijo.nivele_id'  => $nivel["Nivele"]["id"],
					'CicloHijo.grado_id'   => $grado["Grado"]["id"],
					'CicloHijo.ciclo'      => $ciclo
				);

				$pedidos = $Pedido->pedidosYciclos($condiciones);

				foreach ($pedidos as $keyP => $pedido)
				{
					$ped_paq = $PedidosPaquete->traerPedidosPaquetes(array('Pedido.id' => $pedido["Pedido"]["id"]));

					foreach ($ped_paq as $key => $paq)
					{
						if (@$sintesis[$paq["Paquete"]["id"]])
						{
							$cobrados = $sintesis[$paq["Paquete"]["id"]]["cobrados"];
							$no_cobrados = $sintesis[$paq["Paquete"]["id"]]["no_cobrados"];
							$cancelados = $sintesis[$paq["Paquete"]["id"]]["cancelados"];
						}
						else
						{
							$cobrados = 0;
							$no_cobrados = 0;
							$cancelados = 0;
						}

						for ($cantidad=0; $cantidad < $paq["PedidosPaquete"]["cantidad"]; $cantidad++)
						{
							if ($paq["Pedido"]["estatus"])
								$cobrados++;
							else
								if ($paq["Pedido"]["fecha_cancelado"])
									$cancelados++;
								else
									$no_cobrados++;
						}
							

						$datos = array(
							'colegio'     => $colegio_nombre,
							'nivele'      => $nivel["CatalogoNivele"]["nombre"],
							'grado'       => $grado["CatalogoGrado"]["nombre"],
							'paquete_id'  => $paq["Paquete"]["identificador"],
							'descripcion' => $paq["Paquete"]["descripcion"],
							'cobrados'    => $cobrados,
							'no_cobrados' => $no_cobrados,
							'cancelados'  => $cancelados,
							'todos'       => $cobrados + $no_cobrados + $cancelados
						);

						$sintesis[$paq["Paquete"]["id"]] = $datos;
					}
				}
			}
		}
			
		return $sintesis;
	}
	

//=========================================================================


	public function traerDetalle($ciclo, $colegio_id, $estatus_select)
	{
		$PedidosPaquete = new PedidosPaquete();
		$Colegio = new Colegio();
		$Nivele = new Nivele();
		$Pedido = new Pedido();
		$Grado = new Grado();
		$Hijo = new Hijo();

		$detalle = array();

		$colegio_nombre = $Colegio->find('first', array(
			'conditions' => array('id' => $colegio_id),
			'fields' => array('nombre')
		));
		$colegio_nombre = $colegio_nombre["Colegio"]["nombre"];

		$niveles = $Nivele->todosNiveles($colegio_id);

		foreach ($niveles as $key => $nivel)
		{
			$grados = $Grado->todosGrados($nivel["Nivele"]["id"]);
			$niveles[$key]["Grados"] = $grados;
		}


		foreach ($niveles as $keyN => $nivel)
		{
			foreach ($nivel["Grados"] as $keyG => $grado)
			{
				$condiciones = array(
					'CicloHijo.colegio_id' => $colegio_id,
					'CicloHijo.nivele_id'  => $nivel["Nivele"]["id"],
					'CicloHijo.grado_id'   => $grado["Grado"]["id"],
					'CicloHijo.ciclo'      => $ciclo
				);

				switch ($estatus_select)
				{
					case 'no_cobrado':
						$condiciones["Pedido.estatus"] = 0;
						$condiciones["Pedido.fecha_cancelado"] = '';
						break;

					case 'cobrado':
						$condiciones["Pedido.estatus"] = 1;
						break;

					case 'cancelado':
						$condiciones["Pedido.fecha_cancelado <>"] = '';
						break;
				}

				$pedidos = $Pedido->pedidosYciclos($condiciones);

				foreach ($pedidos as $keyP => $pedido)
				{
					$hijo = $Hijo->traerHijos(array('Hijo.id' => $pedido["CicloHijo"]["hijo_id"]));

					$ped_paq = $PedidosPaquete->traerPedidosPaquetes(array('Pedido.id' => $pedido["Pedido"]["id"]));

					foreach ($ped_paq as $key => $paq)
					{
						for ($cantidad=0; $cantidad < $paq["PedidosPaquete"]["cantidad"]; $cantidad++)
						{ 
							if ($paq["Pedido"]["estatus"])
								$estatus = "Cobrado";
							else
								if ($paq["Pedido"]["fecha_cancelado"])
									$estatus = "Cancelado";
								else
									$estatus = "No Cobrado";

							$datos = array(
								'colegio'     => $colegio_nombre,
								'nivele'      => $nivel["CatalogoNivele"]["nombre"],
								'grado'       => $grado["CatalogoGrado"]["nombre"],
								'alumno'      => $hijo[0]["Hijo"]["nombre"],
								'a_paterno'   => $hijo[0]["Hijo"]["a_paterno"],
								'a_materno'   => $hijo[0]["Hijo"]["a_materno"],
								'pedido'      => $paq["Pedido"]["id"],
								'paquete_id'  => $paq["Paquete"]["identificador"],
								'descripcion' => $paq["Paquete"]["descripcion"],
								'factura'     => $paq["Pedido"]["fecha_facturado"],
								'estatus'     => $estatus,
								'importe'     => $paq["PedidosPaquete"]["importe"]
							);

							array_push($detalle, $datos);
						}
					}
				}
			}
		}
			
		return $detalle;
	}
	

//=========================================================================


	public function guardarEnBDD($data, $accion)
	{
		$data["Paquete"] = $this->llenarAtributosVacios($data["Paquete"]);

		$valido = $this->validarInputs($data["Paquete"]);

		if ($valido != 1)
			return $valido;
		else
		{
			$data["Paquete"] = $this->quitarNulos($data["Paquete"]);

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.paquetes
						(identificador, descripcion, imagen, ciclo, colegio_id, nivele_id, grado_id, estatus)
					VALUES (
						'".$data["Paquete"]['identificador']."',
						'".$data["Paquete"]['descripcion']."',
						'".$data["Paquete"]['imagen']."',
						'".$data["Paquete"]['ciclo']."',
						".$data["Paquete"]['colegio_id'].",
						".$data["Paquete"]['nivele_id'].",
						".$data["Paquete"]['grado_id'].",
						1
					)
				");
			}

			if ($accion == "editar")
			{
				$this->borrarHijos($data["Paquete"]['id']);

				$queryExitosa = $this->query("
					UPDATE CCM.paquetes
					SET identificador = '".$data["Paquete"]['identificador']."',
						descripcion = '".$data["Paquete"]['descripcion']."',
						imagen = '".$data["Paquete"]['imagen']."',
						colegio_id = ".$data["Paquete"]['colegio_id'].",
						nivele_id = ".$data["Paquete"]['nivele_id'].",
						grado_id = ".$data["Paquete"]['grado_id']."
					WHERE id = ".$data["Paquete"]['id']."
				");
			}

			if ($queryExitosa)
			{
				$ArticulosPaquete = new ArticulosPaquete();

				if (empty($data["Paquete"]["id"]))
				{
					$paquete_creado = $this->find("first", array(
						'conditions' => array(
							'identificador' => $data['Paquete']['identificador'],
							'descripcion' => $data['Paquete']['descripcion']
						),
						'fields' => array('id')
					));
					$paquete_creado = $paquete_creado["Paquete"]["id"];
				}
				else
					$paquete_creado = $data["Paquete"]["id"];


				$datos_art_paq["paquete_id"] = $paquete_creado;

				if (!empty($data["ArticulosPaquete"]))
				foreach ($data["ArticulosPaquete"] as $key => $art_paq)
				{
					if (!empty($art_paq["identificador"]) &&
						!empty($art_paq["cantidad"]) &&
						!empty($art_paq["precio_publico"]))
					{
						$datos_art_paq["articulo_id"] = $art_paq["id"];
						$datos_art_paq["precio_publico"] = $art_paq["precio_publico"];
						$datos_art_paq["cantidad"] = $art_paq["cantidad"];

						
						$guardado = $ArticulosPaquete->guardarEnBDD($datos_art_paq, "agregar");
					}
				}

				return 1;
			}
			else
				return 'No se pudo guardar.';
		}
	}
	

//=========================================================================


	private function borrarHijos($paquete_id)
	{
		$ArticulosPaquete = new ArticulosPaquete();

		$art_paq = $ArticulosPaquete->find("list", array(
			'recursive' => 0,
			'conditions' => array('ArticulosPaquete.paquete_id' => $paquete_id),
			'fields' => array('ArticulosPaquete.id', 'ArticulosPaquete.id')
		));

		foreach ($art_paq as $keyP => $art_paq_id)
		{
			$ArticulosPaquete->query("
				DELETE FROM CCM.articulos_paquetes
				WHERE id = $art_paq_id
			");
		}
	}

}
