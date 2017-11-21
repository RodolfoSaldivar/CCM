<?php
App::uses('AppController', 'Controller');
/**
 * Dashboard Controller
 *
 */
class DashboardController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
	    return true;
    }
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';
		$this->loadModel("Colegio");
		$this->loadModel("Nivele");
		$this->loadModel("CatalogoNivele");

		$catalogo_niveles = $this->CatalogoNivele->todosNiveles();

		$colegios = $this->Colegio->todosColegios(array());

		foreach ($colegios as $keyC => $colegio)
		{
			$niveles = $this->Nivele->todosNiveles($colegio["Colegio"]["id"]);
			foreach ($niveles as $keyN => $nivel)
			{
				$colegios[$keyC]["Niveles"][$keyN] = $nivel["CatalogoNivele"]["id"];
			}
		}
		
		$this->set("catalogo_niveles", $catalogo_niveles);
		$this->set("colegios", $colegios);	
	}
	

//=========================================================================


	public function resultados($fila = null, $agregados = null, $actualizados = null, $errores_filas = null)
    {
		$this->layout = 'admin';
	    $this->set("fila", $fila);
	    $this->set("agregados", $agregados);
	    $this->set("actualizados", $actualizados);
	    $this->set("errores_filas", json_decode(base64_decode($errores_filas), true));
    }
	

//=========================================================================


	public function descargar_excel()
	{
	    $this->layout = 'vacio';
		if ($this->request->is('post'))
		{
			$filas = $this->request->data["Filas"];
			$nombre_archivo = $this->request->data["nombre_archivo"];

		    $this->set("filas", $filas);
		    $this->set("nombre_archivo", $nombre_archivo);
		}
	}
	

//=========================================================================


	public function descargar_cierre()
	{
	    $this->layout = 'vacio';

	    $data_enc = $this->Session->read("data_cierre");

		if ($data_enc)
		{
			$data = json_decode(base64_decode($data_enc), true);

			$filas = $data["Filas"];
			$nombre_archivo = $data["nombre_archivo"];

			$this->Session->delete("data_cierre");

		    $this->set("filas", $filas);
		    $this->set("nombre_archivo", $nombre_archivo);
		}
	}

}
