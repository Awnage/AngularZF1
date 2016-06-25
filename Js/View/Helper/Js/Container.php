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
 * @package     AngularZF1_Js
 * @subpackage  View
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://www.rosina.me)
 * @license     http://opensource.org/licenses/bsd-license.php     New BSD License
 */


/**
 * @see AngularZF1_Js
 */
require_once "AngularZF1/Js.php";

/**
 * Js View Helper. Transports all Js stack and render information across all views.
 *
 * @uses       AngularZF1_Js_View_Helper_Angular_Container
 * @package    AngularZF1_Js
 * @subpackage View
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://www.rosina.me)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Js_View_Helper_Js_Container
{

    /**
     * Additional javascript files that for Angular Helper components.
     *
     * @var Array
     */
    protected $_javascriptSources = array();

    /**
     * Indicates wheater the Js View Helper is enabled.
     *
     * @var Boolean
     */
    protected $_enabled = false;

    /**
     * Indicates if a capture start method for javascript or onLoad has been called.
     *
     * @var Boolean
     */
    protected $_captureLock = false;

    /**
     * Additional javascript statements that need to be executed after Js lib.
     *
     * @var Array
     */
    protected $_javascriptStatements = array();

    /**
     * Js onLoad statements Stack
     *
     * @var Array
     */
    protected $_onLoadActions = array();

    /**
     * View is rendered in XHTML or not.
     *
     * @var Boolean
     */
    protected $_isXhtml = false;

    /**
     * Dev or minified version?
     *
     * @var String
     */
    protected $_minified = true;

    /**
     * Default Render Mode (all parts)
     *
     * @var Integer
     */
    protected $_renderMode = AngularZF1_Js::RENDER_ALL;

    /**
     * View Instance
     *
     * @var Zend_View_Interface
     */
    public $view = null;

    /**
     * Registered Plugins
     *
     * @var array AngularZF1_Application_Resource_Plugin_Interface
     */
    public $_plugins = array();

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return void
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Get view object
     *
     * @return Zend_View_Interface
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Enable Js
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable Angular
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Is Angular enabled?
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Set whether to use the minified or dev version
     *
     * @param boolean $bool
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function setMinified($bool)
    {
        $this->_minified = ($bool==true);
        return $this;
    }
    
    /**
     * Use the minified version (true) or dev version (false)?
     *
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function isMinified()
    {
        return $this->_minified;
    }

    /**
     * Start capturing routines to run onLoad
     *
     * @return boolean
     */
    public function onLoadCaptureStart()
    {
        if ($this->_captureLock) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception('Cannot nest onLoad captures');
        }

        $this->_captureLock = true;
        return ob_start();
    }

    /**
     * Stop capturing routines to run onLoad
     *
     * @return boolean
     */
    public function onLoadCaptureEnd()
    {
        $data               = ob_get_clean();
        $this->_captureLock = false;

        $this->addOnLoad($data);
        return true;
    }

    /**
     * Capture arbitrary javascript to include
     *
     * @return boolean
     */
    public function javascriptCaptureStart()
    {
        if ($this->_captureLock) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception('Cannot nest captures');
        }

        $this->_captureLock = true;
        return ob_start();
    }

    /**
     * Finish capturing arbitrary javascript to include in Angular script
     *
     * @return boolean
     */
    public function javascriptCaptureEnd()
    {
        $data               = ob_get_clean();
        $this->_captureLock = false;

        $this->addJavascript($data);
        return true;
    }

    /**
     * Add a Javascript File to the include stack.
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function addJavascriptFile($path)
    {
        $path = (string) $path;
        if (!in_array($path, $this->_javascriptSources)) {
            $this->_javascriptSources[] = (string) $path;
        }
        return $this;
    }

    /**
     * Return all currently registered Javascript files.
     *
     * @return Array
     */
    public function getJavascriptFiles()
    {
        return $this->_javascriptSources;
    }

    /**
     * Clear all currently registered Javascript files.
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function clearJavascriptFiles()
    {
        $this->_javascriptSources = array();
        return $this;
    }

    /**
     * Add arbitrary javascript to execute in Angular JS container
     *
     * @param  string $js
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function addJavascript($js)
    {
        $this->_javascriptStatements[] = $js;
        $this->enable();
        return $this;
    }

    /**
     * Return all registered javascript statements
     *
     * @return array
     */
    public function getJavascript()
    {
        return $this->_javascriptStatements;
    }

    /**
     * Clear arbitrary javascript stack
     *
	 * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function clearJavascript()
    {
        $this->_javascriptStatements = array();
        return $this;
    }

    /**
     * Add a script to execute onLoad
     *
     * @param  string $callback Lambda
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function addOnLoad($callback)
    {
        if (!in_array($callback, $this->_onLoadActions, true)) {
            $this->_onLoadActions[] = $callback;
        }
        $this->enable();
        return $this;
    }

    /**
     * Retrieve all registered onLoad actions
     *
     * @return array
     */
    public function getOnLoadActions()
    {
        return $this->_onLoadActions;
    }

    /**
     * Clear the onLoadActions stack.
     *
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function clearOnLoadActions()
    {
        $this->_onLoadActions = array();
        return $this;
    }

    /**
     * Set which parts of the Js enviroment should be rendered.
     *
     * This function allows for a gradual refactoring of the Js code
     * rendered by calling __toString(). Use AngularZF1_Js::RENDER_*
     * constants. By default all parts of the enviroment are rendered.
     *
     * @see    AngularZF1_Js::RENDER_ALL
     * @param  integer $mask
     * @return AngularZF1_Js_View_Helper_Js_Container
     */
    public function setRenderMode($mask)
    {
        $this->_renderMode = $mask;
        return $this;
    }

    /**
     * Return bitmask of the current Render Mode
     * @return integer
     */
    public function getRenderMode()
    {
        return $this->_renderMode;
    }

    /**
     * Register a new plugin
     *
     * @param AngularZF1_Application_Resource_Js_Plugin_Interface
     * @return AngularZF1_Application_Resource_Js
     */
    public function registerPlugin(AngularZF1_Application_Resource_Plugin_Interface $plugin)
    {
        $this->_plugins[$plugin->getIdentifier()] = $plugin;
        return $this;
    }

    /**
     * Get plugin
     *
     * @param string $name
     * @return  AngularZF1_Application_Resource_Js_Plugin_Interface
     */
    public function getPlugin($name)
    {
        return (isset($this->_plugins[$name]))? $this->_plugins[$name] : null;
    }

    /**
     * String representation of Js environment
     *
     * @return string
     */
    public function __toString()
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $this->_isXhtml = $this->view->doctype()->isXhtml();

        $html  = $this->_renderScriptTags() . PHP_EOL
               . $this->_renderExtras();
        return $html;
    }

    /**
     * Renders all javascript file related stuff of the Angular enviroment.
     *
     * @return string
     */
    protected function _renderScriptTags()
    {
        $scriptTags = '';
        foreach ($this->_plugins as $plugin) {
            if ($plugin->isEnabled()) {
                $scriptTags .= $plugin->renderScriptTags().PHP_EOL;
            }
        }

        return $scriptTags;
    }

    /**
     * Renders all javascript code related stuff of the Js enviroment.
     *
     * @return string
     */
    protected function _renderExtras()
    {
        $onLoadActions = array();
        if( ($this->getRenderMode() & AngularZF1_Js::RENDER_JS_ON_LOAD) > 0) {
            foreach ($this->getOnLoadActions() as $callback) {
                $onLoadActions[] = $callback;
            }
        }

        $javascript = '';
        if( ($this->getRenderMode() & AngularZF1_Js::RENDER_JAVASCRIPT) > 0) {
            $javascript = implode("\n    ", $this->getJavascript());
        }

        $content = '';

        if (!empty($onLoadActions)) {
            $content .= '$(document).ready(function() {'."\n    ";
            $content .= implode("\n    ", $onLoadActions) . "\n";
            $content .= '});'."\n";
        }

        if (!empty($javascript)) {
            $content .= $javascript . "\n";
        }

        if (preg_match('/^\s*$/s', $content)) {
            return '';
        }

        $html = '<script type="text/javascript">' . PHP_EOL
              . (($this->_isXhtml) ? '//<![CDATA[' : '//<!--') . PHP_EOL
              . $content
              . (($this->_isXhtml) ? '//]]>' : '//-->') . PHP_EOL
              . PHP_EOL . '</script>';
        return $html;
    }

}
