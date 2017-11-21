<?php
App::uses('AppModel', 'Model');
/**
 * FacturacionDato Model
 *
 */
class FacturacionDato extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Asociado' => array(
			'className' => 'Asociado',
			'foreignKey' => 'asociado_id'
		)
	);
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["razon_social"]))    $atributos["razon_social"]    = "NULL";
		if (empty($atributos["rfc"]))             $atributos["rfc"]             = "NULL";
		if (empty($atributos["calle"]))           $atributos["calle"]           = "NULL";
		if (empty($atributos["numero"]))          $atributos["numero"]          = "NULL";
		if (empty($atributos["numero_interior"])) $atributos["numero_interior"] = "NULL";
		if (empty($atributos["colonia"]))         $atributos["colonia"]         = "NULL";
		if (empty($atributos["ciudad"]))          $atributos["ciudad"]          = "NULL";
		if (empty($atributos["localidad"]))       $atributos["localidad"]       = "NULL";
		if (empty($atributos["estado"]))          $atributos["estado"]          = "NULL";
		if (empty($atributos["pais"]))            $atributos["pais"]            = "NULL";
		if (empty($atributos["codigo_postal"]))   $atributos["codigo_postal"]   = "NULL";
		if (empty($atributos["asociado_id"]))     $atributos["asociado_id"]     = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["razon_social"] == "NULL")    $atributos["razon_social"]    = "";
		if ($atributos["rfc"] == "NULL")             $atributos["rfc"]             = "";
		if ($atributos["calle"] == "NULL")           $atributos["calle"]           = "";
		if ($atributos["numero"] == "NULL")          $atributos["numero"]          = "";
		if ($atributos["numero_interior"] == "NULL") $atributos["numero_interior"] = "";
		if ($atributos["colonia"] == "NULL")         $atributos["colonia"]         = "";
		if ($atributos["ciudad"] == "NULL")          $atributos["ciudad"]          = "";
		if ($atributos["localidad"] == "NULL")       $atributos["localidad"]       = "";
		if ($atributos["estado"] == "NULL")          $atributos["estado"]          = "";
		if ($atributos["pais"] == "NULL")            $atributos["pais"]            = "";
		if ($atributos["codigo_postal"] == "NULL")   $atributos["codigo_postal"]   = "";
		if ($atributos["asociado_id"] == "cero")     $atributos["asociado_id"]     = "0";

		return $atributos;
	}
	

//=========================================================================


	public function traerDatos($condiciones)
	{
		$datos = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'razon_social', 'rfc', 'numero_interior', 'calle', 'numero', 'colonia', 'ciudad', 'localidad', 'estado', 'pais', 'codigo_postal', 'asociado_id'
			)
		));

		return $datos;
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
					INSERT INTO CCM.facturacion_datos
						(razon_social, rfc, numero_interior, calle, numero, colonia, ciudad, localidad, estado, pais, codigo_postal, asociado_id)
					VALUES (
						'".$atributos['razon_social']."',
						'".$atributos['rfc']."',
						'".$atributos['numero_interior']."',
						'".$atributos['calle']."',
						'".$atributos['numero']."',
						'".$atributos['colonia']."',
						'".$atributos['ciudad']."',
						'".$atributos['localidad']."',
						'".$atributos['estado']."',
						'".$atributos['pais']."',
						'".$atributos['codigo_postal']."',
						".$atributos['asociado_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.facturacion_datos
					SET razon_social    = '".$atributos['razon_social']."',
						rfc             = '".$atributos['rfc']."',
						numero_interior = '".$atributos['numero_interior']."',
						calle           = '".$atributos['calle']."',
						numero          = '".$atributos['numero']."',
						colonia         = '".$atributos['colonia']."',
						ciudad          = '".$atributos['ciudad']."',
						localidad       = '".$atributos['localidad']."',
						estado          = '".$atributos['estado']."',
						pais            = '".$atributos['pais']."',
						codigo_postal   = '".$atributos['codigo_postal']."'
					WHERE asociado_id   = ".$atributos['asociado_id']."
				");
			}

			if ($queryExitosa)
				return 1;
			else
				return 'No se pudo guardar.';
		}
	}

}
