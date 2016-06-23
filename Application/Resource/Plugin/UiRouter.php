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
 * @copyright  Copyright (c) 2012 Gregory Wilson (http://www.drakos7.net) and Rosina Bignall (http://rosinabignall.com)
 * @license     http://opensource.org/licenses/bsd-license.php     New BSD License
 */


/**
 * @see AngularZF1_Angular
 */
require_once "AngularZF1/Angular.php";

/**
 * @see AngularZF1_Angular_Application_Resource_Plugin_Interface
 */
require_once "AngularZF1/Application/Resource/Plugin/Interface.php";

/**
 * Restangular Plugin
 *
 * @uses       AngularZF1_Angular
 * @package    AngularZF1_Angular_View_Helper_Angular_Plugin_Resource
 * @subpackage Application_Resource_Plugin
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Application_Resource_Plugin_UiRouter
    implements AngularZF1_Application_Resource_Plugin_Interface
{

    /**
     * Plugin Identifier
     * @const string Plugin Identifier
     */
    const IDENTIFIER = 'UiRouter';

    /**
     * @see https://cdnjs.com/libraries/angular-filter
     * @const string Base path to CDN
     */
    const CDN_BASE = 'https://cdnjs.cloudflare.com/ajax/libs/';

    /**
     * @const string
     */
    const CDN_SUBFOLDER = 'angular-ui-router/';

    /**
     * @const string
     */
    const DEFAULT_VERSION = '1.0.0-alpha.5';

    /**
     * Default uses compressed version, because this is assumed to be the use case
     * in production enviroment.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const MIN_PATH = '/angular-ui-router.min.js';

    /**
     * Non-compressed version.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const PATH = '/angular-ui-router.js';

    /**
     * Base URI
     * @var string
     */
    protected $_base = null;

    /**
     * Indicates whether the script resource should be added to all pages
     *
     * @var Boolean
     */
    protected $_enabled = false;

    /**
     * Indicates version to use
     *
     * @var string
     */
    protected $_version = self::DEFAULT_VERSION;

    /**
     *
     * @param <type> $options
     * @param <type> $angular
     * @return <type>
     */
    public function  __construct($options, $angular) {
        $this->_angular = $angular;
        foreach ($options as $key => $value) {
            switch(strtolower($key)) {
                case 'enable':
                    $this->_enable = (boolean) $value;
                break;
                case 'base':
                    $this->_base = (string) $value;
                break;
                case 'version':
                    $this->_version = (string) $value;
                    break;
            }
        }
    }

    /**
     * Enable when needed
     *
     * @return AngularZF1_Application_Resource_Plugin_UiRouter
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable unless specifically added
     *
     * @return AngularZF1_Application_Resource_Plugin_UiRouter
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Is Angular Resource enabled?
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->_enable;
    }

    /**
     * Get the plugin identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }

    /**
     * Set the version of the library used.
     *
     * @param string $version
     * @return AngularZF1_Application_Resource_Plugin_UiRouter
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
     * Renders all javascript file related stuff of the Angular enviroment.
     *
     * @return string
     */
    public function renderScriptTags()
    {
        $scriptTags = '';
        $source = $this->_getPath();
        $scriptTags .= '<script type="text/javascript" src="' . $source . '"></script>';
        return $scriptTags;
    }

    /**
     * Add scripts to a view
     *
     * @param view Zend_View_Interface
     * @return void
     */
    public function addScripts(Zend_View_Interface $view)
    {
        $view->headScript()->appendFile($this->_getPath());
    }


    /**
     * String representation of Angular environment
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_getPath();
    }

    /**
     * Internal function that constructs the include path of the Restangular library.
     *
     * @return string
     */
    protected function _getPath()
    {
        $source = $this->_getBasePath()
            . ($this->_angular->isMinified()==true? self::MIN_PATH : self::PATH);
        return $source;
    }

    /**
     * Internal function that constructs the include path of the Angular Material library.
     *
     * @return string
     */
    protected function _getBasePath()
    {
        if (null === $this->_base) {
            $baseUri = self::CDN_BASE .
                self::CDN_SUBFOLDER .
                $this->getVersion();
        } else {
            $baseUri = $this->_base;
        }

        return $baseUri;
    }

}
