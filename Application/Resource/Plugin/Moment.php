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
require_once "AngularZF1/Js.php";

/**
 * @see AngularZF1_Angular_Application_Resource_Plugin_Interface
 */
require_once "AngularZF1/Application/Resource/Plugin/Interface.php";

/**
 * Lodash Plugin
 *
 * Lodash isn't an Angular module, but it's required by Restangular so we make it
 * easy by adding a plugin for it too
 *
 * @uses       AngularZF1_Angular
 * @package    AngularZF1_Angular_View_Helper_Angular_Plugin_Resource
 * @subpackage Application_Resource_Plugin
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Application_Resource_Plugin_Moment
    implements AngularZF1_Application_Resource_Plugin_Interface
{

    /**
     * Plugin Identifier
     * @const string Plugin Identifier
     */
    const IDENTIFIER = 'Moment';

    /**
     * @see http://www.jsdelivr.com/projects/momentjs
     * @const string Base path to CDN
     */
    const CDN_BASE = 'https://cdn.jsdelivr.net/';

    /**
     * @const string
     */
    const CDN_SUBFOLDER = 'momentjs/';

    /**
     * @const string
     */
    const DEFAULT_VERSION = '2.14.1';

    /**
     * Default uses compressed version, because this is assumed to be the use case
     * in production enviroment.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const MIN_PATH = '/moment.min.js';
    const MIN_WITH_LOCALES_PATH = '/moment-with-locales.min.js';
    const MIN_LOCALES_PATH = '/locales.min.js';

    /**
     * Non-compressed version.
     *
     * @see https://developers.google.com/speed/libraries/devguide#angularjs
     * @const string File path after base and version
     */
    const PATH = '/moment.js';
    const WITH_LOCALES_PATH = '/moment-with-locals.js';
    const LOCALES_PATH = '/locales.js';

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
     * Indicates whether to use the locales version
     *
     * @var string
     */
    protected $_locales = false;

    /**
     *
     * @param <type> $options
     * @param <type> $angular
     * @return <type>
     */
    public function  __construct($options, $js) {
        $this->_js = $js;
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
                case 'locales':
                    $this->_locales = (bool) $value;
                    break;
            }
        }
    }

    /**
     * Enable MomentJs when needed
     *
     * @return AngularZF1_Application_Resource_Plugin_Moment
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable MomentJs unless specifically added
     *
     * @return AngularZF1_Application_Resource_Plugin_Moment
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Is MomentJs enabled?
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
     * Set the version of the MomentJs library used.
     *
     * @param string $version
     * @return AngularZF1_Application_Resource_Plugin_Moment
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
        $sources = $this->_getPath();
        foreach($sources as $source) {
            $scriptTags .= '<script type="text/javascript" src="' . $source . '"></script>';
        }
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
        $sources = $this->_getPath();
        foreach($sources as $source) {
            $view->headScript()->appendFile($source);
        }
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
        $basePath = $this->_getBasePath();
        if ($this->_locales) {
            $source = array(
                $basePath . ($this->_js->isMinified()==true? self::MIN_WITH_LOCALES_PATH : self::WITH_LOCALES_PATH),
                $basePath . ($this->_js->isMinified()==true? self::MIN_WITH_LOCALES_PATH : self::WITH_LOCALES_PATH),
            );
        } else {
            $source = array($basePath . ($this->_js->isMinified()==true? self::MIN_PATH : self::PATH));
        }
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
