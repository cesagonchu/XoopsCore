<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

/*
 General settings
 */
$modversion = array();
$modversion['name']           = _MI_MAINTENANCE_NAME;
$modversion['description']    = _MI_MAINTENANCE_DESC;
$modversion['version']        = 0.1;
$modversion['author']         = 'Mage Gregory,Cointin Maxime';
$modversion['nickname']       = 'Mage, Kraven30';
$modversion['credits']        = 'The XOOPS Project';
$modversion['license']        = 'GNU GPL 2.0';
$modversion['license_url']    = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official']       = 1;
$modversion['help']           = 'page=help';
$modversion['image']          = 'images/logo.png';
$modversion['dirname']        = 'maintenance';

/*
 Settings for configs
*/
$modversion['release_date']        = '2012/05/11';
$modversion['module_website_url']  = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'ALPHA';
$modversion['min_php'] = '5.3';
$modversion['min_xoops']           = '2.6.0';
$modversion['min_db']              = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

// paypal
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'xoopsfoundation@gmail.com';
$modversion['paypal']['item_name']     = 'Donation : ' . _MI_MAINTENANCE_DESC;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

/*
 Admin menu
 Set to 1 if you want to display menu generated by system module
*/
$modversion['system_menu'] = 1;

/*
 Manage extension
 */
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

/*
 Admin things
*/
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

/*
 JQuery
 Setting for load jquery library and library
*/
$modversion['jquery'] = 1;

/*
 Admin Templates
*/
$modversion['templates'][] = array( 'file' => 'maintenance_center.html', 'description' => '', 'type' => 'admin' );
$modversion['templates'][] = array( 'file' => 'maintenance_dump.html', 'description' => '', 'type' => 'admin' );