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
 * @copyright  Copyright (c) 2012 Gregory Wilson (http://www.drakos7.net)
 * @license     http://opensource.org/licenses/bsd-license.php     New BSD License
 */


/**
 * @see AngularZF1_Angular
 */
require_once "AngularZF1/Angular.php";

/**
 * Angular View Helper. Transports all Angular stack and render information across all views.
 *
 * @uses       AngularZF1_Angular_View_Helper_Angular_Container
 * @package    AngularZF1_Angular
 * @subpackage View
 * @copyright  Copyright (c) 2012 Gregory Wilson (http://www.drakos7.net)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Angular_View_Helper_Angular_Container
{
    /**
     * Path to local webserver Angular library
     *
     * @var String
     */
    protected $_angularLibraryPath = null;

    /**
     * Additional javascript files that for Angular Helper components.
     *
     * @var Array
     */
    protected $_javascriptSources = array();

    /**
     * Indicates wheater the Angular View Helper is enabled.
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
     * Additional javascript statements that need to be executed after Angular lib.
     *
     * @var Array
     */
    protected $_javascriptStatements = array();

    /**
     * Angular onLoad statements Stack
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
     * Default CDN Angular Library version
     *
     * @var String
     */
    protected $_version = AngularZF1_Angular::DEFAULT_ANGULAR_VERSION;

    /**
     * Default Render Mode (all parts)
     *
     * @var Integer
     */
    protected $_renderMode = AngularZF1_Angular::RENDER_ALL;

    /**
     * View Instance
     *
     * @var Zend_View_Interface
     */
    public $view = null;

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
     * Enable Angular
     *
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable Angular
     *
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * Set the version of the Angular library used.
     *
     * @param string $version
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }

    /**
     * Get the version used with the Angular library
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Use CDN, using version specified. Currently supported
     * by Googles Ajax Library API are: 1.2.3, 1.2.6
     *
     * @deprecated As of version 1.8, use {@link setVersion()} instead.
     * @param  string $version
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function setCdnVersion($version = null)
    {
        return $this->setVersion($version);
    }

    /**
     * Get CDN version
     *
     * @deprecated As of version 1.8, use {@link getVersion()} instead.
     * @return string
     */
    public function getCdnVersion()
    {
        return $this->getVersion();
    }

    /**
     * Are we using the CDN?
     *
     * @return boolean
     */
    public function useCdn()
    {
        return !$this->useLocalPath();
    }

    /**
     * Set path to local Angular library
     *
     * @param  string $path
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function setLocalPath($path)
    {
        $this->_angularLibraryPath = (string) $path;
        return $this;
    }

    /**
     * Get local path to Angular
     *
     * @return string
     */
    public function getLocalPath()
    {
        return $this->_angularLibraryPath;
    }

    /**
     * Are we using a local path?
     *
     * @return boolean
     */
    public function useLocalPath()
    {
        return (null === $this->_angularLibraryPath) ? false : true;
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
     * Capture arbitrary javascript to include in Angular script
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
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * This does not include the Angular library, which is handled by another retrieval
     * strategy.
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
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
	 * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * @return AngularZF1_Angular_View_Helper_Angular_Container
     */
    public function clearOnLoadActions()
    {
        $this->_onLoadActions = array();
        return $this;
    }

    /**
     * Set which parts of the Angular enviroment should be rendered.
     *
     * This function allows for a gradual refactoring of the Angular code
     * rendered by calling __toString(). Use AngularZF1_Angular::RENDER_*
     * constants. By default all parts of the enviroment are rendered.
     *
     * @see    AngularZF1_Angular::RENDER_ALL
     * @param  integer $mask
     * @return AngularZF1_Angular_View_Helper_Angular_Container
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
     * String representation of Angular environment
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
        if( ($this->getRenderMode() & AngularZF1_Angular::RENDER_LIBRARY) > 0) {
            $source = $this->_getAngularLibraryPath();

            $scriptTags .= '<script type="text/javascript" src="' . $source . '"></script>'.PHP_EOL;
        }

        if( ($this->getRenderMode() & AngularZF1_Angular::RENDER_SOURCES) > 0) {
            foreach($this->getJavascriptFiles() AS $javascriptFile) {
                $scriptTags .= '<script type="text/javascript" src="' . $javascriptFile . '"></script>'.PHP_EOL;
            }
        }

        return $scriptTags;
    }

    /**
     * Renders all javascript code related stuff of the Angular enviroment.
     *
     * @return string
     */
    protected function _renderExtras()
    {
        $onLoadActions = array();
        if( ($this->getRenderMode() & AngularZF1_Angular::RENDER_ANGULAR_ON_LOAD) > 0) {
            foreach ($this->getOnLoadActions() as $callback) {
                $onLoadActions[] = $callback;
            }
        }

        $javascript = '';
        if( ($this->getRenderMode() & AngularZF1_Angular::RENDER_JAVASCRIPT) > 0) {
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

    /**
     * @return string
     */
    protected function _getAngularLibraryBaseCdnUri()
    {
        return AngularZF1_Angular::CDN_BASE_GOOGLE;
    }


    /**
     * Internal function that constructs the include path of the Angular library.
     *
     * @return string
     */
    protected function _getAngularLibraryPath()
    {
        if($this->_angularLibraryPath != null) {
            $source = $this->_angularLibraryPath;
        } else {
            $baseUri = $this->_getAngularLibraryBaseCdnUri();
            $source = $baseUri .
                AngularZF1_Angular::CDN_SUBFOLDER_ANGULAR .
                $this->getCdnVersion() .
            	AngularZF1_Angular::CDN_ANGULAR_PATH_GOOGLE;
        }

        return $source;
    }

}
