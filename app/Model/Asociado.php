<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Asociado Model
 *
 */
class Asociado extends AppModel {

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'colegio_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Hijo' => array(
			'className' => 'Hijo',
			'foreignKey' => 'asociado_id',
			'dependent' => false
		),
		'Colegio' => array(
			'className' => 'Colegio',
			'foreignKey' => 'asociado_id',
			'dependent' => false
		),
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'padre_id',
			'dependent' => false
		),
		'CorteCaja' => array(
			'className' => 'CorteCaja',
			'foreignKey' => 'cajero_id',
			'dependent' => false
		)
	);
	

//=========================================================================


	public function traerAsociados($condiciones)
	{
		$asociados = $this->find('all', array(
			'conditions' => $condiciones,
			'fields' => array(
				'id', 'nombre', 'a_paterno', 'a_materno', 'mail', 'password', 'celular', 'colegio_id', 'tipo', 'activo', 'token'
			),
			'order' => array(
				'activo' => 'desc',
				'a_paterno' => 'asc',
				'a_materno' => 'asc',
				'nombre' => 'asc'
			)
		));

		return $asociados;
	}
	

//=========================================================================


	private function llenarAtributosVacios($atributos)
	{
		if (empty($atributos["nombre"]))     $atributos["nombre"]     = "NULL";
		if (empty($atributos["a_paterno"]))  $atributos["a_paterno"]  = "NULL";
		if (empty($atributos["a_materno"]))  $atributos["a_materno"]  = "NULL";
		if (empty($atributos["tipo"]))       $atributos["tipo"]       = "NULL";
		if (empty($atributos["mail"]))       $atributos["mail"]       = "NULL";
		if (empty($atributos["password"]))   $atributos["password"]   = "NULL";
		if (empty($atributos["celular"]))    $atributos["celular"]    = "NULL";

		if (empty($atributos["activo"]))     $atributos["activo"]     = "1";
		if (empty($atributos["colegio_id"])) $atributos["colegio_id"] = "cero";

		return $atributos;
	}
	

//=========================================================================


	private function quitarNulos($atributos)
	{
		if ($atributos["nombre"] == "NULL")     $atributos["nombre"]     = "";
		if ($atributos["a_paterno"] == "NULL")  $atributos["a_paterno"]  = "";
		if ($atributos["a_materno"] == "NULL")  $atributos["a_materno"]  = "";
		if ($atributos["tipo"] == "NULL")       $atributos["tipo"]       = "";
		if ($atributos["mail"] == "NULL")       $atributos["mail"]       = "";
		if ($atributos["password"] == "NULL")   $atributos["password"]   = "";
		if ($atributos["celular"] == "NULL")    $atributos["celular"]    = "";
		if ($atributos["activo"] == "cero")     $atributos["activo"]     = "0";
		if ($atributos["colegio_id"] == "cero") $atributos["colegio_id"] = "0";

		return $atributos;
	}
	

//=========================================================================


	public function guardarEnBDD($atributos, $accion)
	{
		$atributos = $this->llenarAtributosVacios($atributos);

		if ($atributos["tipo"] != "Padre") $atributos["token"] = $this->token();

		$valido = $this->validarInputs($atributos);

		if ($valido != 1)
			return $valido;
		else
		{	
			$atributos = $this->quitarNulos($atributos);

			if ($atributos["tipo"] == "Director")
				@$colegio_id = $atributos["colegio_id"];

			if ($accion == "agregar")
			{
				$queryExitosa = $this->query("
					INSERT INTO CCM.asociados
						(nombre, a_paterno, a_materno, tipo, mail, password, celular, token, activo,  colegio_id)
					VALUES (
						'".$atributos['nombre']."',
						'".$atributos['a_paterno']."',
						'".$atributos['a_materno']."',
						'".$atributos['tipo']."',
						'".$atributos['mail']."',
						'".$atributos['password']."',
						'".$atributos['celular']."',
						'".$atributos['token']."',
						".$atributos['activo'].",
						".$atributos['colegio_id']."
					)
				");
			}

			if ($accion == "editar")
			{
				$queryExitosa = $this->query("
					UPDATE CCM.asociados
					SET nombre     = '".$atributos['nombre']."',
						a_paterno  = '".$atributos['a_paterno']."',
						a_materno  = '".$atributos['a_materno']."',
						tipo       = '".$atributos['tipo']."',
						password   = '".$atributos['password']."',
						celular    = '".$atributos['celular']."',
						token      = '".$atributos['token']."',
						colegio_id = ".$atributos['colegio_id']."
					WHERE mail = '".$atributos['mail']."'
				");
			}

			if ($queryExitosa)
			{
				if ($atributos["tipo"] == "Director" && $colegio_id)
				{
					$asociado_id = $this->find('first', array(
						'conditions' => array('mail' => $atributos['mail']),
						'fields' => array('id')
					));
					$asociado_id = $asociado_id["Asociado"]["id"];

					$retorno = $this->query("
						UPDATE CCM.colegios
						SET asociado_id = $asociado_id
						WHERE id = $colegio_id
					");
				}

				return 1;
			}
			else
				return 'No se pudo guardar el asociado.';
		}
	}
	

//=========================================================================


	public function activarAsociado($token)
	{
		$existe = $this->find('first', array(
			'conditions' => array('token' => $token),
			'fields' => 'token'
		));

		if ($existe)
			$retorno = $this->query("
				UPDATE CCM.asociados
				SET activo = 1
				WHERE token = '$token'
			");
		else
			$retorno = 0;

		return $retorno;
	}


}
