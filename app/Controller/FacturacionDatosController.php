<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * FacturacionDatos Controller
 *
 */
class FacturacionDatosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Todos
		return true;
    }
	

//=========================================================================


	public function actualizar()
	{
		if ($this->request->is('post'))
		{
			$data = $this->request->data;
			$asociado_id = $data["asociado_id"];

			try {
				@$client = new SoapClient("http://209.15.226.227:8080/WsFactExternos/Comprobantes.asmx?WSDL");
			}
			catch (SoapFault $E)
			{
			    $Email = new CakeEmail();
				$Email->template('default', 'datos_facturacion');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to(array("fallas-fac@tiendaccm.mx", "rodolfo.saldivar@udem.edu"));
				$Email->subject("Error - Datos de Facturación");
				$Email->send();
			}

			if (@$client)
			{
				$this->loadModel("Asociado");
				$asociado = $this->Asociado->traerAsociados(array('id' => $asociado_id));

				if (!$asociado[0]["Asociado"]["celular"])
					$asociado[0]["Asociado"]["celular"] = "1";

				$ws_mizar["NumCliente"] = $asociado_id;
				$ws_mizar["RazonSocial"] = $data["FacturacionDato"]["razon_social"];
				$ws_mizar["RFC"] = $data["FacturacionDato"]["rfc"];
				$ws_mizar["Calle"] = $data["FacturacionDato"]["calle"];
				$ws_mizar["Colonia"] = $data["FacturacionDato"]["colonia"];
				$ws_mizar["EntreCalles"] = "";
				$ws_mizar["NoInterior"] = $data["FacturacionDato"]["numero_interior"];
				$ws_mizar["NoExterior"] = $data["FacturacionDato"]["numero"];
				$ws_mizar["Estado"] = $data["FacturacionDato"]["estado"];
				$ws_mizar["Localidad"] = $data["FacturacionDato"]["localidad"];
				$ws_mizar["Municipio"] = $data["FacturacionDato"]["ciudad"];
				$ws_mizar["Referencia"] = "";
				$ws_mizar["Pais"] = $data["FacturacionDato"]["pais"];
				$ws_mizar["CodigoPostal"] = $data["FacturacionDato"]["codigo_postal"];
				$ws_mizar["Email"] = $asociado[0]["Asociado"]["mail"];
				$ws_mizar["Telefono"] = $asociado[0]["Asociado"]["celular"];
				$ws_mizar["RFCCompañia"] = "AAA010101AAA";

				$formato = array(
					"Tabla1" => array(
						$ws_mizar
					)
				);

				$datos_json = json_encode($formato, JSON_UNESCAPED_UNICODE);

				$params = array(
					'dClientes' => $datos_json
				);
				$result = $client->AdmonClientes($params)->AdmonClientesResult;
				$result = json_decode($result, true);

				$mensaje_mizar = trim(preg_replace('/\s+/', ' ', $result["Table1"][0]["Mensajes"]));
				//var_dump($mensaje_mizar);

				if (!$result["Table1"][0]["Estatus"])
				{
					$this->Session->setFlash($mensaje_mizar);
				}
				else
				{
					$condiciones = array('asociado_id' => $asociado_id);
					$d_fac = $this->FacturacionDato->traerDatos($condiciones);

					if ($d_fac)
						$accion = "editar";
					else
						$accion = "agregar";


					$data['FacturacionDato']['asociado_id'] = $asociado_id;
					$guardado = $this->FacturacionDato->guardarEnBDD($data['FacturacionDato'], $accion);

					if ($guardado != 1)
						$this->Session->setFlash($guardado);
					else
					{
						$this->Session->write("habilita_comprobante", 1);
						$this->Session->setFlash('Datos guardados exitosamente.');
					}
				}					
			}
			else
				$this->Session->setFlash('Error Interno, intente después.');

			if ($this->Session->read("pedidos_detalle"))
			{
				$pedido_id = $this->Session->read("pedidos_detalle");
				$fact_forma_pago = $this->Session->read("fact_forma_pago");
				$this->Session->delete("pedidos_detalle");
				$this->Session->delete("fact_forma_pago");
				$this->redirect("/pedidos/opcion_facturar/$pedido_id/$fact_forma_pago");
			}
			else
	    		$this->redirect($this->referer());
		}
	}
	

//=========================================================================


	public function cambiar($asociado_id, $pedido_id, $forma_pago)
	{
		$this->layout = 'admin';

		$this->Session->write("pedidos_detalle", $pedido_id);
		$this->Session->write("fact_forma_pago", $forma_pago);

		$condiciones = array('asociado_id' => $asociado_id);
		$d_fac = $this->FacturacionDato->traerDatos($condiciones);

		@$this->set("d_fac", $d_fac[0]["FacturacionDato"]);
		$this->set("asociado_id", $asociado_id);
	}

}
