<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Cajas Controller
 *
 */
class CajasController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Cajeros
		if (isset($user['tipo']) && in_array($user['tipo'], array("Cajero")))
		{
			return true;
		}

		return parent::isAuthorized($user);
	}


//=========================================================================


	function corteCajaAbierta()
	{
		$this->loadModel("CorteCaja");

		$cajero_id = $this->Session->read("Auth.User.id");

		$condiciones = array(
			'CorteCaja.cajero_id'    => $cajero_id,
			'CorteCaja.fecha_cierre' => ''
		);
		$corte = $this->CorteCaja->traerCortes($condiciones);

		return @$corte[0];
	}


//=========================================================================


	public function facturarMizar($pedido_id, $clave_pago)
	{
		$this->loadModel("Pedido");
		$this->loadModel("Asociado");
		$this->loadModel("PedidosPaquete");
		$this->loadModel("FacturacionDato");
		$this->loadModel("ArticulosPaquete");

		$paquetes = $this->PedidosPaquete->traerPedidosPaquetes(array('pedido_id' => $pedido_id));

		$detalles_articulos = array();
		$info_paquetes = array();

		foreach ($paquetes as $keyP => $paquete)
		{
			$info = array(
				'id_interno' => $paquete["Paquete"]["id"],
				'identificador' => $paquete["Paquete"]["identificador"],
				'descripcion' => $paquete["Paquete"]["descripcion"]
			);
			array_push($info_paquetes, $info);

			$paquete_id = $paquete["Paquete"]["id"];
			$condiciones = array('Paquete.id' => $paquete_id);
			$articulos = $this->ArticulosPaquete->traerArticulosEnPaquete($condiciones);

			foreach ($articulos as $keyA => $articulo)
			{
				$articulo_id = $articulo["Articulo"]["id"];
				$iva = $articulo["Articulo"]["iva"] / 100;
				$cantidad_articulo = $articulo["ArticulosPaquete"]["cantidad"] *
									 $paquete["PedidosPaquete"]["cantidad"];
				$precio_con_iva = $articulo["ArticulosPaquete"]["precio_publico"];
				$precio_unitario = $precio_con_iva / (1 + $iva);
				$precio_unitario = number_format($precio_unitario, 2);
				$precio_iva = $articulo["ArticulosPaquete"]["precio_publico"] - $precio_unitario;
				$precio_total = $cantidad_articulo * ($precio_unitario + $precio_iva);

				$detalles_articulos["$paquete_id-$articulo_id"] = array(
					'identificador'   => $articulo["Articulo"]["identificador"],
					'descripcion'     => $articulo["Articulo"]["descripcion"],
					'cantidad'        => $cantidad_articulo,
					'precio_unitario' => $precio_unitario,
					'iva'             => $articulo["Articulo"]["iva"],
					'importe_iva'     => $precio_iva,
					'total'           => $precio_total
				);
			}
		}

		$pedido = $this->Pedido->find('first', array(
			'recursive' => 0,
			'conditions' => array('Pedido.id' => $pedido_id),
			'fields' => array('Asociado.id')
		));
		$d_fac = $this->FacturacionDato->traerDatos(array('asociado_id' => $pedido["Asociado"]["id"]));

		$asociado_correo = $this->Asociado->find('first', array(
			'conditions' => array('id' => $pedido["Asociado"]["id"]),
			'fields' => array('mail')
		));
		$asociado_correo = $asociado_correo["Asociado"]["mail"];

		$iva_total = 0;
		foreach ($detalles_articulos as $key => $articulo)
			$iva_total+= $articulo["importe_iva"] * $articulo["cantidad"];

		$tabla1 = array();
		$fecha_actual = $this->fechaHoy();
		$fecha_actual = str_replace("/", "-", $fecha_actual);
		foreach ($detalles_articulos as $key => $articulo)
		{
			$ws_mizar["NumDocumento"]      = intval($pedido_id);
			$ws_mizar["RFCCompañia"]       = "LAN7008173R5";
			$ws_mizar["Sucursal"]          = "";
			$ws_mizar["RFCCliente"]        = $d_fac[0]["FacturacionDato"]["rfc"];
			$ws_mizar["ClaveCliente"]      = $d_fac[0]["FacturacionDato"]["asociado_id"];
			$ws_mizar["TipoDocumento"]     = "ingreso";
			$ws_mizar["MetodoDePago"]      = "NA";
			$ws_mizar["FormaDePago"]       = "$clave_pago";
			$ws_mizar["CondicionesDePago"] = "NA";
			$ws_mizar["Moneda"]            = 1;
			$ws_mizar["FechayHora"]        = $fecha_actual;
			$ws_mizar["TipoCambio"]        = 1;
			$ws_mizar["Cantidad"]          = $articulo["cantidad"];
			$ws_mizar["PrecioUnitario"]    = $articulo["precio_unitario"];
			$ws_mizar["Importe"]           = $articulo["precio_unitario"] * $articulo["cantidad"];
			$ws_mizar["Descripcion"]       = $articulo["descripcion"];
			$ws_mizar["ImporteIVA"]        = $iva_total;
			$ws_mizar["Descuento"]         = 0;
			$ws_mizar["UnidadDeMedida"]    = "NA";
			$ws_mizar["NumeroArticulo"]    = $articulo["identificador"];
			$ws_mizar["NumeroCuenta"]      = "";
			$ws_mizar["EsNotaCredito"]     = 0;
			$ws_mizar["TasaIVA"]           = $articulo["iva"];
			$ws_mizar["TasaIEPS"]          = 0;
			$ws_mizar["TasaISRRET"]        = 0;
			$ws_mizar["TasaIVARET"]        = 0;
			$ws_mizar["MontoIEPS"]         = 0;
			$ws_mizar["MontoISRRET"]       = 0;
			$ws_mizar["MontoIVARET"]       = 0;

			array_push($tabla1, $ws_mizar);
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
			$Email->template('default', 'no_facturado');
			$Email->emailFormat('html');
			$Email->config('smtp');
			$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu", $asociado_correo));
			$Email->subject("Pruebas, No Facturado - Pedido ".$pedido_id);
			$Email->viewVars(array(
				'pedido_id' => $pedido_id,
				'error' => 0,
				'info_paquetes' => $info_paquetes
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
			// var_dump($mensaje_mizar);

			if (!$result["Table1"][0]["Estatus"])
			{
				// $this->Session->setFlash($mensaje_mizar);
				$Email = new CakeEmail();
				$Email->template('default', 'no_facturado');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu", $asociado_correo));
				$Email->subject("Pruebas, No Facturado - Pedido ".$pedido_id);
				$Email->viewVars(array(
					'pedido_id' => $pedido_id,
					'error' => $mensaje_mizar,
					'info_paquetes' => $info_paquetes
				));
				$Email->send();
			}
			else
			{
				$uuid = $result["Table1"][0]["UUID"];
				$pdf_factura = $result["Table1"][0]["PDF"];
				$xml = base64_encode($result["Table1"][0]["XML"]);

				$pedido = $this->Pedido->traerPedidos(array('id' => $pedido_id));
				$pedido[0]["Pedido"]["estatus"] = 1;
				$pedido[0]["Pedido"]["pdf_factura"] = $pdf_factura;
				$pedido[0]["Pedido"]["xml"] = $xml;
				$pedido[0]["Pedido"]["uuid"] = $uuid;
				$pedido[0]["Pedido"]["fecha_facturado"] = $this->fechaHoy()." - ".$result["Table1"][0]["Folio"];

				$this->Pedido->guardarEnBDD($pedido[0]["Pedido"], "editar");
			}	
		}
	}


//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");

		$user_tipo = $this->Session->read("Auth.User.tipo");

		$colegios = $this->Colegio->todosColegios(array('id >' => '0'));

		$corte = $this->corteCajaAbierta();

		if ($corte)
			$caja_abierta = 1;
		else
			$caja_abierta = 0;

		$this->set("colegios", $colegios);
		$this->set("caja_abierta", $caja_abierta);
		$this->set("user_tipo", $user_tipo);
	}


//=========================================================================


	public function filtrar_tabla()
	{
		$this->layout='ajax';
		$this->loadModel("Pedido");

		$colegio_id = $this->request->data["colegio"];
		$caja_abierta = $this->request->data["caja_abierta"];

		$condiciones = array(
			'CicloHijo.colegio_id'   => $colegio_id,
			'CicloHijo.ciclo'        => $this->cicloActual(),
			'Pedido.estatus'         => 0,
			'Pedido.fecha_cancelado' => ''
		);

		$pedidos = $this->Pedido->traerPedidosXciclo($condiciones);

		$this->set("pedidos", $pedidos);
		$this->set("caja_abierta", $caja_abierta);
	}


//=========================================================================


	public function buscador_filtrar()
	{
		$this->layout='ajax';

		$this->loadModel("Pedido");
		$this->loadModel("CicloHijo");

		$data = $this->request->data;

		$valido = $this->Pedido->validarBuscador($data);

		if (!$valido)
			$this->set("pedidos", "");
		else
		{
			//Condiciones default
			$condiciones = array(
				'CicloHijo.colegio_id'   => $data["colegio_id"],
				'CicloHijo.ciclo'        => $this->cicloActual(),
				'Pedido.estatus'         => 0,
				'Pedido.fecha_cancelado' => ''
			);

			//Agrega la condicion si su campo no esta vacio
			if (!empty($data['pedido_id']))
				$condiciones["Pedido.id"] = $data["pedido_id"];

			if (!empty($data['nombre_padre']))
				$condiciones["CHARINDEX('".$data['nombre_padre']."', CONCAT(Asociado.nombre, ' ', Asociado.a_paterno, ' ', Asociado.a_materno)) >"] = '0';

			if (!empty($data['nombre_hijo']))
			{
				$hijos = $this->CicloHijo->find('all', array(
					'recursive' => 0,
					'conditions' => array(
						"CHARINDEX('".$data['nombre_hijo']."', CONCAT(Hijo.nombre, ' ', Hijo.a_paterno, ' ', Hijo.a_materno)) >" => "0"
					),
					'fields' => array('Hijo.id')
				));

				$hijos_id = array();
				foreach ($hijos as $key => $hijo)
					array_push($hijos_id, $hijo["Hijo"]["id"]);

				$condiciones['CicloHijo.hijo_id'] = $hijos_id;
			}

			//Busca
			$encontrados = $this->Pedido->traerPedidosXciclo($condiciones);

			$this->set("pedidos", $encontrados);
		}

		$this->set("caja_abierta", $data["caja_abierta"]);
	}


//=========================================================================


	public function cobrar($pedido_id)
	{
		$this->layout='admin';
 		$this->loadModel("ArticulosPaquete");
		$this->loadModel("FacturacionDato");
 		$this->loadModel("PedidosPaquete");
 		$this->loadModel("Paquete");
		$this->loadModel("Pedido");
		$this->loadModel("Hijo");

		$pedido = $this->Pedido->traerPedidosXciclo(array('Pedido.id' => $pedido_id));

		$condiciones = array(
			'pedido_id' => $pedido_id
		);
		$paquetes = $this->PedidosPaquete->traerPedidosPaquetes($condiciones);

		$condiciones = array('asociado_id' => $pedido[0]["Pedido"]["padre_id"]);
		$d_fac = $this->FacturacionDato->traerDatos($condiciones);

		$condiciones = array('Hijo.id' => $pedido[0]["CicloHijo"]["hijo_id"]);
		$hijo = $this->Hijo->traerHijos($condiciones);

		$this->set("pedido", $pedido[0]);
		$this->set("paquetes", $paquetes);
		@$this->set("d_fac", $d_fac[0]["FacturacionDato"]);
		$this->set("hijo", $hijo[0]["Hijo"]);
	}


//=========================================================================


	public function agregar_metodo_pago()
	{
		$this->layout='ajax';

		$cont = $this->request->data["cont"];

		$this->set("cont", $cont);
	}


//=========================================================================


	public function finalizar_cobro()
	{
		$this->layout='admin';

		$this->loadModel("Pago");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			if ($data["forma"] == "factura")
			{
				if (@$data["Pago"][1])
					$clave_pago = "99";
				else
				{
					switch ($data["Pago"][0]["forma_pago"])
					{
						case 'efectivo':
							$clave_pago = "01";
							break;

						case 'banco':
							$clave_pago = "99";
							break;

						case 'cheque':
							$clave_pago = "02";
							break;

						case 'tarjeta':
							$clave_pago = "04";
							break;

						case 'en línea':
							$clave_pago = "04";
							break;
					}
				}

				//Se conecta con MIZAR para hacer la factura
				$this->facturarMizar($data["pedido_id"], $clave_pago);
			}

			$corte_caja_id = $this->corteCajaAbierta();
			$corte_caja_id = $corte_caja_id["CorteCaja"]["id"];

			$datos_pago["fecha_pago"]    = $this->fechaHoy();
			$datos_pago["corte_caja_id"] = $corte_caja_id;
			$datos_pago["pedido_id"]     = $data["pedido_id"];

			$importe_total = 0;
			$pago_efectivo = false;
			foreach ($data["Pago"] as $key => $pago)
			{
				$importe_total+= $pago["importe"];
				if ($pago["forma_pago"] == "efectivo")
				{
					$pago_efectivo = true;
					$llave_efectivo = $key;
				}
			}

			if ($importe_total > $data["importe_pagar"] && $pago_efectivo)
			{
				$diferencia = $importe_total - $data["importe_pagar"];
				$data["Pago"][$llave_efectivo]["importe"]-= $diferencia;
			}

			$todo_correcto = 1;
			foreach ($data["Pago"] as $key => $pago)
			{
				$datos_pago["forma_pago"] = $pago["forma_pago"];
				$datos_pago["importe"]    = $pago["importe"];
				$datos_pago["referencia"] = $pago["referencia"];

				$guardado = $this->Pago->guardarEnBDD($datos_pago, "agregar");

				if ($guardado != 1)
					$todo_correcto = $guardado;					
			}

			if ($todo_correcto != 1)
				$this->Session->setFlash($todo_correcto);
			else
			{
			 	$this->Session->setFlash('Pago realizado exitosamente.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}


//=========================================================================


	public function abrir_caja()
	{
		$this->layout='admin';

		if ($this->request->is('post'))
		{
			$cajero_id = $this->Session->read("Auth.User.id");
			$corte = $this->corteCajaAbierta();

			if (!$corte)
			{
				$datos_caja["cajero_id"]  = $cajero_id;
				$datos_caja["ciclo"]      = $this->cicloActual();
				$datos_caja["fecha_ap"]   = $this->fechaHoy();
				$datos_caja["importe_ap"] = $this->request->data["CorteCaja"]["importe_ap"];

				$this->CorteCaja->guardarEnBDD($datos_caja, "agregar");

				$this->redirect(array('action' => 'index'));
			}
			else
				$this->redirect(array('action' => 'index'));
		}
	}


//=========================================================================


	public function cerrar_caja()
	{
		$this->layout='admin';

		$corte = $this->corteCajaAbierta();

		$corte["CorteCaja"]["fecha_cierre"] = $this->fechaHoy();

		$this->CorteCaja->guardarEnBDD($corte["CorteCaja"], "editar");

		$this->redirect(array('action' => 'index'));
	}


//=========================================================================


	public function agregar_cobrar()
	{
		$this->layout='admin';

		$this->loadModel("FacturacionDato");
		$this->loadModel("CicloHijo");
		$this->loadModel("Colegio");
		$this->loadModel("Hijo");

		$cajero_id = $this->Session->read("Auth.User.id");
		$colegio_id = $this->Session->read("Auth.User.colegio_id");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$data["Hijo"]["asociado_id"] = $cajero_id;
			$data["CicloHijo"]["ciclo"] = $this->cicloActual();
			$data["CicloHijo"]["colegio_id"] = $colegio_id;
			var_dump($data);

			$guardado = $this->Hijo->guardarEnBDD($data, "agregar");

			if ($guardado != 1)
				$this->Session->setFlash($guardado);
			else
			{	
				$hijo_id = $this->Hijo->find("first", array(
					'conditions' => array(
						'nombre' => $data['Hijo']['nombre'],
						'a_paterno' => $data['Hijo']['a_paterno'],
						'a_materno' => $data['Hijo']['a_materno'],
						'asociado_id' => $data["Hijo"]["asociado_id"]
					),
					'order' => array('id' => "DESC"),
					'fields' => array('id')
				));
				$hijo_id = $hijo_id["Hijo"]["id"];

		    	$this->Session->write("hijo_id", $hijo_id);


		    	// Borra datos de facturacion
		    	$condiciones = array('asociado_id' => $cajero_id);
				$d_fac = $this->FacturacionDato->traerDatos($condiciones);

				if ($d_fac)
				{
					$data['FacturacionDato']['id'] = $d_fac[0]["FacturacionDato"]["id"];
					$accion = "editar";
				}
				else
					$accion = "agregar";

				$data['FacturacionDato']['asociado_id'] = $cajero_id;
				$guardado = $this->FacturacionDato->guardarEnBDD($data['FacturacionDato'], $accion);
				////////////////////////////

		    	$carrito = array();
		    	foreach ($data["Paquete"] as $paquete_id => $value)
		    	{
		    		array_push($carrito, $paquete_id);
		    	}					

				$this->Session->write("Carrito", $carrito);
				$this->Session->write("vamos_a", "/cajas/cobrar/");

		    	$this->redirect("/carrito/terminar_pedido");
			}
		}
		
		$colegio = $this->Colegio->todosColegios(array('id' => $colegio_id));

		$this->set("cajero_id", $cajero_id);
		@$this->set("colegio", $colegio[0]["Colegio"]);
	}
}
