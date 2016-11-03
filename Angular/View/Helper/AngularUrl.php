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
 * AngularUrl View Helper.
 * Helper for making easy links and getting urls that depend on the routes and router.
 * Generates urls allowing for {{ }} in the url to be used in angular apps.
 *
 * @package    AngularZF1_Angular
 * @subpackage View
 * @copyright  Copyright (c) 2012 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */

class AngularZF1_Angular_View_Helper_AngularUrl
{
   /**
     * Generates an url given the name of a route.
     *
     * @access public
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function angularUrl(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble($urlOptions, $name, $reset, $encode);
        
        //unencode {{ }} and :
        $url = str_replace('%7B%7B', '{{', $url);
        $url = str_replace('%7D%7D', '}}', $url);
        $url = str_replace('%3A', ':', $url);
        return $url;
    }   
    
}