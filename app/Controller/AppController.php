<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    

//=========================================================================


    public $components = array(
        'Session',
        'Flash',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'asociados',
                'action' => 'login'
            ),
            'loginRedirect' => array(
                'controller' => 'asociados',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'asociados',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
            'authorize' => array('Controller')
        )
    );
    

//=========================================================================


    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['tipo']) && $user['tipo'] === 'CCM'){
            return true;
        }

        // Default deny
        return false;
    }
    

//=========================================================================


    public function beforeFilter() {
        $this->Auth->allow('');
    }
    

//=========================================================================


    function cicloActual()
    {
        date_default_timezone_set('America/Mexico_City');
         
        return date('Y');
    }
    

//=========================================================================


    function fechaHoy()
    {
        date_default_timezone_set('America/Mexico_City');
         
        return date("d/m/Y");
    }


//=========================================================================


    function paquetesEnSession()
    {
        $this->loadModel("Paquete");
        $this->loadModel("ArticulosPaquete");

        $carrito = $this->Session->read("Carrito");
        if ($carrito)
        {
            $mismos = array_count_values($carrito);

            $ids = array();

            foreach ($carrito as $key => $paquete_id)
            {
                array_push($ids, $paquete_id);
            }

            $condiciones = array('id' => $ids);

            $paquetes = $this->Paquete->traerPaquetes($condiciones);

            foreach ($paquetes as $key => $paquete)
            {
                $condiciones = array('Paquete.id' => $paquete["Paquete"]["id"]);
                $precios = $this->ArticulosPaquete->precios($condiciones);
                $paquetes[$key]["Precios"] = $precios;
            }
        }
        else
            $paquetes = array();

        return @array($mismos, $paquetes);
    }
}
