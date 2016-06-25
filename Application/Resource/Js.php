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
 * to drakos7@gmail.com so we can send you a copy immediately.
 *
 * @category   AngularZF1
 * @package    AngularZF1_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2012 Gregory Wilson (http://www.drakos7.net)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */

/**
 * Js application resource
 *
 * Example configuration:
 * <pre>
 *   resources.Js.enable = true
 *   resources.Js.render_mode = 255 ; default
 *   resources.Js.rendermode = 255 ; default
 *   resources.Js.minified = true
 *
 *   resources.Js.javascriptfile = "/some/file.js"
 *   resources.Js.javascriptfiles.0 = "/some/file.js"
 * </pre>
 *
 * Resource for settings Angular options
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   AngularZF1
 * @package    AngularZF1_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2012 Gregory Wilson (http://www.drakos7.net)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Application_Resource_Js
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var AngularZF1_Js_View_Helper_Js_Container
     */
    protected $_js;

    /**
     * @var Zend_View
     */
    protected $_view;

    /**
     * Contains registered plugins
     *
     * @var array
     */
    protected $_plugins = array();


    /**
     * Standard plugins
     *
     * @var array
     */
    public static $standardPlugins = array(
        'Lodash'
    );

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return AngularZF1_Angular_View_Helper_Js_Container
     */
    public function init()
    {
        return $this->getJs();
    }

    /**
     * Retrieve Angular View Helper
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function getJs()
    {
        if (null === $this->_js) {
            $this->getBootstrap()->bootstrap('view');
            $this->_view = $this->getBootstrap()->view;

            AngularZF1_Js::enableView($this->_view);
            $this->_parseOptions($this->getOptions());

            $this->_angular = $this->_view->angular();
        }

        return $this->_js;
    }

    /**
     * Parse options to find those pertinent to js helper and invoke them
     *
     * @param  array $options
     * @return void
     */
    protected function _parseOptions(array $options)
    {
        foreach ($options as $key => $value) {
            switch(strtolower($key)) {
                case 'minified':
                    $this->_view->Angular()->setMinified($value);
                    break;
                case 'render_mode':
                case 'rendermode':
                    $this->_view->Angular()->setRenderMode($value);
                    break;
                case 'javascriptfile':
                    $this->_view->Angular()->addJavascriptFile($value);
                    break;
                case 'javascriptfiles':
                    foreach($options['javascriptfiles'] as $file) {
                        $this->_view->Angular()->addJavascriptFile($file);
                    }
                    break;
                case 'plugin':
                    $this->_registerPlugins($value);
                    break;
            }
        }

        if ((isset($options['enable']) && (bool) $options['enable'])
           || !isset($options['enable']))
        {
            $this->_view->Js()->enable();
        } else {
            $this->_view->Js()->disable();
        }
    }


    /**
     * Load plugins set in config option
     *
     * @return void;
     */
    protected function _registerPlugins($plugins)
    {
        $angular = $this->_view->Js();

        foreach ($plugins as $plugin => $options) {
            // Register an instance
            if (is_object($plugin) && in_array('AngularZF1_Application_Resource_Plugin_Interface', class_implements($plugin))) {
                $angular->registerPlugin($plugin);
                continue;
            }

            if (!is_string($plugin)) {
                throw new Exception("Invalid plugin name", 1);
            }
            $plugin = ucfirst($plugin);

            // Register a classname
            if (in_array($plugin, AngularZF1_Application_Resource_Js::$standardPlugins)) {
                // standard plugin
                $pluginClass = 'AngularZF1_Application_Resource_Plugin_' . $plugin;
            } else {
                // we use a custom plugin
                if (!preg_match('~^[\w]+$~D', $plugin)) {
                    throw new Zend_Exception("AngularZF1: Invalid Js plugin name [$plugin]");
                }
                $pluginClass = $plugin;
            }

            require_once str_replace('_', DIRECTORY_SEPARATOR, $pluginClass) . '.php';
            $object = new $pluginClass($options, $angular);
            $angular->registerPlugin($object);
        }
    }



}
