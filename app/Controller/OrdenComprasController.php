<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * OrdenCompras Controller
 *
 */
class OrdenComprasController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function detalle($id_url = 0)
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("DetalleOrdenCompra");


		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$submit_action = $data["action"];

			$condiciones = array(
				'colegio_id' => $data["OrdenCompra"]["colegio_id"],
				'ciclo' => $this->cicloActual(),
			);

			$orden_compra = $this->OrdenCompra->traerOrdenCompra($condiciones);

			if ($orden_compra)
			{
				$data["OrdenCompra"] = $orden_compra["OrdenCompra"];
				$accion = "editar";
			}
			else
				$accion = "agregar";

			$data["OrdenCompra"]["ciclo"] = $this->cicloActual();
			$data["OrdenCompra"]["fecha_modificado"] = $this->fechaHoy();

			$guardado = $this->OrdenCompra->guardarEnBDD($data["OrdenCompra"], $accion);

			if ($guardado != 1)
				$this->Session->setFlash($guardado);
			else
			{
				$orden_compra = $this->OrdenCompra->traerOrdenCompra($condiciones);
				$orden_compra_id = $orden_compra["OrdenCompra"]["id"];

				if ($submit_action == "actualizar" && !$orden_compra["OrdenCompra"]["fecha_facturado"])
				{
					$this->DetalleOrdenCompra->articulosVendidos($orden_compra_id, $data["OrdenCompra"]["colegio_id"], $this->cicloActual(), $submit_action);
				}

				$this->redirect("/orden_compras/detalle/$orden_compra_id");
			}
		}



		if ($id_url)
		{
			$orden_compra = $this->OrdenCompra->traerOrdenCompra(array('id' => $id_url));
			$colegio_id = $orden_compra["OrdenCompra"]["colegio_id"];
			$ajuste_iva = $orden_compra["OrdenCompra"]["ajuste_iva"];
			$fecha_facturado = $orden_compra["OrdenCompra"]["fecha_facturado"];

			$articulos = $this->DetalleOrdenCompra->articulosVendidos($id_url, $colegio_id, $this->cicloActual(), "ver");
			$totales = $articulos[1];
			$articulos = $articulos[0];
		}
		else
		{
			$articulos = array();
			$totales = array();
			$ajuste_iva = 0;
			$fecha_facturado = 0;
			$id_url = 0;
		}


		$colegios = $this->Colegio->todosColegios(array('id >' => '0'));

		$this->set("colegios", $colegios);
		@$this->set("colegio_id", $colegio_id);
		$this->set("ajuste_iva", $ajuste_iva);
		$this->set("fecha_facturado", $fecha_facturado);
		$this->set("articulos", $articulos);
		$this->set("totales", $totales);
		$this->set("orden_id", $id_url);
	}
	

//=========================================================================


	public function sintesis($id_url = 0)
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("DetalleOrdenCompra");


		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$submit_action = $data["action"];

			$data["OrdenCompra"]["ciclo"] = $this->cicloActual();
			$data["OrdenCompra"]["fecha_modificado"] = $this->fechaHoy();

			$condiciones = array(
				'colegio_id' => $data["OrdenCompra"]["colegio_id"],
				'ciclo' => $data["OrdenCompra"]["ciclo"],
			);

			$orden_compra = $this->OrdenCompra->traerOrdenCompra($condiciones);

			if (!$orden_compra)
			{
				$this->Session->setFlash("Favor de primero crear el detalle.");
				$this->redirect("/orden_compras/sintesis/");
			}
			else
			{
				$orden_compra_id = $orden_compra["OrdenCompra"]["id"];
				$this->redirect("/orden_compras/sintesis/$orden_compra_id");
			}
		}



		if ($id_url)
		{
			$orden_compra = $this->OrdenCompra->traerOrdenCompra(array('id' => $id_url));
			$colegio_id = $orden_compra["OrdenCompra"]["colegio_id"];

			$familias = $this->DetalleOrdenCompra->porFamilia($id_url, $colegio_id, $this->cicloActual());
			$totales = $familias[1];
			$familias = $familias[0];
		}
		else
		{
			$familias = array();
			$totales = array();
		}


		$colegios = $this->Colegio->todosColegios(array('id >' => '0'));

		$this->set("colegios", $colegios);
		@$this->set("colegio_id", $colegio_id);
		$this->set("familias", $familias);
		$this->set("totales", $totales);
	}


//=========================================================================


	public function guardar_detalle()
	{
		$this->layout = 'admin';

		$this->loadModel("DetalleOrdenCompra");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			if ($data["action"] == "guardar")
			{
				foreach ($data["Filas"] as $articulo_id => $atributos)
				{
					$this->DetalleOrdenCompra->guardarEnBDD($atributos, "editar");
				}

				$orden = $this->OrdenCompra->traerOrdenCompra(array('id' => $data["orden_id"]));
				$orden["OrdenCompra"]["ajuste_iva"] = $data["ajuste_iva"];
				$this->OrdenCompra->guardarEnBDD($orden["OrdenCompra"], "editar");

				$this->redirect($this->referer());
			}

			if ($data["action"] == "excel")
			{
				$data_enc = base64_encode(json_encode($data));
				$this->Session->write("data_cierre", $data_enc);
				$this->redirect("/dashboard/descargar_cierre");
			}
		}
    }


//=========================================================================


	public function facturas()
	{
		$this->loadModel("DetalleOrdenCompra");

		$this->layout = 'admin';

		$ordenes = $this->OrdenCompra->conInfoColegio(array(
			'OrdenCompra.ciclo' => $this->cicloActual()
		));

		foreach ($ordenes as $key => $orden)
		{
			$existe_detalle = $this->DetalleOrdenCompra->traerInfo(array(
				'OrdenCompra.id' => $orden["OrdenCompra"]["id"]
			));

			if (!$existe_detalle)
				unset($ordenes[$key]);
		}

		$this->set("ordenes", $ordenes);
    }


//=========================================================================


	public function hacer_pdf($orden_id = null)
	{
		$this->loadModel("DetalleOrdenCompra");

		$this->layout = 'vacio';

		$orden = $this->OrdenCompra->conInfoColegio(array(
			'OrdenCompra.id' => $orden_id
		));
		$colegio_id = $orden[0]["Colegio"]["id"];
		$ajuste_iva = $orden[0]["OrdenCompra"]["ajuste_iva"];

		$articulos = $this->DetalleOrdenCompra->articulosVendidos($orden_id, $colegio_id, $this->cicloActual(), "ver");

		$totales = $articulos[1];
		$articulos = $articulos[0];

		$this->set("fecha", $this->fechaHoy());
		$this->set("ajuste_iva", $ajuste_iva);
		$this->set("orden_id", $orden_id);
		$this->set("orden", $orden[0]);
		$this->set("totales", $totales);
		$this->set("articulos", $articulos);
    }
	

//=========================================================================


	public function ver_pdf($orden_id = null)
	{
		$this->layout = 'vacio';

		$codigo_html = $this->Session->read("codigo_html");
		$this->Session->delete("codigo_html");

		$this->set("codigo_html", $codigo_html);
		$this->set("orden_id", $orden_id);
	}
	

//=========================================================================


	public function facturar($orden_id = null)
	{
		$this->loadModel("DetalleOrdenCompra");

		$this->layout = 'admin';

		$orden = $this->OrdenCompra->conInfoColegio(array(
			'OrdenCompra.id' => $orden_id
		));
		$ajuste_iva = $orden[0]["OrdenCompra"]["ajuste_iva"];
		$colegio_id = $orden[0]["Colegio"]["id"];
		$colegio_identificador = $orden[0]["Colegio"]["identificador"];
		$colegio_nombre = $orden[0]["Colegio"]["nombre"];

		$articulos = $this->DetalleOrdenCompra->articulosVendidos($orden_id, $colegio_id, $this->cicloActual(), "ver");

		$totales = $articulos[1];
		$articulos = $articulos[0];

		$tabla1 = array();
		$fecha_actual = $this->fechaHoy();
		$fecha_actual = str_replace("/", "-", $fecha_actual);
		foreach ($articulos as $key => $articulo)
		{
			if ($articulo["f_cantidad"])
			{
				$ws_mizar["NumDocumento"]      = intval($orden_id);
				$ws_mizar["RFCCompañia"]       = "LAN7008173R5";
				$ws_mizar["Sucursal"]          = "";
				$ws_mizar["RFCCliente"]        = "XAXX010101000";
				$ws_mizar["ClaveCliente"]      = $colegio_identificador;
				$ws_mizar["TipoDocumento"]     = "ingreso";
				$ws_mizar["MetodoDePago"]      = "NA";
				$ws_mizar["FormaDePago"]       = "99";
				$ws_mizar["CondicionesDePago"] = "NA";
				$ws_mizar["Moneda"]            = 1;
				$ws_mizar["FechayHora"]        = $fecha_actual;
				$ws_mizar["TipoCambio"]        = 1;
				$ws_mizar["Cantidad"]          = $articulo["f_cantidad"];

				if ($articulo["resultado"])
					$ws_mizar["PrecioUnitario"] = floatval(str_replace(',', '', $articulo["individual"]));
				else
					$ws_mizar["PrecioUnitario"] = floatval(str_replace(',', '', $articulo["fp_importe"]));
				
				if ($articulo["resultado"])
					$ws_mizar["Importe"] = floatval(str_replace(',', '', $articulo["resultado"]));
				else
					$ws_mizar["Importe"] = floatval(str_replace(',', '', $articulo["fp_importe"]));
				
				$ws_mizar["Descripcion"]    = $articulo["descripcion"];

				if ($ajuste_iva > 0)
					$ws_mizar["ImporteIVA"]     = floatval(str_replace(',', '', $ajuste_iva));
				else
					$ws_mizar["ImporteIVA"]     = floatval(str_replace(',', '', $totales["fp_iva_total"]));
				
				$ws_mizar["Descuento"]      = 0;
				$ws_mizar["UnidadDeMedida"] = "NA";
				$ws_mizar["NumeroArticulo"] = $articulo["identificador"];
				$ws_mizar["NumeroCuenta"]   = "";
				$ws_mizar["EsNotaCredito"]  = 0;
				$ws_mizar["TasaIVA"]        = $articulo["iva"];
				$ws_mizar["TasaIEPS"]       = 0;
				$ws_mizar["TasaISRRET"]     = 0;
				$ws_mizar["TasaIVARET"]     = 0;
				$ws_mizar["MontoIEPS"]      = 0;
				$ws_mizar["MontoISRRET"]    = 0;
				$ws_mizar["MontoIVARET"]    = 0;

				array_push($tabla1, $ws_mizar);
			}
		}

		$formato = array(
			"Tabla1" => $tabla1
		);

		$datos_json = json_encode($formato, JSON_UNESCAPED_UNICODE);

		try {
			@$client = new SoapClient("http://209.15.226.227:8080/WsFactExternos/Comprobantes.asmx?WSDL");
		}
		catch (SoapFault $E)
		{
			$Email = new CakeEmail();
			$Email->template('default', 'orden_sin_facturar');
			$Email->emailFormat('html');
			$Email->config('smtp');
			$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu", $asociado_correo));
			$Email->subject("Pruebas, No Facturado - Orden Compra ".$orden_id);
			$Email->viewVars(array(
				'orden_id' => $orden_id,
				'error' => "No entra al sistema de facturación.",
				'colegio_id' => $colegio_identificador,
				'colegio_nombre' => $colegio_nombre
			));
			$Email->send();
		} 

		if (@$client)
		{
			$params = array(
				'dComprobante' => $datos_json
			);
			$result = $client->GeneraComprobante($params)->GeneraComprobanteResult;
			$result = json_decode($result, true);

			$mensaje_mizar = trim(preg_replace('/\s+/', ' ', $result["Table1"][0]["Mensajes"]));
			var_dump($mensaje_mizar);

			if (!$result["Table1"][0]["Estatus"])
			{
				$Email = new CakeEmail();
				$Email->template('default', 'orden_sin_facturar');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu"));
				$Email->subject("Pruebas, No Facturado - Orden Compra ".$orden_id);
				$Email->viewVars(array(
					'orden_id' => $orden_id,
					'error' => $mensaje_mizar,
					'colegio_id' => $colegio_identificador,
					'colegio_nombre' => $colegio_nombre
				));
				$Email->send();
			}
			else
			{
				$uuid = $result["Table1"][0]["UUID"];
				// $pdf_factura = $result["Table1"][0]["PDF"];
				// $xml = base64_encode($result["Table1"][0]["XML"]);

				$orden = $this->OrdenCompra->traerOrdenCompra(array('id' => $orden_id));
				// $pedido[0]["Pedido"]["pdf_factura"] = $pdf_factura;
				// $pedido[0]["Pedido"]["xml"] = $xml;
				$orden["OrdenCompra"]["uuid"] = $uuid;
				$orden["OrdenCompra"]["fecha_facturado"] = $this->fechaHoy()." - ".$result["Table1"][0]["Folio"];

				$this->OrdenCompra->guardarEnBDD($orden["OrdenCompra"], "editar");
			}
		}

		$this->redirect("/orden_compras/facturas");
	}

}