<?php
App::uses('AppModel', 'Model');
App::uses('ArticulosPaquete', 'Model');
App::uses('FacturacionDato', 'Model');
App::uses('PedidosPaquete', 'Model');
App::uses('Asociado', 'Model');
App::uses('Colegio', 'Model');
App::uses('Nivele', 'Model');
App::uses('Grado', 'Model');
App::uses('Hijo', 'Model');
App::uses('Pago', 'Model');
/**
 * Pedido Model
 *
 */
class Pedido extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CicloHijo' => array(
			'className' => 'CicloHijo',
			'foreignKey' => 'ciclo_hijo_id'
		),
		'Asociado' => array(
			'className' => 'Asociado',
			'foreignKey' => 'padre_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PedidosPaquete' => array(
			'className' => 'PedidosPaquete',
			'foreignKey' => 'pedido_id',
			'dependent' => false
		),
		'Pago' => array(
			'className' => 'Pago',
			'foreignKey' => 'pedido_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["importe"]))          $atributos["importe"]          = "cero";
		if (empty($atributos["fecha_pedido"]))     $atributos["fecha_pedido"]     = "NULL";
		if (empty($atributos["fecha_cancelado"]))  $atributos["fecha_cancelado"]  = "NULL";
		if (empty($atributos["fecha_facturado"]))  $atributos["fecha_facturado"]  = "NULL";
		if (empty($atributos["fecha_modificado"])) $atributos["fecha_modificado"] = "NULL";
		if (empty($atributos["padre_id"]))         $atributos["padre_id"]         = "cero";
		if (empty($atributos["pdf_pedido"]))       $atributos["pdf_pedido"]       = "NULL";
		if (empty($atributos["pdf_factura"]))      $atributos["pdf_factura"]      = "NULL";
		if (empty($atributos["ciclo_hijo_id"]))    $atributos["ciclo_hijo_id"]    = "cero";
		if (empty($atributos["estatus"]))          $atributos["estatus"]          = "cero";
		if (empty($atributos["xml"]))              $atributos["xml"]              = "NULL";
		if (empty($atributos["uuid"]))             $atributos["uuid"]             = "NULL";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["importe"] == "cero")          $atributos["importe"]          = "0";
		if ($atributos["fecha_pedido"] == "NULL")     $atributos["fecha_pedido"]     = "";
		if ($atributos["fecha_cancelado"] == "NULL")  $atributos["fecha_cancelado"]  = "";
		if ($atributos["fecha_facturado"] == "NULL")  $atributos["fecha_facturado"]  = "";
		if ($atributos["fecha_modificado"] == "NULL") $atributos["fecha_modificado"] = "";
		if ($atributos["padre_id"] == "cero")         $atributos["padre_id"]         = "0";
		if ($atributos["pdf_pedido"] == "NULL")       $atributos["pdf_pedido"]       = "";
		if ($atributos["pdf_factura"] == "NULL")      $atributos["pdf_factura"]      = "";
		if ($atributos["ciclo_hijo_id"] == "cero")    $atributos["ciclo_hijo_id"]    = "0";
		if ($atributos["estatus"] == "cero")          $atributos["estatus"]          = "0";
		if ($atributos["xml"] == "NULL")              $atributos["xml"]              = "";
		if ($atributos["uuid"] == "NULL")             $atributos["uuid"]             = "";

		return $atributos;
	}
	

//=========================================================================


	public function traerPedidos($condiciones)
	{
		$pedidos = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'importe', 'fecha_pedido', 'fecha_cancelado', 'fecha_facturado', 'fecha_modificado', 'padre_id', 'pdf_pedido', 'pdf_factura', 'ciclo_hijo_id', 'xml', 'estatus', 'uuid'
			),
			'order' => array('fecha_pedido', 'id')
		));

		return $pedidos;
	}
	

//=========================================================================


	public function traerPedidosXciclo($condiciones)
	{
		$Asociado = new Asociado();
		$Hijo = new Hijo();

		$pedidos = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Pedido.id', 'Pedido.importe', 'Pedido.fecha_pedido', 'Pedido.fecha_cancelado', 'Pedido.fecha_facturado', 'Pedido.fecha_modificado', 'Pedido.padre_id', 'Pedido.estatus', 'CicloHijo.id', 'CicloHijo.hijo_id', 'CicloHijo.ciclo', 'CicloHijo.colegio_id', 'CicloHijo.nivele_id', 'CicloHijo.grado_id'
			),
			'order' => array('Pedido.id')
		));

		foreach ($pedidos as $key => $pedido)
		{
			$hijo = $Hijo->traerHijos(array('Hijo.id' => $pedido["CicloHijo"]["hijo_id"]));
			$hijo_nombre = $hijo[0]["Hijo"]["nombre"]." ".$hijo[0]["Hijo"]["a_paterno"]." ".$hijo[0]["Hijo"]["a_materno"];
			$colegio_nombre = $hijo[0]["Hijo"]["colegio"];

			$padre = $Asociado->traerAsociados(array('id' => $pedido["Pedido"]["padre_id"]));
			$padre = $padre[0]["Asociado"];
			$padre_nombre = $padre["nombre"]." ".$padre["a_paterno"]." ".$padre["a_materno"];

			$pedidos[$key]["CicloHijo"]["padre_nombre"]   = $padre_nombre;
			$pedidos[$key]["CicloHijo"]["hijo_nombre"]    = $hijo_nombre;
			$pedidos[$key]["CicloHijo"]["colegio_nombre"] = $colegio_nombre;
		}

		return $pedidos;
	}
	

//=========================================================================


	public function pedidosYciclos($condiciones)
	{
		$pedidos = $this->find('all', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'Pedido.id', 'Pedido.importe', 'Pedido.fecha_pedido', 'Pedido.fecha_cancelado', 'Pedido.fecha_facturado', 'Pedido.fecha_modificado', 'Pedido.padre_id', 'Pedido.estatus', 'CicloHijo.id', 'CicloHijo.hijo_id', 'CicloHijo.ciclo', 'CicloHijo.colegio_id', 'CicloHijo.nivele_id', 'CicloHijo.grado_id'
			),
			'order' => array('Pedido.id')
		));

		return $pedidos;
	}
	

//=========================================================================


	public function traerDetalle($ciclo, $colegio_id, $estatus_select)
	{
		$Nivele = new Nivele();
		$Grado = new Grado();
		$Hijo = new Hijo();
		$Pago = new Pago();

		$detalle = array();

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

				$pedidos = $this->pedidosYciclos($condiciones);

				foreach ($pedidos as $keyP => $pedido)
				{
					$hijo = $Hijo->traerHijos(array('Hijo.id' => $pedido["CicloHijo"]["hijo_id"]));
					$hijo_nombre = $hijo[0]["Hijo"]["nombre"]." ".$hijo[0]["Hijo"]["a_paterno"]." ".$hijo[0]["Hijo"]["a_materno"];
					$colegio_nombre = $hijo[0]["Hijo"]["colegio"];


					if ($pedido["Pedido"]["fecha_cancelado"])
						$estatus = "Cancelado";
					else
						if (!$pedido["Pedido"]["estatus"])
							$estatus = "No Cobrado";
						else
							$estatus = "Cobrado";


					$pagos = $Pago->traerPagos(array('Pedido.id' => $pedido["Pedido"]["id"]));
					$formas = array();
					$forma_pago = "";
					foreach ($pagos as $key => $pago)
						array_push($formas, $pago["Pago"]["forma_pago"]);
					$formas = array_unique($formas);
					foreach ($formas as $key => $forma)
						$forma_pago.= $forma.", ";
					$forma_pago = substr($forma_pago, 0, -2);


					$datos = array(
						'padre_id'   => $pedido["Pedido"]["padre_id"],
						'colegio'    => $colegio_nombre,
						'nivele'     => $nivel["CatalogoNivele"]["nombre"],
						'grado'      => $grado["CatalogoGrado"]["nombre"],
						'alumno'     => $hijo_nombre,
						'pedido'     => $pedido["Pedido"]["id"],
						'factura'    => $pedido["Pedido"]["fecha_facturado"],
						'estatus'    => $estatus,
						'importe'    => $pedido["Pedido"]["importe"],
						'forma_pago' => $forma_pago
					);

					array_push($detalle, $datos);
				}
			}
		}
				
		return $detalle;
	}
	

//=========================================================================


	public function traerFacturas($ciclo, $colegio_id)
	{
		$ArticulosPaquete = new ArticulosPaquete();
		$FacturacionDato = new FacturacionDato();
		$PedidosPaquete = new PedidosPaquete();
		$Asociado = new Asociado();
		$Colegio = new Colegio();
		$Nivele = new Nivele();
		$Grado = new Grado();
		$Hijo = new Hijo();
		$Pago = new Pago();

		$facturas = array();

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
					'CicloHijo.ciclo'      => $ciclo,
					'Pedido.estatus'       => 1
				);

				$condiciones["Pedido.fecha_facturado <>"] = '';

				$pedidos = $this->pedidosYciclos($condiciones);

				foreach ($pedidos as $keyP => $pedido)
				{
					$datos_factura = explode(" - ", $pedido["Pedido"]["fecha_facturado"]);

					$hijo = $Hijo->traerHijos(array('Hijo.id' => $pedido["CicloHijo"]["hijo_id"]));
					$hijo_nombre = $hijo[0]["Hijo"]["nombre"]." ".$hijo[0]["Hijo"]["a_paterno"]." ".$hijo[0]["Hijo"]["a_materno"];

					$padre = $this->Asociado->traerAsociados(array('id' => $pedido["Pedido"]["padre_id"]));
					$padre = $padre[0]["Asociado"];
					$padre_nombre = $padre["nombre"]." ".$padre["a_paterno"]." ".$padre["a_materno"];

					$datos_facturacion = $FacturacionDato->traerDatos(array('asociado_id' => $pedido["Pedido"]["padre_id"]));
					$datos_facturacion = $datos_facturacion[0]["FacturacionDato"];

					$paquetes = $PedidosPaquete->traerPedidosPaquetes(array('pedido_id' => $pedido["Pedido"]["id"]));

					$total_iva = 0;
					$total_importe = 0;
					foreach ($paquetes as $keyP => $paquete)
					{
						$paquete_id = $paquete["Paquete"]["id"];
						$condiciones = array('Paquete.id' => $paquete_id);
						$articulos = $ArticulosPaquete->traerArticulosEnPaquete($condiciones);

						foreach ($articulos as $keyA => $articulo)
						{
							$articulo_id = $articulo["Articulo"]["id"];
							$iva = $articulo["Articulo"]["iva"] / 100;
							$cantidad_articulo = $articulo["ArticulosPaquete"]["cantidad"] *
												 $paquete["PedidosPaquete"]["cantidad"];
							$precio_unitario = $articulo["ArticulosPaquete"]["precio_publico"];
							$precio_total = $cantidad_articulo * $precio_unitario;
							$precio_siva = $precio_total / (1 + $iva);
							$precio_iva = $precio_siva * $iva;

							$total_iva+= $precio_iva;
							$total_importe+= $precio_siva;
						}
					}

					$datos = array(
						'colegio'      => $colegio_nombre,
						'pedido'       => $pedido["Pedido"]["id"],
						'factura'      => @$datos_factura[1],
						'razon_social' => $datos_facturacion["razon_social"],
						'rfc'          => $datos_facturacion["rfc"],
						'fecha'        => $datos_factura[0],
						'alumno'       => $hijo_nombre,
						'importe'      => number_format($total_importe, 2),
						'iva'          => number_format($total_iva, 2),
						'total'        => number_format($pedido["Pedido"]["importe"], 2)
					);

					array_push($facturas, $datos);
				}
			}
		}

		return $facturas;
	}
	

//=========================================================================


	public function traerSintesis($ciclo, $colegio_id)
	{
		$Colegio = new Colegio();
		$Nivele = new Nivele();
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

				$condiciones["Pedido.estatus"] = 1;
				$cobrados = $this->find('count', array(
					'recursive' => 0,
					'conditions' => $condiciones,
					'fields' => array('Pedido.id')
				));

				$condiciones["Pedido.estatus"] = 0;
				$condiciones["Pedido.fecha_cancelado"] = '';
				$no_cobrados = $this->find('count', array(
					'recursive' => 0,
					'conditions' => $condiciones,
					'fields' => array('Pedido.id')
				));

				unset($condiciones["Pedido.estatus"]);
				unset($condiciones["Pedido.fecha_cancelado"]);
				$condiciones["Pedido.fecha_cancelado <>"] = '';
				$cancelados = $this->find('count', array(
					'recursive' => 0,
					'conditions' => $condiciones,
					'fields' => array('Pedido.id')
				));

				$datos = array(
					'colegio'     => $colegio_nombre,
					'nivele'      => $nivel["CatalogoNivele"]["nombre"],
					'grado'       => $grado["CatalogoGrado"]["nombre"],
					'cobrados'    => $cobrados,
					'no_cobrados' => $no_cobrados,
					'cancelados'  => $cancelados,
					'todos'       => $cobrados + $no_cobrados + $cancelados
				);

				array_push($sintesis, $datos);
			}
		}
				
		return $sintesis;
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
					INSERT INTO CCM.pedidos
						(importe, fecha_pedido, fecha_cancelado, fecha_facturado, fecha_modificado, padre_id, pdf_pedido, pdf_factura, ciclo_hijo_id, estatus, xml, uuid)
					VALUES (
						".$atributos['importe'].",
						'".$atributos['fecha_pedido']."',
						'".$atributos['fecha_cancelado']."',
						'".$atributos['fecha_facturado']."',
						'".$atributos['fecha_modificado']."',
						".$atributos['padre_id'].",
						'".$atributos['pdf_pedido']."',
						'".$atributos['pdf_factura']."',
						".$atributos['ciclo_hijo_id'].",
						".$atributos['estatus'].",
						'".$atributos['xml']."',
						'".$atributos['uuid']."'
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.pedidos
					SET importe = ".$atributos['importe'].",
						fecha_pedido = '".$atributos['fecha_pedido']."',
						fecha_cancelado = '".$atributos['fecha_cancelado']."',
						fecha_facturado = '".$atributos['fecha_facturado']."',
						fecha_modificado = '".$atributos['fecha_modificado']."',
						padre_id = ".$atributos['padre_id'].",
						pdf_pedido = '".$atributos['pdf_pedido']."',
						pdf_factura = '".$atributos['pdf_factura']."',
						ciclo_hijo_id = ".$atributos['ciclo_hijo_id'].",
						xml = '".$atributos['xml']."',
						uuid = '".$atributos['uuid']."',
						estatus = ".$atributos['estatus']."
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
