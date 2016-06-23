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
 * @see Zend_Registry
 */
require_once 'Zend/Registry.php';

/**
 * @see AngularZF1_Angular_View_Helper_Angular_Container
 */
require_once "AngularZF1/Angular/View/Helper/Angular/Container.php";

/**
 * Angular Helper. Functions as a stack for code and loads all Angular dependencies.
 *
 * @package    AngularZF1_Angular
 * @subpackage View
 * @copyright  Copyright (c) 2016 Rosina Bignall (http://rosinabignall.com)
 * @license    http://opensource.org/licenses/bsd-license.php     New BSD License
 */
class AngularZF1_Angular_View_Helper_AngularUiRouter extends Zend_View_Helper_Abstract
{

    const ANGULAR_REGISTRY = 'AngularZF1_Angular_View_Helper_Angular';

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
        if (!isset($registry[self::ANGULAR_REGISTRY])) {
            require_once 'AngularZF1/Angular/View/Helper/Angular/Container.php';
            $container = new AngularZF1_Angular_View_Helper_Angular_Container();
            $registry[self::ANGULAR_REGISTRY] = $container;
        }
        $this->_container = $registry[self::ANGULAR_REGISTRY];
    }

    /**
     * Return Angular View Helper class, to execute Angular library related functions.
     *
     * @return void
     */
    public function angularUiRouter()
    {
        $plugin = $this->_container->getPlugin('UiRouter');
        if ($plugin) {
            $plugin->addScripts($this->view);
        }
        return ;
    }


}
