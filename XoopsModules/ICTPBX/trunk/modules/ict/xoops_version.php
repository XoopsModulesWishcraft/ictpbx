<?php
/**
 * ICT-FAX Open Source Fax system based on ICT Innovations drupal modules
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       © 2012 ICT Innovations, All Rights Reserved
 * @copyright       © 2015 Chronolabs Cooperative, All Rights Reserved
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @subpackage      ictfax
 * @category      	foip/fax
 * @since           1.0.1
 * @author          Simon A. Roberts <wishcraft@users.sourceforge.net>
 * @author          Falak Nawaz <support@ictinnovations.com>
 * @author          Nasir Iqbal <support@ictinnovations.com>
 * @author          Tahir Almas <support@ictinnovations.com>
 * @see         	http://www.ictinnovations.com/
 * @see         	http://labs.coop/
 * @link			mailto:info@ictinnovations.com
 */

$modversion = array();
$modversion['name'] = "ICTFax Module";
$modversion['version'] = 1.01;
$modversion['description'] = "Provides Fax2Email and Email2Fax Functionality";
$modversion['author'] = "Simon A. Roberts; Falak Nawaz; Nasir Iqbal; Tahir Almas";
$modversion['credits'] = "wishcraft";
$modversion['license'] = "GPL see LICENSE";
$modversion['image'] = "images/ictfax.png";
$modversion['dirname'] = basename(__DIR__);
$modversion['website'] = "www.ictinnovations.com";

$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'modules/'.$modversion['dirname'].DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.'16';
$modversion['icons32'] = 'modules/'.$modversion['dirname'].DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.'32';

$modversion['release_info'] = "Stable 2015/08/07";
$modversion['release_file'] = XOOPS_URL.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$modversion['dirname'].DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR.'changelog.txt';
$modversion['release_date'] = "2015/08/07";

$modversion['author_realname'] = "Simon Antony Roberts";
$modversion['author_website_url'] = "http://labs.coop";
$modversion['author_website_name'] = "Chronolabs Australia";
$modversion['author_email'] = "simon@staff.labs.coop";
$modversion['demo_site_url'] = "http://2.5.xoops.demo.labs.coop";
$modversion['demo_site_name'] = "Chronolabs Demo Site";
$modversion['support_site_url'] = "http://sourceforge.net/projects/chronolabs/";
$modversion['support_site_name'] = "Chronolabs @ Source-forge";
$modversion['submit_bug'] = "http://sourceforge.net/projects/chronolabs/tickets";
$modversion['submit_feature'] = "http://sourceforge.net/projects/chronolabs/tickets";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";


// Admin things
$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = "admin/dashboard.php";
$modversion['adminmenu'] = "admin/menu.php";

// Scripts to run upon installation or update
// $modversion['onInstall'] = "include/install.php";
// $modversion['onUpdate'] = "include/update.php";

// Menu
$modversion['hasMain'] = 0;

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "profile_category";
$modversion['tables'][2] = "profile_profile";
$modversion['tables'][3] = "profile_field";
$modversion['tables'][4] = "profile_visibility";
$modversion['tables'][5] = "profile_regstep";
$modversion['tables'][6] = "proflile_validation";
$modversion['tables'][7] = "proflile_oauth";
$modversion['tables'][8] = "profile_oauth_server_token";
$modversion['tables'][9] = "profile_oauth_server_nonce";
$modversion['tables'][10] = "profile_oauth_server_registry";
$modversion['tables'][11] = "profile_oauth_consumer_token";
$modversion['tables'][12] = "profile_oauth_consumer_registry";
$modversion['tables'][13] = "profile_oauth_log";


// Comments
$modversion['hasComments'] = true;
$modversion['comments']['itemName'] = 'uid';
$modversion['comments']['pageName'] = 'userinfo.php';

//Blocks
$i=0;
$i++;
$modversion['blocks'][$i]['file'] = "linkedin_signin.php";
$modversion['blocks'][$i]['name'] = 'Sign-in with LinkedIN' ;
$modversion['blocks'][$i]['description'] = "Allows users to sign in with linkedin";
$modversion['blocks'][$i]['show_func'] = "b_profile_linkedin_block_signin_show";
$modversion['blocks'][$i]['edit_func'] = "b_profile_linkedin_block_signin_edit";
$modversion['blocks'][$i]['options'] = "";
$modversion['blocks'][$i]['template'] = "profile_linkedin_block_signin.html" ;

$i++;
$modversion['blocks'][$i]['file'] = "twitter_signin.php";
$modversion['blocks'][$i]['name'] = 'Sign-in with Twitter' ;
$modversion['blocks'][$i]['description'] = "Allows users to sign in with Twitter";
$modversion['blocks'][$i]['show_func'] = "b_profile_twitter_block_signin_show";
$modversion['blocks'][$i]['edit_func'] = "b_profile_twitter_block_signin_edit";
$modversion['blocks'][$i]['options'] = "";
$modversion['blocks'][$i]['template'] = "profile_twitter_block_signin.html" ;

$i++;
$modversion['blocks'][$i]['file'] = "facebook_signin.php";
$modversion['blocks'][$i]['name'] = 'Sign-in with Facebook' ;
$modversion['blocks'][$i]['description'] = "Allows users to sign in with Facebook";
$modversion['blocks'][$i]['show_func'] = "b_profile_facebook_block_signin_show";
$modversion['blocks'][$i]['edit_func'] = "b_profile_facebook_block_signin_edit";
$modversion['blocks'][$i]['options'] = "";
$modversion['blocks'][$i]['template'] = "profile_facebook_block_signin.html" ;

$i++;
$modversion['blocks'][$i]['file'] = "all_signin.php";
$modversion['blocks'][$i]['name'] = 'Sign-in with Social networks' ;
$modversion['blocks'][$i]['description'] = "Allows users to sign in with Social Networks";
$modversion['blocks'][$i]['show_func'] = "b_profile_all_block_signin_show";
$modversion['blocks'][$i]['edit_func'] = "b_profile_all_block_signin_edit";
$modversion['blocks'][$i]['options'] = "";
$modversion['blocks'][$i]['template'] = "profile_all_block_signin.html" ;

// Config items
$i=1;
$modversion['config'][$i]['name'] = 'profile_search';
$modversion['config'][$i]['title'] = '_PROFILE_MI_PROFILE_SEARCH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;


$modversion['config'][] = array(
	'name'			=> 'directory_title',
	'title' 		=> '_PROFILE_MI_TITLE',
	'description'	=> '_PROFILE_MI_TITLE_DESC',
	'formtype'		=> 'textbox',
	'valuetype'		=> 'text',
	'default'		=> 'Directory of Organisations'
) ;

$modversion['config'][] = array(
	'name'			=> 'directory_description',
	'title' 		=> '_PROFILE_MI_DESCRIPTION',
	'description'	=> '_PROFILE_MI_DESCRIPTION_DESC',
	'formtype'		=> 'textarea',
	'valuetype'		=> 'text',
	'default'		=> ''
) ;

$modversion['config'][] = array(
	'name'			=> 'groups',
	'title' 		=> '_PROFILE_MI_GROUPS',
	'description'	=> '_PROFILE_MI_GROUPS_DESC',
	'formtype'		=> 'group_multi',
	'valuetype'		=> 'array',
	'default'		=> array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS)
) ;

$fields_handler =& xoops_getmodulehandler('field', 'profile');

$fields = $fields_handler->getObjects(NULL, true);
$fieldnames = array();

foreach ($fields as $id => $field)
	$fieldnames = array_merge($fieldnames, array($field->getVar('field_name') => $field->getVar('field_title')));


$fieldnames = array_merge($fieldnames, array('user_avatar' => 'User Avatar'));

$options = array();
foreach($fieldnames as $id => $fieldname)
	if (!is_numeric($id))
		$options[$fieldname] = $id;
	else
		$options[$fieldname] = $fieldname;

$i=0;
$i++;
$modversion['config'][$i] = array(
	'name'			=> 'display',
	'title' 		=> '_PROFILE_MI_DISPLAY',
	'description'	=> '_PROFILE_MI_DISPLAY_DESC',
	'formtype'		=> 'select_multi',
	'valuetype'		=> 'array',
	'options'		=> $options,
	'default'		=> array()
) ;
$i++;
$modversion['config'][$i] = array(
	'name'			=> 'check_ip',
	'title' 		=> '_PROFILE_MI_CHECKIP',
	'description'	=> '_PROFILE_MI_CHECKIP_DESC',
	'formtype'		=> 'yesno',
	'valuetype'		=> 'int',
	'default'		=> '0',
	'options'		=> array()
) ;
$i++;
$modversion['config'][$i] = array(
	'name'			=> 'check_proxy_ip',
	'title' 		=> '_PROFILE_MI_CHECKPROXYIP',
	'description'	=> '_PROFILE_MI_CHECKPROXYIP_DESC',
	'formtype'		=> 'yesno',
	'valuetype'		=> 'int',
	'default'		=> '0',
	'options'		=> array()
) ;

$i++;
$modversion['config'][$i] = array(
	'name'			=> 'check_network_addy',
	'title' 		=> '_PROFILE_MI_CHECKNETWORKADDY',
	'description'	=> '_PROFILE_MI_CHECKNETWORKADDY_DESC',
	'formtype'		=> 'yesno',
	'valuetype'		=> 'int',
	'default'		=> '0',
	'options'		=> array()
) ;

