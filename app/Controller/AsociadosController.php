<?php
App::uses('AppController', 'Controller');	
App::uses('CakeEmail', 'Network/Email');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Asociados Controller
 *
 */
class AsociadosController extends AppController {


//=========================================================================


	public function isAuthorized($user)
	{
		//Acceso para Todos
		if (in_array($this->action, array('login', 'logout', 'activar', 'agregar_padre', 'olvide_contrasena', 'resetear_contra', 'cambiar_contrasena')))
		{
            if (isset($user['tipo']) && in_array($user['tipo'], array("Padre")))
    		{
    			return true;
    		}
        }

	    return parent::isAuthorized($user);
    }
	

//=========================================================================


	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('logout', 'agregar_padre', 'activar', 'olvide_contrasena', 'resetear_contra');
	}
	

//=========================================================================


	function mailEnUso($mail)
	{
		$condiciones = array('Asociado.mail' => $mail);
		$mail_en_uso = $this->Asociado->traerAsociados($condiciones);

		return $mail_en_uso;
	}
	

//=========================================================================


	public function login()
	{
		$this->layout ="login";
		$this->loadModel("EstatusTienda");

		$activo = $this->EstatusTienda->valor();

		if ($this->Session->check('Auth')) $this->redirect('/asociados/logout');	

		if ($this->request->is('post'))
		{ 
		    if ($this->Auth->login())
			{
				if ($this->Session->read("Auth.User.activo") == "0")
				{
					$this->Session->setFlash('Active su usuario siguiendo las instrucciones que se le enviaron a su correo.');
					$this->Auth->logout();
				}
				else
				{
					if ($this->Session->read("Auth.User.tipo") != "CCM" && !$activo)
					{
						$this->Session->destroy();
						$this->redirect(array('controller' => 'settings' , 'action' => 'tienda_cerrada'));
					}

					if ($this->Session->read("Auth.User.tipo") == "Director")
						$this->redirect(array('controller' => 'corte_cajas' , 'action' => 'index'));

					if ($this->Session->read("Auth.User.tipo") == "Padre")
						$this->redirect(array('controller' => 'hijos' , 'action' => 'seleccionar_hijo'));
					else
						$this->redirect(array('controller' => 'cajas' , 'action' => 'index'));
				}
			}
	        else
	        	$this->Session->setFlash('Usuario o contraseña incorrecta.');
		}
	}
	

//=========================================================================


	public function logout()
	{
		$this->Session->destroy();
	    $this->redirect($this->Auth->logout());
	}
	

//=========================================================================


	public function index()
	{
		$this->layout = 'admin';

		$condiciones = array();

		if ($this->Session->read("Auth.User.tipo") != 'CCM')
		{
			$condiciones['colegio_id'] = $this->Session->read("Auth.User.colegio_id");
		}

		$asociados = $this->Asociado->traerAsociados($condiciones);

		$this->set("asociados", $asociados);
	}
	

//=========================================================================


	public function activar($token = null)
	{
		$activado = $this->Asociado->activarAsociado($token);
		
		if ($activado)
		{
			$this->Session->setFlash('Usuario activado correctamente.');
			$this->redirect(array('controller' => 'hijos', 'action' => 'seleccionar_hijo'));
		}
		else
		{
			$this->Session->setFlash('Usuario inválido.');
			$this->redirect(array('action' => 'login'));
		}
	}
	

//=========================================================================


	public function agregar_padre()
	{
		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$mail_en_uso = $this->mailEnUso($data["Asociado"]["mail"]);

			if (!$mail_en_uso)
			{
				$token = $this->Asociado->token();
				$contra_original = $data['Asociado']['password'];

				$blowF = new BlowfishPasswordHasher();
				$contra_encr = $blowF->hash($contra_original);
				$data['Asociado']['password'] = $contra_encr;
				$data['Asociado']['tipo'] = "Padre";
				$data['Asociado']['token'] = $token;
				$data['Asociado']['activo'] = "cero";

				$guardado = $this->Asociado->guardarEnBDD($data['Asociado'], "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
					$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER["HTTP_HOST"]."/asociados/activar/$token";

					$Email = new CakeEmail();
					$Email->template('default', 'agregado');
					$Email->emailFormat('html');
					$Email->config('smtp');
					$Email->to($data["Asociado"]["mail"]);
					$Email->subject('Activación de tu cuenta, en el portal CCM.');
					$Email->viewVars(array(
						'usu_username' => $data["Asociado"]["mail"],
						'nombre' => $data["Asociado"]["nombre"],
						'a_paterno' => $data["Asociado"]["a_paterno"],
						'a_materno' => $data["Asociado"]["a_materno"],
						'contra' => $contra_original,
						'url' => $url
					));
					$Email->send();

			    	$this->Session->setFlash('Se le mandó un correo electrónico, siga las instrucciones.');
				}
			}
			else
				$this->Session->setFlash('Ese correo electrónico ya esta en uso.');
		}
	}
	

//=========================================================================


	public function agregar_director()
	{
		$this->layout = 'admin';

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$mail_en_uso = $this->mailEnUso($data["Asociado"]["mail"]);

			if (!$mail_en_uso)
			{
				$token = $this->Asociado->token();
				$contra_original = $data['Asociado']['password'];

				$blowF = new BlowfishPasswordHasher();
				$contra_encr = $blowF->hash($contra_original);
				$data['Asociado']['password'] = $contra_encr;
				$data['Asociado']['tipo'] = "Director";
				$data['Asociado']['token'] = $token;

				$guardado = $this->Asociado->guardarEnBDD($data['Asociado'], "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Director agregado exitosamente.');
			    	$this->redirect(array('controller' => 'colegios', 'action' => 'agregar'));
				}
			}
			else
				$this->Session->setFlash('Ese correo electrónico ya esta en uso.');
		}
	}
	

//=========================================================================


	public function agregar()
	{
		$this->layout = 'admin';

		$this->loadModel("Colegio");

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$mail_en_uso = $this->mailEnUso($data["Asociado"]["mail"]);

			if (!$mail_en_uso)
			{
				$token = $this->Asociado->token();
				$contra_original = $data['Asociado']['password'];

				$blowF = new BlowfishPasswordHasher();
				$contra_encr = $blowF->hash($contra_original);
				$data['Asociado']['password'] = $contra_encr;
				$data['Asociado']['token'] = $token;

				$guardado = $this->Asociado->guardarEnBDD($data['Asociado'], "agregar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Asociado agregado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
			else
				$this->Session->setFlash('Ese correo electrónico ya esta en uso.');
		}

		$colegios = $this->Colegio->todosColegios(array());
		
		$this->set("colegios", $colegios);
	}
	

//=========================================================================


	public function editar($id = null)
	{
		$this->layout = 'admin';

		$this->loadModel("Colegio");

		$asociado = $this->Asociado->traerAsociados(array('id' => $id));

		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$mail_en_uso = $this->mailEnUso($data["Asociado"]["mail"]);

			if (!$mail_en_uso || $data["Asociado"]["mail"] === $asociado[0]["Asociado"]["mail"])
			{
				$token = $this->Asociado->token();

				if ($data["Asociado"]["password"] != $asociado[0]["Asociado"]["password"])
				{
					$contra_original = $data['Asociado']['password'];

					$blowF = new BlowfishPasswordHasher();
					$contra_encr = $blowF->hash($contra_original);
					$data['Asociado']['password'] = $contra_encr;
				}
				
				if (in_array($data["Asociado"]["tipo"], array("CCM", "Padre")))
					$data["Asociado"]["colegio_id"] = 0;

				$data['Asociado']['token'] = $token;

				$guardado = $this->Asociado->guardarEnBDD($data['Asociado'], "editar");

				if ($guardado != 1)
					$this->Session->setFlash($guardado);
				else
				{
			    	$this->Session->setFlash('Asociado guardado exitosamente.');
			    	$this->redirect(array('action' => 'index'));
				}
			}
			else
				$this->Session->setFlash('Ese correo electrónico ya esta en uso.');
		}

		$colegios = $this->Colegio->todosColegios(array());
		
		$this->set("colegios", $colegios);
		$this->set("asociado", $asociado[0]["Asociado"]);
	}
	

//=========================================================================


	public function activo_actualizar()
	{
		$this->layout='ajax';

		$asociado_id = $this->request->data["asociado_id"];
		$activo = $this->request->data["activo"];

		if ($activo == 1)
			$activo = 0;
		else
			$activo = 1;

		$this->Asociado->query("
			UPDATE ccm.asociados
			SET activo = $activo
			WHERE id = $asociado_id
		");

		$asociado["Asociado"]["id"] = $asociado_id;
		$asociado["Asociado"]["activo"] = $activo;
		$this->set("asociado", $asociado);
	}
	

//=========================================================================


	public function olvide_contrasena()
	{
		$this->layout = 'login';

		if ($this->request->is('post'))
		{
			$mail = $this->request->data['Asociado']['mail'];

			$asociado = $this->Asociado->traerAsociados(array('mail' => $mail));

		    if (empty($asociado)) {
		    	$this->Session->setFlash('Usuario no existente.');
		    }
		    else
		    {
			    $token = $asociado[0]['Asociado']['token'];
			    $nombre = $asociado[0]['Asociado']['nombre'];
			    $a_paterno = $asociado[0]['Asociado']['a_paterno'];
			    $a_materno = $asociado[0]['Asociado']['a_materno'];

				$nueva_contra = $this->Asociado->token();

				$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER["HTTP_HOST"]."/asociados/resetear_contra/$token/$nueva_contra";

				$Email = new CakeEmail();
				$Email->template('default', 'recuperar_contra');
				$Email->emailFormat('html');
				$Email->config('smtp');
				$Email->to($mail);
				$Email->subject('Cambio de Contraseña');
				$Email->viewVars(array(
					'mail' => $mail,
					'nombre' => $nombre,
					'a_paterno' => $a_paterno,
					'a_materno' => $a_materno,
					'nueva_contra' => $nueva_contra,
					'url' => $url
				));
				$Email->send();

		    	$this->Session->setFlash('Se le mandó un correo electrónico, siga las instrucciones.');
		    }
		}
	}
	

//=========================================================================


	public function resetear_contra($token = null, $nueva_contra = null)
	{
		$asociado = $this->Asociado->traerAsociados(array('token' => $token));			

		if ($asociado)
		{	
			$nuevo_token = $this->Asociado->token();

			$blowF = new BlowfishPasswordHasher();
			$contra_encr = $blowF->hash($nueva_contra);

			$this->Asociado->query("
				UPDATE ccm.asociados
				SET token = '$nuevo_token',
					password = '$contra_encr'
				WHERE token = N'$token'
			");

			$this->redirect(array('action' => 'login'));
		}
		else
			$this->redirect(array('action' => 'login'));
	}
	

//=========================================================================


	public function cambiar_contrasena()
	{
		$this->layout = "login";

		if (!empty($this->request->data))
		{
			$asociado_id = $this->Session->read('Auth.User.id');

			$asociado = $this->Asociado->traerAsociados(array('id' => $asociado_id));	

		    $contra_bdd = $asociado[0]["Asociado"]["password"];
		    $contra_actual = $this->request->data("Asociado.actual");
		    $contra_nueva = $this->request->data("Asociado.nueva");

		    //Verifica que la contraseña dada sea la misma que la de la base de datos
		    $blowF = new BlowfishPasswordHasher();
			if ($blowF->check($contra_actual, $contra_bdd))
	        {
	        	//Checa que la nueva contraseña sea alfanumerica
	        	if(preg_match($this->Asociado->regex(), $contra_nueva))
					$this->Session->setFlash('Contraseña nueva solo letras y números.');
				else
				{
		        	//Checa que tenga más de 8 caracteres
		        	if (strlen($contra_nueva) < 8)
	   					$this->Session->setFlash('Mínimo 8 caracteres.');
	   				else
	   				{
	   					//Checa que tenga menos de 20 caracteres
			        	if (strlen($contra_nueva) > 20)
		   					$this->Session->setFlash('Máximo 20 caracteres.');
		   				else
		   				{
		   					//Esta validado, ahora si se hace el cambio
		   					$contra_encr = $blowF->hash($contra_nueva);
							$this->Asociado->query("
								UPDATE ccm.asociados
								SET password = '$contra_encr'
								WHERE id = $asociado_id
							");

							$this->redirect(array('controller' => 'hijos' , 'action' => 'seleccionar_hijo'));
		   				}
	   				}
				}
	        }
	        else
	        {
	        	$this->Session->setFlash('Contraseña actual incorrecta.');
	        }
		}
	}
}
