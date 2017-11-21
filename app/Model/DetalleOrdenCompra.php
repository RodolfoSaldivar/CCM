<?php
App::uses('AppModel', 'Model');
App::uses('Pedido', 'Model');
App::uses('Articulo', 'Model');
App::uses('PedidosPaquete', 'Model');
App::uses('ArticulosPaquete', 'Model');
App::uses('ArticulosPrecio', 'Model');
App::uses('CatalogoFamilia', 'Model');
/**
 * DetalleOrdenCompra Model
 *
 */ 
class DetalleOrdenCompra extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/*
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Articulo' => array(
			'className' => 'Articulo',
			'foreignKey' => 'articulo_id'
		),
		'OrdenCompra' => array(
			'className' => 'OrdenCompra',
			'foreignKey' => 'orden_compra_id'
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["cantidad"])) $atributos["cantidad"] = "cero";
		if (empty($atributos["devueltos"])) $atributos["devueltos"] = "cero";
		if (empty($atributos["inventario"])) $atributos["inventario"] = "cero";
		if (empty($atributos["pu_colegio"])) $atributos["pu_colegio"] = "cero";
		if (empty($atributos["f_cantidad"])) $atributos["f_cantidad"] = "cero";
		if (empty($atributos["f_pu_venta"])) $atributos["f_pu_venta"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["cantidad"] == "cero") $atributos["cantidad"] = "0";
		if ($atributos["devueltos"] == "cero") $atributos["devueltos"] = "0";
		if ($atributos["inventario"] == "cero") $atributos["inventario"] = "0";
		if ($atributos["pu_colegio"] == "cero") $atributos["pu_colegio"] = "0";
		if ($atributos["f_cantidad"] == "cero") $atributos["f_cantidad"] = "0";
		if ($atributos["f_pu_venta"] == "cero") $atributos["f_pu_venta"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerInfo($condiciones)
	{
		$orden = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $condiciones,
			'fields' => array(
				'DetalleOrdenCompra.id', 'DetalleOrdenCompra.cantidad', 'DetalleOrdenCompra.devueltos', 'DetalleOrdenCompra.inventario', 'DetalleOrdenCompra.pu_colegio', 'DetalleOrdenCompra.f_cantidad', 'DetalleOrdenCompra.f_pu_venta', 'DetalleOrdenCompra.articulo_id', 'DetalleOrdenCompra.orden_compra_id'
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
					INSERT INTO CCM.detalle_orden_compras
						(orden_compra_id, articulo_id, cantidad, devueltos, inventario, pu_colegio, f_cantidad, f_pu_venta)
					VALUES (
						".$atributos['orden_compra_id'].",
						".$atributos['articulo_id'].",
						".floatval(str_replace(',', '', $atributos['cantidad'])).",
						".floatval(str_replace(',', '', $atributos['devueltos'])).",
						".floatval(str_replace(',', '', $atributos['inventario'])).",
						".floatval(str_replace(',', '', $atributos['pu_colegio'])).",
						".floatval(str_replace(',', '', $atributos['f_cantidad'])).",
						".floatval(str_replace(',', '', $atributos['f_pu_venta']))."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.detalle_orden_compras
					SET cantidad = ".floatval(str_replace(',', '', $atributos['cantidad'])).",
						devueltos = ".floatval(str_replace(',', '', $atributos['devueltos'])).",
						inventario = ".floatval(str_replace(',', '', $atributos['inventario'])).",
						pu_colegio = ".floatval(str_replace(',', '', $atributos['pu_colegio'])).",
						f_cantidad = ".floatval(str_replace(',', '', $atributos['f_cantidad'])).",
						f_pu_venta = ".floatval(str_replace(',', '', $atributos['f_pu_venta']))."
					WHERE orden_compra_id = ".$atributos['orden_compra_id']."
						AND articulo_id = ".$atributos["articulo_id"]."
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
	

//=========================================================================


	public function articulosVendidos($orden_compra_id, $colegio_id, $ciclo, $accion)
	{
		$Pedido = new Pedido();
		$PedidosPaquete = new PedidosPaquete();
		$ArticulosPaquete = new ArticulosPaquete();

		$pedidos = $Pedido->pedidosYciclos(array(
			'CicloHijo.ciclo' => $ciclo,
			'CicloHijo.colegio_id' => $colegio_id,
			'Pedido.estatus' => 1
		));

		$articulos = array();
		foreach ($pedidos as $keyP => $pedido)
		{
			$ped_paq = $PedidosPaquete->traerPedidosPaquetes(array(
				'PedidosPaquete.pedido_id' => $pedido["Pedido"]["id"]
			));

			foreach ($ped_paq as $keyPP => $p_p)
			{
				$cantidad_pp = $p_p["PedidosPaquete"]["cantidad"];

				$art_paq = $ArticulosPaquete->traerArticulosEnPaquete(array(
					'Paquete.id' => $p_p["Paquete"]["id"]
				));

				foreach ($art_paq as $keyAP => $a_p)
				{
					// Hasta aqui se obtienen todos los articulos que se vendieron por paquete que se vendieron por pedido

					$articulo_id = $a_p["Articulo"]["id"];

					// Se obtienen los datos que ya estaban guardados
					$detalle_orden = $this->traerInfo(array(
						'articulo_id' => $articulo_id,
						'orden_compra_id' => $orden_compra_id
					));
					@$detalle_orden = $detalle_orden["DetalleOrdenCompra"];

					if (!$detalle_orden)
					{
						$detalle_orden["cantidad"] = 0;
						$detalle_orden["devueltos"] = 0;
						$detalle_orden["inventario"] = 0;
						$detalle_orden["pu_colegio"] = 0;
						$detalle_orden["f_cantidad"] = 0;
						$detalle_orden["f_pu_venta"] = 0;
					}

					// Si quiere actualizar significa que todo se hara 0
					if ($accion == "actualizar")
					{
						$articulos[$articulo_id] = array(
							'articulo_id' => $articulo_id,
							'orden_compra_id' => $orden_compra_id,
							'cantidad' => @$detalle_orden["cantidad"],
							'devueltos' => @$detalle_orden["devueltos"],
							'inventario' => @$detalle_orden["inventario"],
							'pu_colegio' => @$detalle_orden["pu_colegio"]
						);
					}

					// Si quiere ver se le mostrara la lista con los datos que ya tenia guardados
					if ($accion == "ver")
					{
						// Obtiene la info que estara en la tabla
						$cantidad_total = $a_p["ArticulosPaquete"]["cantidad"] * $cantidad_pp;
						$iva = $a_p["Articulo"]["iva"];
						if ($detalle_orden["cantidad"])
						{
							if (!$detalle_orden["pu_colegio"])
							{
								$po_importe = ($a_p["Articulo"]["precio_venta"] / (1 + $iva / 100)) * ($detalle_orden["cantidad"] - @$detalle_orden["devueltos"]);
							}
							else
							{
								$po_importe = ($detalle_orden["pu_colegio"] / (1 + $iva / 100)) * ($detalle_orden["cantidad"] - @$detalle_orden["devueltos"]);
							}
							
							$po_iva = $po_importe * ($iva / 100);
						}
						else
						{
							$po_importe = 0;
							$po_iva = 0;
						}
							
						$vt_importe = ($a_p["ArticulosPaquete"]["precio_publico"] / (1 + $iva / 100)) * $cantidad_total;
						$vt_iva = $vt_importe * ($iva / 100);

						// Solo los pedidos que fueron facturados
						if ($pedido["Pedido"]["fecha_facturado"])
						{
							$vf_cantidad = $cantidad_total;
							$vf_importe = ($a_p["ArticulosPaquete"]["precio_publico"] / (1 + $iva / 100)) * $cantidad_total;
							$vf_iva = $vf_importe * ($iva / 100);
							$vf_total = $vf_importe + $vf_iva;
						}
						else
						{
							$vf_cantidad = 0;
							$vf_importe = 0;
							$vf_iva = 0;
							$vf_total = 0;
						}

						if ($detalle_orden["pu_colegio"] == 0)
							$detalle_orden["pu_colegio"] = $a_p["Articulo"]["precio_venta"];

					
						$articulos[$articulo_id] = array(
							'orden_compra_id' => $orden_compra_id,
							'articulo_id' => $articulo_id,
							'identificador' => $a_p["Articulo"]["identificador"],
							'descripcion' => $a_p["Articulo"]["descripcion"],
							'iva' => $iva,
							'familia' => $a_p["Articulo"]["familia_nombre"],
							'po_cant' => @$detalle_orden["cantidad"],
							'po_dev' => @$detalle_orden["devueltos"],
							'po_inv' => @$detalle_orden["inventario"],
							'pu_colegio' => @$detalle_orden["pu_colegio"],
							'po_importe' => $po_importe,
							'po_iva' => $po_iva,
							'po_total' => $po_importe + $po_iva,
							'vt_cantidad' => @$articulos[$articulo_id]["vt_cantidad"] + $cantidad_total,
							'vt_importe' => @$articulos[$articulo_id]["vt_importe"] + $vt_importe,
							'vt_iva' => @$articulos[$articulo_id]["vt_iva"] + $vt_iva,
							'vt_total' => @$articulos[$articulo_id]["vt_total"] + $vt_importe + $vt_iva,
							'vf_cantidad' => @$articulos[$articulo_id]["vf_cantidad"] + $vf_cantidad,
							'vf_importe' => @$articulos[$articulo_id]["vf_importe"] + $vf_importe,
							'vf_iva' => @$articulos[$articulo_id]["vf_iva"] + $vf_iva,
							'vf_total' => @$articulos[$articulo_id]["vf_total"] + $vf_total,
							'f_cantidad' => @$detalle_orden["f_cantidad"],
							'f_pu_venta' => @$detalle_orden["f_pu_venta"],
							'm_f_cantidad' => @$detalle_orden["f_cantidad"],
							'm_f_pu_venta' => @$detalle_orden["f_pu_venta"]
						);
					}
				}
			}
		}

		$totales["po_cant_total"] = 0;
		$totales["po_dev_total"] = 0;
		$totales["po_inv_total"] = 0;
		$totales["po_venta_total"] = 0;
		$totales["po_importe_total"] = 0;
		$totales["po_iva_total"] = 0;
		$totales["po_total_total"] = 0;
		$totales["vt_cant_total"] = 0;
		$totales["vt_venta_total"] = 0;
		$totales["vt_importe_total"] = 0;
		$totales["vt_iva_total"] = 0;
		$totales["vt_total_total"] = 0;
		$totales["vf_cant_total"] = 0;
		$totales["vf_venta_total"] = 0;
		$totales["vf_importe_total"] = 0;
		$totales["vf_iva_total"] = 0;
		$totales["vf_total_total"] = 0;
		$totales["fp_cant_total"] = 0;
		$totales["fp_venta_total"] = 0;
		$totales["fp_importe_total"] = 0;
		$totales["fp_iva_total"] = 0;
		$totales["fp_total_total"] = 0;
		$totales["ajuste"] = 0;
		$totales["cant_siva"] = 0;
		$totales["individual"] = 0;
		$totales["resultado"] = 0;
		$totales["ajuste_manual"] = 0;
		$totales["importe_pdf"] = 0;

		foreach ($articulos as $key => $articulo)
		{
			if ($accion == "actualizar")
			{
				$existe = $this->find('first', array(
					'conditions' => array(
						'articulo_id' => $articulo["articulo_id"],
						'orden_compra_id' => $articulo["orden_compra_id"]
					),
					'fields' => 'id'
				));
				if ($existe)
					$this->guardarEnBDD($articulo, "editar");
				else
					$this->guardarEnBDD($articulo, "agregar");				
			}


			if ($accion == "ver")
			{
				if ($articulo["vt_cantidad"])
					$vt_venta = $articulo["vt_total"] / $articulo["vt_cantidad"];
				else
					$vt_venta = 0;

				if ($articulo["vf_cantidad"])
					$vf_venta = $articulo["vf_total"] / $articulo["vf_cantidad"];
				else
					$vf_venta = 0;

				$articulos[$key]["vt_venta"] = number_format($vt_venta, 2);
				$articulos[$key]["vf_venta"] = number_format($vf_venta, 2);

				if (!floatval($articulo["f_cantidad"]))
					$articulos[$key]["f_cantidad"] = 0;
				if (!floatval($articulo["f_pu_venta"]))
					$articulos[$key]["f_pu_venta"] = 0;

				if (!$articulos[$key]["f_pu_venta"])
				{
					$f_cantidad = $articulo["po_cant"] - $articulo["po_dev"] - $articulo["vf_cantidad"];

					if ($f_cantidad > 0)
						$f_pu_venta = (($articulo["po_cant"] - $articulo["po_dev"]) * $articulo["pu_colegio"] - $articulo["vf_cantidad"] * $vf_venta) / $f_cantidad;
					else
					{
						$f_cantidad = 0;
						$f_pu_venta = 0;
					}

					$articulos[$key]["f_cantidad"] = $f_cantidad;
					$articulos[$key]["f_pu_venta"] = number_format($f_pu_venta, 2);
				}

				$fp_importe = $articulos[$key]["f_cantidad"] * ($articulos[$key]["f_pu_venta"] / (1 + $articulo["iva"] / 100));
				$fp_iva = $fp_importe * ($articulo["iva"] / 100);
				$fp_total = $fp_importe + $fp_iva;
				$fp_importe = $fp_importe;
				$fp_iva = $fp_iva;

				$articulos[$key]["fp_importe"] = number_format($fp_importe, 2);
				$articulos[$key]["fp_iva"] = number_format($fp_iva, 2);
				$articulos[$key]["fp_total"] = number_format($fp_total, 2);

				$articulos[$key]["po_importe"] = number_format($articulo["po_importe"], 2);
				$articulos[$key]["po_iva"]     = number_format($articulo["po_iva"], 2);
				$articulos[$key]["vt_importe"] = number_format($articulo["vt_importe"], 2);
				$articulos[$key]["vt_iva"]     = number_format($articulo["vt_iva"], 2);
				$articulos[$key]["vf_importe"] = number_format($articulo["vf_importe"], 2);
				$articulos[$key]["vf_iva"]     = number_format($articulo["vf_iva"], 2);
				$articulos[$key]["vf_total"]   = number_format($articulo["vf_total"], 2);
			}

			$articulos[$key]["individual"] = 0;
			$articulos[$key]["resultado"] = 0;
			$articulos[$key]["quitar"] = 0;

			@$totales["po_cant_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_cant"]));
			@$totales["po_dev_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_dev"]));
			@$totales["po_inv_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_inv"]));
			@$totales["po_venta_total"]+= floatval(str_replace(',', '', $articulos[$key]["pu_colegio"]));
			@$totales["po_importe_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_importe"]));
			@$totales["po_iva_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_iva"]));
			@$totales["po_total_total"]+= floatval(str_replace(',', '', $articulos[$key]["po_total"]));

			@$totales["vt_cant_total"]+= floatval(str_replace(',', '', $articulos[$key]["vt_cantidad"]));
			@$totales["vt_venta_total"]+= floatval(str_replace(',', '', $articulos[$key]["vt_venta"]));
			@$totales["vt_importe_total"]+= floatval(str_replace(',', '', $articulos[$key]["vt_importe"]));
			@$totales["vt_iva_total"]+= floatval(str_replace(',', '', $articulos[$key]["vt_iva"]));
			@$totales["vt_total_total"]+= floatval(str_replace(',', '', $articulos[$key]["vt_total"]));

			@$totales["vf_cant_total"]+= floatval(str_replace(',', '', $articulos[$key]["vf_cantidad"]));
			@$totales["vf_venta_total"]+= floatval(str_replace(',', '', $articulos[$key]["vf_venta"]));
			@$totales["vf_importe_total"]+= floatval(str_replace(',', '', $articulos[$key]["vf_importe"]));
			@$totales["vf_iva_total"]+= floatval(str_replace(',', '', $articulos[$key]["vf_iva"]));
			@$totales["vf_total_total"]+= floatval(str_replace(',', '', $articulos[$key]["vf_total"]));

			@$totales["fp_cant_total"]+= floatval(str_replace(',', '', $articulos[$key]["f_cantidad"]));
			@$totales["fp_venta_total"]+= floatval(str_replace(',', '', $articulos[$key]["f_pu_venta"]));
			@$totales["fp_importe_total"]+= floatval(str_replace(',', '', $articulos[$key]["fp_importe"]));
			@$totales["fp_iva_total"]+= floatval(str_replace(',', '', $articulos[$key]["fp_iva"]));
			@$totales["fp_total_total"]+= floatval(str_replace(',', '', $articulos[$key]["fp_total"]));

			if (floatval(str_replace(',', '', @$articulos[$key]["po_total"])) < floatval(str_replace(',', '', @$articulos[$key]["vf_total"])))
			{
				@$totales["ajuste"]+= floatval(str_replace(',', '', $articulos[$key]["vf_total"])) - floatval(str_replace(',', '', $articulos[$key]["po_total"]));
			}

			if (@$articulos[$key]["fp_iva"] == "0.00")
			{
				@$totales["cant_siva"]+= floatval(str_replace(',', '', $articulos[$key]["f_cantidad"]));
			}
		}

		if (@$totales["cant_siva"] && @$totales["ajuste"])
		{
			@$totales["quitar"] = number_format(@$totales["ajuste"] / @$totales["cant_siva"], 2);
			@$totales["individual"] = 0;
		}
		else
		{
			@$totales["individual"] = 0;
			@$totales["resultado"] = 0;
			@$totales["ajuste_manual"] = number_format(@$totales["ajuste"], 2);
		}
		

		if ($accion == "ver" && @$totales["cant_siva"] && @$totales["ajuste"])
		{
			$ajuste_manual = 0;
			$individual = @$totales["quitar"];

			foreach ($articulos as $key => $articulo)
			{
				$f_cantidad = floatval(str_replace(',', '', $articulo["f_cantidad"]));
				$f_pu_venta = floatval(str_replace(',', '', $articulo["f_pu_venta"]));
				$fp_importe = floatval(str_replace(',', '', $articulo["fp_importe"]));
				$fp_iva = floatval(str_replace(',', '', $articulo["fp_iva"]));

				$por_quitar = $f_cantidad * $individual;
				$pu_ajustado = $f_pu_venta - $individual;

				if ($f_cantidad > 0 && $fp_iva == 0)
				{
					if ($por_quitar < $fp_importe)
					{
						$articulos[$key]["quitar"] = $individual;
						$articulos[$key]["individual"] = number_format($pu_ajustado, 2);
						$articulos[$key]["resultado"] = number_format($fp_importe - $por_quitar, 2);

						@$totales["individual"]+= $pu_ajustado;
						@$totales["resultado"]+= $fp_importe - $por_quitar;
						
						@$totales["fp_total_total"]-= $fp_importe;
						@$totales["fp_total_total"]+= $fp_importe - $por_quitar;
					}
					else
					{
						if ($f_pu_venta == 0)
						{
							$ajuste_manual+= $por_quitar + $f_cantidad;
							$articulos[$key]["f_pu_venta"] = "1.00";
						}
						else
						{
							$ajuste_manual+= $por_quitar;
						}
					}
				}
			}

			@$totales["ajuste_manual"] = number_format($ajuste_manual, 2);
		}

		foreach ($articulos as $key => $articulo)
		{
			if ($articulos[$key]["resultado"])
				$totales["importe_pdf"]+= floatval(str_replace(',', '', $articulos[$key]["resultado"]));
			else
			{
				$totales["importe_pdf"]+= floatval(str_replace(',', '', @$articulos[$key]["fp_importe"]));
				$articulos[$key]["pu_siva"] = floatval(str_replace(',', '', @$articulos[$key]["f_pu_venta"])) / (1 + floatval(str_replace(',', '', @$articulos[$key]["iva"])) / 100);
			}
		}

		return array($articulos, $totales);
	}
	

//=========================================================================


	public function porFamilia($orden_compra_id, $colegio_id, $ciclo)
	{
		$CatalogoFamilia = new CatalogoFamilia();

		$articulos = $this->articulosVendidos($orden_compra_id, $colegio_id, $ciclo, "ver");
		$totales = $articulos[1];
		$articulos = $articulos[0];

		$familias = $CatalogoFamilia->todasFamilias();

		$totales = array("ajuste_manual" => $totales["ajuste_manual"]);

		foreach ($familias as $key => $familia)
		{
			$nombres[$familia["CatalogoFamilia"]["nombre"]]["po"] = 0;
			$totales["po"] = 0;

			$nombres[$familia["CatalogoFamilia"]["nombre"]]["vt"] = 0;
			$totales["vt"] = 0;

			$nombres[$familia["CatalogoFamilia"]["nombre"]]["vf"] = 0;
			$totales["vf"] = 0;

			$nombres[$familia["CatalogoFamilia"]["nombre"]]["pf"] = 0;
			$totales["pf"] = 0;

		}

		foreach ($articulos as $keyA => $articulo)
		{
			$llave = $articulo["familia"];

			$po_importe = floatval(str_replace(',', '', $articulo["po_total"]));
			$nombres[$llave]["po"]+= $po_importe;
			$totales["po"]+= $po_importe;

			$vt_importe = floatval(str_replace(',', '', $articulo["vt_total"]));
			$nombres[$llave]["vt"]+= $vt_importe;
			$totales["vt"]+= $vt_importe;

			$vf_importe = floatval(str_replace(',', '', $articulo["vf_total"]));
			$nombres[$llave]["vf"]+= $vf_importe;
			$totales["vf"]+= $vf_importe;

			if ($articulo["resultado"])
				$pf_importe = floatval(str_replace(',', '', $articulo["resultado"]));
			else
				$pf_importe = floatval(str_replace(',', '', $articulo["fp_total"]));

			$nombres[$llave]["pf"]+= $pf_importe;
			$totales["pf"]+= $pf_importe;
		}

		return array($nombres, $totales);
	}

}