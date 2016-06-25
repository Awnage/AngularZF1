<?php
/**
 * AngularZF1
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category    AngularZF1
 * @package     AngularZF1_Angular
 * @subpackage  View
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://www.rosina.me)
 * @license     http://opensource.org/licenses/bsd-license.php     New BSD License
 */

/**
 * @see AngularZF1_Js
 */
require_once "AngularZF1/Js.php";

/**
 * @see Zend_Registry
 */
require_once 'Zend/Registry.php';

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see AngularZF1_Angular_View_Helper_Js_Container
 */
require_once "AngularZF1/Angular/View/Helper/Js/Container.php";

/**
 * Js Helper. Functions as a stack for code and loads all Js dependencies.
 *
 * @uses 	   Zend_Json
 * @package    AngularZF1_Js
 * @subpackage View
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://www.rosina.me)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Angular_View_Helper_Js extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface
     */
    public $view;

   /**
     * Initialize helper
     *
     * Retrieve container from registry or create new container and store in
     * registry.
     *
     * @return void
     */
    public function __construct()
    {
        $registry = Zend_Registry::getInstance();
        if (!isset($registry[__CLASS__])) {
            require_once 'AngularZF1/Angular/View/Helper/Angular/Container.php';
            $container = new AngularZF1_Angular_View_Helper_Angular_Container();
            $registry[__CLASS__] = $container;
        }
        $this->_container = $registry[__CLASS__];
    }

    /**
     * Return Angular View Helper class, to execute Angular library related functions.
     *
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function Js()
    {
        return $this->_container;
    }

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return void
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        $this->_container->setView($view);
    }

    /**
     * Proxy to container methods
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     * @throws Zend_View_Exception For invalid method calls
     */
    public function __call($method, $args)
    {
        if (!method_exists($this->_container, $method)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception(sprintf('Invalid method "%s" called on Js view helper', $method));
        }

        return call_user_func_array(array($this->_container, $method), $args);
    }

    /**
     * Disable noConflict Mode of Js if this was previously enabled.
     *
     * @return void
     */
    public static function disableNoConflictMode()
    {
    	self::$noConflictMode = false;
    }

    /**
     * Return current status of the Js no Conflict Mode
     *
     * @return Boolean
     */
    public static function getNoConflictMode()
    {
    	return self::$noConflictMode;
    }

    /**
     * Return current Js handler based on noConflict mode settings.
     *
     * @return String
     */
    public static function getJsHandler()
    {
        return ((self::getNoConflictMode()==true)?'$j':'$');
    }
}
