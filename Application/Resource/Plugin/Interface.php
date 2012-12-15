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
 * Interface for AngularZF1 Plugins
 *
 * @uses       AngularZF1_Angular_View_Helper_Angular_Plugin_Interface
 * @package    AngularZF1_Angular
 * @subpackage Application_Resource_Plugin
 * @copyright  Copyright (c) 2012 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
interface AngularZF1_Application_Resource_Plugin_Interface
{
    /**
     * Get the plugin identifier
     * 
     * @return string
     */
    public function getIdentifier();

    /**
     * Is the plugin enabled?
     *
     * @return boolean
     */
    public function isEnabled();


    /**
     * Renders all script tags related to the plugin
     *
     * @return string
     */
    public function renderScriptTags();


    /**
     * Add scripts to a view
     *
     * @param view Zend_View_Interface
     * @return void
     */
    public function addScripts(Zend_View_Interface $view);

}
