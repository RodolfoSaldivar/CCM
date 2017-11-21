<?php
App::uses('AppController', 'Controller');
/**
 * Carrito Controller
 *
 */
class CarritoController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Padres
		if (isset($user['tipo']) && in_array($user['tipo'], array("Padre")))
		{
			return true;
		}

		//Acceso para Cajeros
		if (in_array($this->action, array('terminar_pedido')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Cajero")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }


//=========================================================================


	public function ver()
	{
		$this->loadModel("CicloHijo");

		$hijo_id = $this->Session->read("hijo_id");
		$colegio_id = $this->CicloHijo->find('first', array(
			'conditions' => array('hijo_id' => $hijo_id),
			'fields' => array('colegio_id')
		));
		$colegio_id = $colegio_id["CicloHijo"]["colegio_id"];

		$paquetes_en_session = $this->paquetesEnSession();

		$this->set("colegio_id", $colegio_id);
		$this->set("paquetes", $paquetes_en_session[1]);
		@$this->set("mismos", $paquetes_en_session[0]);
	}


//=========================================================================


	public function agregar_al_carrito()
	{
		$this->layout = 'ajax';

		$paquete_id = $this->request->data["paquete_id"];

		$carrito = $this->Session->read("Carrito");

		if ($carrito)
			array_push($carrito, $paquete_id);
		else
			$carrito = array($paquete_id);

		$this->Session->write("Carrito", $carrito);
	}


//=========================================================================


	public function remover_del_carrito()
	{
		$this->layout = 'ajax';

		$paquete_id = $this->request->data["paquete_id"];

		$carrito = $this->Session->read("Carrito");

		foreach (array_keys($carrito, $paquete_id, true) as $key) {
		    unset($carrito[$key]);
		    break;
		}

		$this->Session->write("Carrito", $carrito);
	}


//=========================================================================


	public function pagar()
	{
		$this->loadModel("Hijo");
		$this->loadModel("FacturacionDato");
		$this->loadModel("PedidosPaquete");
		$this->loadModel("CicloHijo");
		$this->loadModel("Pedido");
		$this->loadModel("Pago");

		@$result_indicator    = $this->params['url']['resultIndicator'];
		@$success_indicator   = $this->Session->read("Pago.successIndicator");
		$habilita_comprobante = $this->Session->read("habilita_comprobante");

		if ($result_indicator)
		{
			if ($result_indicator == $success_indicator)
			{
				$this->Session->write("Pago.ya_pagado", 1);

				if (!$habilita_comprobante)
				{
					$pedido_id = $this->Session->read("Pago.pedido_id");

					$datos_pedido["id"] = $pedido_id;

					$ciclo_hijo_id = $this->CicloHijo->find('first', array(
						'conditions' => array(
							'ciclo' => $this->cicloActual(),
							'hijo_id' => $this->Session->read("hijo_id")
						),
						'fields' => array('id')
					));
					$ciclo_hijo_id = $ciclo_hijo_id["CicloHijo"]["id"];


					$paquetes_en_session = $this->paquetesEnSession();
					$paquetes = $paquetes_en_session[1];
					$mismos = $paquetes_en_session[0];
					$importe_total = 0;

					foreach ($paquetes as $key => $paquete)
					{
						$importe_subtotal = $mismos[$paquete["Paquete"]["id"]] * $paquete["Precios"]["precio_publico"];
						$importe_total+= $importe_subtotal;
					}

					$fecha_pedido = $this->fechaHoy();

					$datos_pedido["padre_id"]      = $this->Session->read("Auth.User.id");
					$datos_pedido["ciclo_hijo_id"] = $ciclo_hijo_id;
					$datos_pedido["importe"]       = $importe_total;
					$datos_pedido["fecha_pedido"]  = $fecha_pedido;

					$guardado = $this->Pedido->guardarEnBDD($datos_pedido, "editar");

					if ($guardado != 1)
						$this->Session->setFlash($guardado);
					else
					{
						$this->Session->write("Pago.id_url", $pedido_id);

						$datos_ped_paq["pedido_id"] = $pedido_id;

				    	foreach ($paquetes as $key => $paquete)
						{
							$datos_ped_paq["importe"] = $paquete["Precios"]["precio_publico"];
							$datos_ped_paq["cantidad"] = $mismos[$paquete["Paquete"]["id"]];
							$datos_ped_paq["paquete_id"] = $paquete["Paquete"]["id"];
							
							$this->PedidosPaquete->guardarEnBDD($datos_ped_paq, "agregar");
						}

						$datos_pago["fecha_pago"] = $this->fechaHoy();
						$datos_pago["pedido_id"]  = $pedido_id;
						$datos_pago["forma_pago"] = "en línea";
						$datos_pago["importe"]    = $importe_total;
						$datos_pago["referencia"] = "en línea";

						$guardado = $this->Pago->guardarEnBDD($datos_pago, "agregar");

						$this->Session->setFlash('Pago realizado exitosamente.');
					}
				}
				
			}
		}

		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			$this->Session->write("forma_pago", $data["forma_pago"]);

			if ($data["forma_pago"] == "linea")
			{
				$this->redirect(array('action' => 'pago_en_linea'));
			}
		}

		$paquetes_en_session = $this->paquetesEnSession();

		$condiciones = array('asociado_id' => $this->Session->read("Auth.User.id"));
		$d_fac = $this->FacturacionDato->traerDatos($condiciones);

		$condiciones = array('Hijo.id' => $this->Session->read("hijo_id"));
		$hijo = $this->Hijo->traerHijos($condiciones);

		@$this->set("ya_pagado", $this->Session->read("Pago.ya_pagado"));
		@$this->set("habilita_comprobante", $habilita_comprobante);
		@$this->set("id_url", $this->Session->read("Pago.id_url"));
		$this->set("hijo", $hijo[0]["Hijo"]);
		$this->set("paquetes", $paquetes_en_session[1]);
		@$this->set("mismos", $paquetes_en_session[0]);
		@$this->set("d_fac", $d_fac[0]["FacturacionDato"]);
	}


//=========================================================================


	public function pago_en_linea()
	{
		$this->loadModel("Pedido");

		$this->layout = 'vacio';

		// Saca el precio total
		$paquetes_en_session = $this->paquetesEnSession();
		$paquetes = $paquetes_en_session[1];
		$mismos = $paquetes_en_session[0];
		$importe_total = 0;
		foreach ($paquetes as $key => $paquete)
		{
			$importe_subtotal = $mismos[$paquete["Paquete"]["id"]] * $paquete["Precios"]["precio_publico"];
			$importe_total+= $importe_subtotal;
		}
		$importe_total = number_format($importe_total, 2, '.', '');
		// $importe_total = 1.10;

		// Crea el pedido vacio para tenerlo el id del order.id
		$this->Pedido->guardarEnBDD(array(), "agregar");
		$pedido_id = $this->Pedido->find('first', array(
			'order' => array('id' => 'DESC'),
			'fields' => array('id')
		));
		$pedido_id = $pedido_id["Pedido"]["id"];
		// 

		$data = array(
			"apiOperation"          => "CREATE_CHECKOUT_SESSION",
			"apiPassword"           => "64372af3d01b11775b1c30e0160ab0f5",
			"apiUsername"           => "merchant.TEST1060709",
			"merchant"              => "TEST1060709",
			"order.id"              => "$pedido_id",
			"order.amount"          => "$importe_total",
			"order.currency"        => "MXN",
			"interaction.returnUrl" => $_SERVER['REQUEST_SCHEME']."://".$_SERVER["HTTP_HOST"]."/carrito/pagar"
		);

		$post_string = "";
		foreach ($data as $key => $value)
			$post_string.= "$key=$value&";

		//-----------------------------------------------------------------
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://banamex.dialectpayments.com/api/nvp/version/41');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);

		$respuesta = array();
		parse_str(curl_exec($ch), $respuesta);
		//-----------------------------------------------------------------

		$session_id = $respuesta["session_id"];
		$success_indicator = $respuesta["successIndicator"];
		$url_cancelar = $_SERVER['REQUEST_SCHEME']."://".$_SERVER["HTTP_HOST"]."/carrito/ver";
		$anio1 = $this->cicloActual();
		$anio2 = $this->cicloActual() + 1;
		$ciclo_escolar = "$anio1 - $anio2";

		$this->Session->write("Pago.pedido_id", $pedido_id);
		$this->Session->write("Pago.session_id", $session_id);
		$this->Session->write("Pago.importe_total", $importe_total);
		$this->Session->write("Pago.successIndicator", $success_indicator);

		$this->set("pedido_id", $pedido_id);
		$this->set("session_id", $session_id);
		$this->set("url_cancelar", $url_cancelar);
		$this->set("ciclo_escolar", $ciclo_escolar);
		$this->set("importe_total", $importe_total);
		$this->set("success_indicator", $success_indicator);
	}


//=========================================================================


	public function terminar_pedido($id_url = 0)
	{
		$this->loadModel("ArticulosPaquete");
		$this->loadModel("FacturacionDato");
		$this->loadModel("PedidosPaquete");
		$this->loadModel("CicloHijo");
		$this->loadModel("Asociado");
		$this->loadModel("Pedido");
		$this->loadModel("Hijo");

		$this->layout = 'vacio';

		if (!$id_url)
		{
			if ($this->Session->read("Pago.ya_pagado"))
			{
				$datos_pedido["id"] = $this->Session->read("Pago.pedido_id");
				$accion = "editar";
			}
			else
			{	
				$accion = "agregar";
			}

			$ciclo_hijo_id = $this->CicloHijo->find('first', array(
				'conditions' => array(
					'ciclo' => $this->cicloActual(),
					'hijo_id' => $this->Session->read("hijo_id")
				),
				'fields' => array('id')
			));
			$ciclo_hijo_id = $ciclo_hijo_id["CicloHijo"]["id"];


			$paquetes_en_session = $this->paquetesEnSession();
			$paquetes = $paquetes_en_session[1];
			$mismos = $paquetes_en_session[0];
			$importe_total = 0;

			foreach ($paquetes as $key => $paquete)
			{
				$importe_subtotal = $mismos[$paquete["Paquete"]["id"]] * $paquete["Precios"]["precio_publico"];
				$importe_total+= $importe_subtotal;
			}

			$fecha_pedido = $this->fechaHoy();

			$datos_pedido["padre_id"]      = $this->Session->read("Auth.User.id");
			$datos_pedido["ciclo_hijo_id"] = $ciclo_hijo_id;
			$datos_pedido["importe"]       = $importe_total;
			$datos_pedido["fecha_pedido"]  = $fecha_pedido;

			$guardado = $this->Pedido->guardarEnBDD($datos_pedido, $accion);
		}
		else
		{
			$guardado = 1;
			$pedido_id = $id_url;
		}

		if ($guardado != 1)
			var_dump("no se guardo");
		else
		{
		    if (!$id_url)
	    	{
	    		$pedido_id = $this->Pedido->find('first', array(
		    		'conditions' => array(
		    			'fecha_pedido' => $fecha_pedido,
		    			'padre_id' => $datos_pedido["padre_id"],
		    			'ciclo_hijo_id' => $ciclo_hijo_id
		    		),
		    		'fields' => array('id'),
		    		'order' => array('id DESC')
		    	));
		    	$pedido_id = $pedido_id["Pedido"]["id"];


		    	$datos_ped_paq["pedido_id"] = $pedido_id;

		    	foreach ($paquetes as $key => $paquete)
				{
					$datos_ped_paq["importe"] = $paquete["Precios"]["precio_publico"];
					$datos_ped_paq["cantidad"] = $mismos[$paquete["Paquete"]["id"]];
					$datos_ped_paq["paquete_id"] = $paquete["Paquete"]["id"];
					
					$this->PedidosPaquete->guardarEnBDD($datos_ped_paq, "agregar");
				}
	    	}

			//Hasta aqui se guarda en la base de datos
			//Comienza a buscar la informacion que va en el PDF

			$pedido = $this->Pedido->traerPedidos(array('id' => $pedido_id));
			$pedido = $pedido[0]["Pedido"];

			$ciclo_hijo = $this->CicloHijo->traerInfo(array('id' => $pedido["ciclo_hijo_id"]));
			$ciclo_hijo = $ciclo_hijo[0]["CicloHijo"];

			$hijo = $this->Hijo->traerHijos(array('Hijo.id' => $ciclo_hijo["hijo_id"]));
			$hijo_nombre = $hijo[0]["Hijo"]["nombre"]." ".$hijo[0]["Hijo"]["a_paterno"]." ".$hijo[0]["Hijo"]["a_materno"];
			$colegio_id = $hijo[0]["CicloHijo"]["colegio_id"];
			$colegio_nombre = $hijo[0]["Hijo"]["colegio"];
			$nivel_nombre = $hijo[0]["Hijo"]["nivel"];
			$grado_nombre = $hijo[0]["Hijo"]["grado"];

			if ($this->Session->read("Auth.User.tipo") == "Cajero")
				$cajero = 1;
			else
				$cajero = 0;

			$padre = $this->Asociado->traerAsociados(array('id' => $pedido["padre_id"]));
			$padre = $padre[0]["Asociado"];
			$padre_nombre = $padre["nombre"]." ".$padre["a_paterno"]." ".$padre["a_materno"];

			$datos_facturacion = $this->FacturacionDato->traerDatos(array('asociado_id' => $pedido["padre_id"]));
			if ($datos_facturacion)
				$datos_facturacion = $datos_facturacion[0]["FacturacionDato"];

			$paquetes = $this->PedidosPaquete->traerPedidosPaquetes(array('pedido_id' => $pedido_id));

			$detalles_articulos = array();

			foreach ($paquetes as $keyP => $paquete)
			{
				$paquete_id = $paquete["Paquete"]["id"];
				$condiciones = array('Paquete.id' => $paquete_id);
				$articulos = $this->ArticulosPaquete->traerArticulosEnPaquete($condiciones);

				foreach ($articulos as $keyA => $articulo)
				{
					$articulo_id = $articulo["Articulo"]["id"];
					$iva = $articulo["Articulo"]["iva"] / 100;
					$cantidad_articulo = $articulo["ArticulosPaquete"]["cantidad"] *
										 $paquete["PedidosPaquete"]["cantidad"];
					$precio_unitario = $articulo["ArticulosPaquete"]["precio_publico"];
					$precio_total = $cantidad_articulo * $precio_unitario;

					$detalles_articulos["$paquete_id-$articulo_id"] = array(
						'identificador'   => $articulo["Articulo"]["identificador"],
						'descripcion'     => $articulo["Articulo"]["descripcion"],
						'cantidad'        => $cantidad_articulo,
						'precio_unitario' => $precio_unitario,
						'total'           => $precio_total
					);
				}
			}
			//var_dump($detalles_articulos);

			$comprobante["datos_facturacion"] = $datos_facturacion;
			$comprobante["colegio_nombre"]    = $colegio_nombre;
			$comprobante["nivel_nombre"]      = $nivel_nombre;
			$comprobante["grado_nombre"]      = $grado_nombre;
			$comprobante["padre_nombre"]      = $padre_nombre;
			$comprobante["hijo_nombre"]       = $hijo_nombre;
			$comprobante["pedido"]            = $pedido;
			$comprobante["padre"]             = $padre;

			$this->set("comprobante", $comprobante);
	    	$this->set("colegio_id",  $colegio_id);
			$this->set("articulos",   $detalles_articulos);
	    	$this->set("pedido_id",   $pedido_id);
	    	$this->set("cajero",      $cajero);
		}
	}

}
