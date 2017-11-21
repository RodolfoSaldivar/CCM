<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	

//=========================================================================


	var $useDbConfig = 'sql_local';

	public $recursive = -1;
	

//=========================================================================


	public function encriptacion($id)
	{
		$id_encriptada = base64_encode(base64_encode(base64_encode($id)));
		$id_encriptada = substr($id_encriptada, 0, -1);

		return $id_encriptada;
	}
	

//=========================================================================


	public function desencriptacion($id)
	{
		$id = $id."=";
		$id_desencriptada =	base64_decode(base64_decode(base64_decode($id)));

		return $id_desencriptada;
	}
	

//=========================================================================


	public function regex()
	{
		return '/[^a-z_\ñáéíóú\ÑÁÉÍÓÚ\-0-9\ \/\$\.\;\:\,\_\-\@\!\#\%\&\(\)\?\¿\¡\{\}\+\*\<\>\=]/i';
	}
	

//=========================================================================


	public function validarInputs($tabla = null)
	{
		$regex = $this->regex();

		//Valida que todos los campos esten llenos
		$todos_llenos = !array_search("", $tabla) !== false;

		if (!$todos_llenos)
			return "Favor de llenar todos los campos.";
		else
		{
			//Checa que los campos no tengan caracteres inválidos
			$valido = 1;

			foreach ($tabla as $atributo => $valor)
				if(preg_match($regex, $valor)) $valido = 0;

			if ($valido == 0)
				return "Solo letras y números.";
			else
			{
				//Paso las condiciones
				return 1;
			}
		}	
	}
	

//=========================================================================


	public function validarBuscador($arreglo = null)
	{
		$regex = $this->regex();

		//Checa que los campos no tengan carácteres inválidos
		$valido = 1;

		foreach ($arreglo as $campo => $valor)
			if(preg_match($regex, $valor)) $valido = 0;

		if ($valido == 0)
			return 0;
		else
		{
			//Paso las condiciones
			return 1;
		}
	}
	

//=========================================================================


	public function token()
	{
		$token = substr(
				str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20
			) .
			substr(
				str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20
			);
		return $token;
	}

}
