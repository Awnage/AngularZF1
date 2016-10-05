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
 * Angular-ui Plugin
 *
 * @uses       AngularZF1_Angular
 * @package    AngularZF1_Angular_View_Helper_Angular_Plugin_Resource
 * @subpackage Application_Resource_Plugin
 * @copyright  Copyright (c) 2012 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Application_Resource_Plugin_FileSaver
    implements AngularZF1_Application_Resource_Plugin_Interface
{

    /**
     * Plugin Identifier
     * @const string Plugin Identifier
     */
    const IDENTIFIER = 'FileSaver';

    /**
     * Plugin Identifier
     * @const string Plugin Identifier
     */
    const DEFAULT_VERSION = null;
    /**
     * Base name of the file. Naming convention is <path>-<version><extension>
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base
     */
    const PATH = '/angular-file-saver';

    /**
     * Default uses compressed version, because this is assumed to be the use case
     * in production enviroment.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const MIN_EXT = '.min.js';

    /**
     * Non-compressed version.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const EXT = '.js';

    /**
     * Default Base URI
     *
     * @const string Base uri
     */
    const DEFAULT_BASE_URI = '/js';

    /**
     * Base URI
     * @var string
     */
    protected $_base = null;

    /**
     * Base name of the file (default: self::PATH)
     *
     * @var string
     */
    protected $_path = self::PATH;

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
                case 'path':
                    $this->_path = (string) $value;
                    break;
                case 'version':
                    $this->setVersion($value);
                    break;
            }
        }
    }

    /**
     * Enable the plugin
     *
     * @return AngularZF1_Application_Resource_Plugin_FileSaver
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable the plugin unless specifically added
     *
     * @return AngularZF1_Application_Resource_Plugin_FileSaver
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Is enabled?
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

    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Set the version of the library used. (currently unused)
     *
     * @param string $version
     * @return AngularZF1_Application_Resource_Plugin_FileSaver
     */
    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }

    /**
     * Get the version used with the library
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
        // angular-FileSaver depends on these modules as well
        // Note: these are currently not implemented. If the bundle is not used then these need to be implemented
        //$view->jsFileSaver();
        //$view->jsBlob();

        // Add the angular-filesaver script
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
     * Internal function that constructs the include path of the Angular-resource library.
     *
     * @return string
     */
    protected function _getPath()
    {
        // use the angular base uri if uri is not defined
        if (null === $this->_base) {
            $baseUri = self::DEFAULT_BASE_URI;
        } else {
            $baseUri = $this->_base;
        }
        $version = $this->getVersion();

        $source = $baseUri
            . $this->_path
            . ($version != null ? '-' . $version : '')
            . ($this->_angular->isMinified()==true? self::MIN_EXT : self::EXT);
        return $source;
    }

    /**
     * Internal function that constructs the include path of the Angular-resource css.
     *
     * @return string
     */
    protected function _getCssPath()
    {
        // use the angular base uri if uri is not defined
        if (null === $this->_base) {
            $baseUri = self::DEFAULT_BASE_URI;
        } else {
            $baseUri = $this->_base;
        }

        $source = $baseUri
            . ($this->_angular->isMinified() == true ? self::MIN_CSS : self::CSS);
        return $source;
    }

}
