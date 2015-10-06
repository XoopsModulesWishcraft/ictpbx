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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'coredb.php';

function ictpbx_country_list_api($envalued = false)
{
	static $countries = array();
	if (!isset($countries[$envalued]) || empty($countries[$envalued]))
	{
		xoops_load("XoopsCache");
		if (!$countries[$envalued] = XoopsCache::read($cache = "ictpbx_countries".($envalued==false?"_hashed":"_named")))
		{
			$placesapi = ictpbx_getFileContents(__DIR__ . DIRECTORY_SEPARATOR . "places-api.uris");
			$tries=-1;
			while($tries < 9)
			{
				$tries++;
				shuffle($placesapi);
				$data = json_decode(ictpbx_getURL($placesapi[mt_rand(0, count($placesapi)-1)] . "/v1/list/list/json.api", 60, 60), true);
				if (isset($data['countries']) && !empty($data['countries']))
				{
					$tries = 9;
					if ($envalued==0)
					{
						$countries[$envalued] = array();
						foreach($data['countries'] as $key => $values)
							$countries[$envalued][$values['key']] = $values['Country'];
					} else {
						$countries[$envalued] = array();
						foreach($data['countries'] as $key => $values)
							$countries[$envalued][$values['Country']] = $values['Country'];
					}
				}
			}
		}
		if (!empty($countries[$envalued]))
				XoopsCache::write($cache, $countries[$envalued], 3600 *24 * 7 * 2.5);
	}
	return $countries[$envalued];
}


/**
 * Loads a file as an array skipping commenting
 *
 * @param string $filename
 *
 * @return array
 */
function ictpbx_getFile($filename = '')
{
	$ret = array();
	if (file_exists($filename))
	{
		foreach(file($filename) as $line => $value)
		{
			if (substr(trim($value), 0, 2)!="##" && !strpos(' '.$value, "##") && strlen(trim($value))>0)
			{
				while(substr($value, strlen($value)-1, 1) == "\r")
					$value = substr($value, 0, strlen($value)-1);
				while(substr($value, strlen($value)-1, 1) == "\n")
					$value = substr($value, 0, strlen($value)-1);
				while(substr($value, strlen($value)-1, 1) == "\r")
					$value = substr($value, 0, strlen($value)-1);
				if (intval(trim($value))!=0 || !is_numeric($value))
					$ret[] = trim($value);
			}
		}
	}
	return $ret;
}


/**
 * Loads a file as an array skipping commenting
 *
 * @param string $filename
 *
 * @return array
 */
function ictpbx_getFileContents($filename = '')
{
	return implode("\n", ictpbx_getFile($filename));
}


/** function getURL()
 *
 * 	cURL Routine
 *  @return 		string()
 */
function ictpbx_getURL($uri = '', $timeout = 17, $connectout = 28, $post_data = array(), $getheaders = false)
{
	if (!function_exists("curl_init"))
	{
		return file_get_contents($uri);
	}
	if (!$io = curl_init($uri)) {
		return false;
	}
	curl_setopt($io, CURLOPT_POST, (count($post_data)==0?false:true));
	if (count($post_data)!=0)
		curl_setopt($io, CURLOPT_POSTFIELDS, http_build_query($post_data));
	curl_setopt($io, CURLOPT_CONNECTTIMEOUT, $connectout);
	curl_setopt($io, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($io, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($io, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($io, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($io, CURLOPT_VERBOSE, $getheaders);
	curl_setopt($io, CURLOPT_HEADER, $getheaders);

	/**
	 * Execute Curl Call
	 * @var string
	 */
	$response = curl_exec($io);
	if ($getheaders==true) {
		$infos = curl_getinfo($io);
		$header = substr($response, 0, curl_getinfo($io, CURLINFO_HEADER_SIZE));
		$data = substr($response, curl_getinfo($io, CURLINFO_HEADER_SIZE));
		curl_close($io);
		return array('info'=>$infos, 'header' =>$header, 'data' => $data);
	}
	curl_close($io);
	return $response;
}

function ictcore_access($access) {
	global $user;

	if (is_array($access)) $access = $access[0];
	/*if ($user->phone_verified == 0) {
	 if ($access == 'ictcore blocked') {
	return true;
	} else {
	return false;
	}
	}*/
	if (!user_access($access)) return false;

	return true;
}

/**
 * Loader function for individual ictregistrations using UID.
 *
 */
function ictcore_user_load($users) {
	$user_handler = xoops_gethandler('user');
	$criteria = new Criteria('uid', '(' . implode(', ', $users) . ')', 'IN');
	return $user_handler->getObjects($criteria);
}

/**
 * Inserts a new ictcore user, or updates an existing one.
 *
 */
function ictcore_form_submit($form, $form_state) {
	global $user;
	$user_handler = xoops_getmodulehandler('user', _ICTPBX_DIRNAME);
	if (isset($form_state['values']['ictpbx_user_id']) && !empty($form_state['values']['ictpbx_user_id']))
		$user = $user_handler->get($form_state['values']['ictpbx_user_id']);
	else
		$user = $user_handler->create();
	$user->setVars($form_state['values']);
	return $user_handler->insertFromForm($user, true, $form_state['values']);
}

/**
 * Loader function for all services.
 */
function ictcore_service_option($active = -1, $asObject = false, $id_key = true) {
	static $services = array();
	if (!isset($services[$active][$asObject][$id_key]))
	{
		$service_handler = xoops_getmodulehandler('service', _ICTPBX_DIRNAME);
		if ($active == -1)
			$criteria = new Criteria("created", time(), "<=");
		if ($active == true || $active == false)
			$criteria = new Criteria("active", $active, "=");
		$criteria->setOrder("`name`");
		$criteria->setSort("ASC");
		$services[$active][$asObject][$id_key] = $service_handler->getObjects($criteria, $id_key, $asObject);
	}
	return $services[$active][$asObject][$id_key];
}

/**
 * Loader function for all technologies.
 */
function ictcore_technology_option($active = -1, $asObject = false, $id_key = true) {
	static $technologies = array();
	if (!isset($technologies[$active][$asObject][$id_key]))
	{
		$technology_handler = xoops_getmodulehandler('technology', _ICTPBX_DIRNAME);
		if ($active == -1)
			$criteria = new Criteria("created", time(), "<=");
		if ($active == true || $active == false)
			$criteria = new Criteria("active", $active, "=");
		$criteria->setOrder("`name`");
		$criteria->setSort("ASC");
		$technologies[$active][$asObject][$id_key] = $technology_handler->getObjects($criteria, $id_key, $asObject);
	}
	return $technologies[$active][$asObject][$id_key];
}

/**
 * Loader function for all gateways.
 */
function ictcore_gateway_option($active = -1, $asObject = false, $id_key = true) {
	static $gateways = array();
	if (!isset($gateways[$active][$asObject][$id_key]))
	{
		$gateway_handler = xoops_getmodulehandler('gateway', _ICTPBX_DIRNAME);
		if ($active == -1)
			$criteria = new Criteria("created", time(), "<=");
		if ($active == true || $active == false)
			$criteria = new Criteria("active", $active, "=");
		$criteria->setOrder("`name`");
		$criteria->setSort("ASC");
		$gateways[$active][$asObject][$id_key] = $gateway_handler->getObjects($criteria, $id_key, $asObject);
	}
	return $gateways[$active][$asObject][$id_key];
}

/**
 * Loader function for all trunks.
 */

function ictcore_user_option($active = -1, $asObject = false, $id_key = true) {
	$users = array();
	if (!isset($users[$active][$asObject][$id_key]))
	{
		$users[$active][$asObject][$id_key] = array();
		$user_handler = xoops_getmodulehandler('user', _ICTPBX_DIRNAME);
		if ($active == -1)
			$criteria = new Criteria("created", time(), "<=");
		if ($active == true || $active == false)
			$criteria = new Criteria("active", $active, "=");
		$criteria->setOrder("`name`");
		$criteria->setSort("ASC");
		if ($asObject==false)
			foreach($user_handler->getObjects($criteria, $id_key, true) as $key => $user)
				$users[$active][$asObject][$id_key][$key] = check_plain($user->getVar('name'));
		else
			$users[$active][$asObject][$id_key] = $user_handler->getObjects($criteria, $id_key, $asObject);
	}
	return $users[$active][$asObject][$id_key];
}


/**
 * Page function for did List
 *
 * It will show a list of available dids in form of list
 *
 * @return
 * page html
 */
function ictpbx_did_list($admin_links = false, $criteria = NULL) {

	$did_handler = xoops_getmodulehandler('did', _ICTPBX_DIRNAME);
	$rows = array();
	foreach ($did_handler->getDidByUserList($criteria) as $key => $value) {
		$row_id = $value->getVar('account_id');
		$rows[$row_id]['phone_number'] = check_plain($value->getVar('phone_number'));
		$rows[$row_id]['first_name'] = check_plain($value->getVar('first_name'));
		$rows[$row_id]['last_name'] = check_plain($value->getVar('last_name'));
		$rows[$row_id]['username'] = check_plain($value->getVar('name'));
		$rows[$row_id]['email'] = check_plain($value->getVar('mail'));
		$rows[$row_id]['links'] =  _ictpbx_did_list_links($value, $admin_links);
	}
	return $rows;
}

/**
 * Build the Forward and Release links for a single did.
 *
 * @see did_list()
 */
function _ictpbx_did_list_links(IctDidUserList $did, $admin_links = false) {
	
	xoops_loadLanguage('forms', _ICTPBX_DIRNAME);
	xoops_loadLanguage('images', _ICTPBX_DIRNAME);
	
	$links['edit'] = array(
			'title' => _ICTPBX_FORM_EDIT,
			'image' => _ICTPBX_IMAGE_24x24_EDIT,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/did/' . $value->getVar('account_id') . '/edit',
			'html' => TRUE,
	);
	$links['delete'] = array(
			'title' => _ICTPBX_FORM_REMOVE,
			'image' => _ICTPBX_IMAGE_24x24_REMOVE,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/did/' . $value->getVar('account_id') . '/delete',
			'html' => TRUE,
	);
	$links['assign'] = array(
			'title' => _ICTPBX_FORM_ASSIGN,
			'image' => _ICTPBX_IMAGE_24x24_ASSIGN,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/did/' . $value->getVar('account_id') . '/assign',
			'html' => TRUE,
	);
	return theme_links($links);
}


function theme_links($links = array(), $class = "ictpbx-links", $ids = "ictpbx-link-")
{
	$html = array();
	foreach($links as $key => $value)
	{
		if ($value['HTML']==true)
		{
			if (file_exists(dirname(__DIR__) . $value['image']))
			{
				$html[$key] = "<a title='".$value['title']. "' href='".$value['href'] . "' class='$class-anchor' id='$ids-anchor-$key'><img alt='".$value['title']. "' class='$class-image' id='$ids-image-$key' src='" . XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . $value['image'] . "' /></a>";
			} else {
				$html[$key] = "<a title='".$value['title']. "' href='".$value['href'] . "' class='$class-anchor' id='$ids-anchor-$key'>" . $value['title'] . "</a>";
			}
		}
	}
	return implode("&nbsp;", $html);
}

/**
 * Inserts a new did, or updates an existing one.
 *
 * @param $did
 *   A did to be saved. If $did['did_id'] is set, the did will be updated.
 *   Otherwise, a new did will be inserted into the database.
 * @return
 *   The saved did, with its ID set.
 */
function ictpbx_did_form_submit($form, &$form_state) {
	global $user;

	$data = $form_state['values'];

	// save did for ictcore
	$did = array(
			'phone'          => $data['phone'],
			'first_name'     => $data['first_name'],
			'email'          => $data['email']
	);

	if (!empty($data['account_id'])) {
		$did['account_id'] = $data['account_id'];
	}
	if (isset($data['created_by'])) {
		$did['created_by'] = $data['created_by'];
	}
	if (!isset($data['date_created'])) {
		$did['date_created'] = time();
	}

	// save the did
	if (isset($did['account_id'])) {
		CoreDB::db_update('account')->fields($did)->condition('account_id', $did['account_id'], '=')->execute();
		drupal_set_message(t('DID updated successfully!'), 'status');
	} else {
		CoreDB::db_insert('account')->fields($did)->execute();
		drupal_set_message(t('DID created successfully!'), 'status');
	}
	$form_state['redirect'] = 'ictcore/did/list';
}

/**
 * Loader function for individual dids.
 */
function ictpbx_did_load($did_id) {
	$sql    = "SELECT * FROM account WHERE account_id = :account_id";
	$result = CoreDB::db_query($sql, array(':account_id' => $did_id));
	if ($did = $result->fetchAssoc()) {
		return $did;
	} else {
		drupal_set_message(t('Specified did does not exist! or you have not proper permissions!'), 'error');
		return FALSE;
	}
}

/**
 * Loader function for all dids.
 */
function ictpbx_did_option() {
	$sql    = "SELECT account_id, phone FROM account";
	$result = CoreDB::db_query($sql);
	$rows   = array();
	while ($did = $result->fetchAssoc()) {
		$row_id = $did['account_id'];
		$rows[$row_id] = check_plain($did['phone']);
	}
	if (!isset($rows)) {
		return FALSE;
	}
	return $rows;
}



/**
 * Deletes a did, given its unique ID.
 *
 * @param $did
 *   An array of did containing the ID of a did.
 */
function ictpbx_did_delete($did) {
	$sql = 'DELETE FROM account WHERE account_id = :account_id';
	CoreDB::db_query($sql, array(':account_id'=>$did['account_id']));
	drupal_set_message(t('DID deleted successfully!'), 'status');
	drupal_goto('ictcore/did/list');
}

/**
 * Creates dids in batch and saves them.
 */
function ictpbx_did_batch_submit($form, &$form_state) {
	global $user;

	$did = $form_state['values'];
	$did['created_by'] = 0; // no owner

	for ($num = $did['from']; $num <= $did['to']; $num++) {
		$did['phone'] = $num;
		unset($did['account_id']);
		CoreDB::db_insert('account')->fields($did)->execute();
	}

	$form_state['redirect'] = 'ictcore/did/list';
}

/**
 * Creates dids in import and saves them.
 */
function ictpbx_did_import_submit($form, &$form_state) {
	global $user;
	$did = $form_state['values'];
	// set source file path
	//$file = file_save_upload('file_upload');
	$validators = array(
			'file_validate_extensions' => array('csv xls'),
	);
	if($file = file_save_upload('file_upload', $validators)) {
		$orgFile = '/tmp/'.$file->filename;
		if (file_validate_extensions($file, 'xml')) {
			$file_name = tempnam('', 'did-list-');
			$command = sys_which('xlhtml') . " -csv -xp:0 ". $orgFile ." > $file_name";
			exec($command);
		} else {
			$file_name = $orgFile;
		}
		$handle  = fopen($file_name, "r");
		while (($data = fgetcsv($handle, 500, ",")) !== false) {
			$did       = array();
			if (count($data) == 1) {
				$did['phone']       = $data[0];
			} else if (count($data) >= 2) {
				$did['phone']       = $data[0];
				$did['first_name']  = $data[1];
			}
			if (count($did) > 0) {
				$did['created_by'] = -1; // new did not assigned to anybody

				// now save did
				$result = CoreDB::db_update('account', $did);
				$total_rows++;
			} else {
				// incomplete did record
				$skipped_rows++;
			}
		}
		fclose($handle);
		return $total_rows;
	}
}

/**
 * Inserts a new did, or updates an existing one.
 *
 * @param $did
 *   A did to be saved. If $did['did_id'] is set, the did will be updated.
 *   Otherwise, a new did will be inserted into the database.
 * @return
 *   The saved did, with its ID set.
 */
function ictpbx_did_assign_submit($form, &$form_state) {
	global $user;

	$data = $form_state['values'];
	// save did for ictcore
	$did = array(
			'phone'          => $data['phone'],
			'first_name'     => $data['first_name'],
			'email'          => $data['email'],
			'created_by'     => $data['created_by']
	);
	if (!empty($data['account_id'])) {
		$did['account_id'] = $data['account_id'];
	}
	if (isset($did['created_by']) && !empty($did['created_by'])) {
		$did['created_by'] = $data['created_by'];
	} else {
		$did['created_by'] = 0;
		$did['email']      = '';
	}

	// save the did assignment
	if (isset($did['account_id'])) {
		CoreDB::db_update('account')->fields($did)->condition('account_id', $did['account_id'], '=')->execute();
	}

	drupal_set_message(t('DID assigned successfully!'), 'status');
	$form_state['redirect'] = 'ictcore/did/list';
}

/**********************************
 * Now ICTFax User to Manage DIDs  *
***********************************/
/**
 * Page function for did List
 *
 * It will show a list of available dids in form of list
 *
 * @return
 * page html
 */
function ictpbx_user_did_list() {
	global $user;
	$header = array(
			array('data' => t('DID Number'),    'field' => 'phone',  'sort' => 'asc'),
			array('data' => t('Title'),         'field' => 'first_name'),
			array('data' => t('Forwarded To'),  'field' => 'email'),
			array('data' => t('Operations')),
	);

	$query = CoreDB::db_select('account', 'a')->extend('PagerDefault');
	$query->fields('a', array('account_id', 'phone', 'first_name', 'email'));
	$query->leftjoin('usr','u','u.usr_id = a.email');
	$query->condition('u.usr_id', $user->uid , '=');

	$result = $query->limit(50)
	->extend('TableSort')
	->orderByHeader($header)
	->execute();

	while ($did = $result->fetchAssoc()) {
		$row_id = $did['account_id'];
		$rows[$row_id][] = check_plain($did['phone']);
		$rows[$row_id][] = check_plain($did['first_name']);
		$rows[$row_id][] = check_plain($did['email']);
		$rows[$row_id][] = _ictpbx_user_did_list_links($did);
	}
	if (!isset($rows)) {
		$rows[] = array(array('data' => t('No DID Number available'), 'colspan' => 6));
	}

	$output = theme('table', array('header'=>$header, 'rows'=>$rows));
	$output .= theme('pager');

	return $output;
}


/**
 * Implementation of hook_node_name
 */
function ictfax_node_name() {
	$name = t('Fax Account');
	if ( empty($name) ) {
		$name = 'Fax Account';
	}
	return $name;
}

/**
 * Valid permissions for this module
 * @return array An array of valid permissions for the ictfax module
 * At this point, we'll give permission to anyone who can access site content
 * or administrate the module:
 */
function ictfax_permission() {
	return array(
			'can use ictfax'  => array('title'=> 'can use ictfax'),
	);
}

function ictfax_access($access) {
	global $user;
	if (is_array($access)) $access = $access[0];
	if (!user_access($access)) return false;
	return true;
}

/**
 * This function creates a list of faxs for the specified interface
 */
function ictfax_outbox_list() {
	global $user;
	$service_flag = 2; // SERVICE_FAX
	$header = array(
			array('data' => t('Job ID'),    'field' => 'transmission_id', 'sort' => 'desc'),
			array('data' => t('File Name'), 'field' => 'message_id'),
			array('data' => t('Fax From'),  'field' => 'account_id'),
			array('data' => t('Fax To'),    'field' => 'contact_id'),
			array('data' => t('Status'),    'field' => 'status'),
			array('data' => t('Action'),    'field' => ''),
	);

	$query = CoreDB::db_select('transmission', 't')->extend('PagerDefault');
	$query->fields('t', array('transmission_id', 'message_id', 'account_id', 'contact_id', 'status'));
	$query->condition('t.direction', 'outbound', '=');
	$query->condition('t.service_flag', $service_flag, '=');
	$result = $query->limit(50)
	->extend('TableSort')
	->orderByHeader($header)
	->execute();
	while ($transmission = $result->fetchAssoc()) {
		$row_id = $transmission['transmission_id'];
		$rows[$row_id][] = check_plain($transmission['transmission_id']);
		$rows[$row_id][] = check_plain(ictfax_get_message_filename($transmission['message_id']));
		$rows[$row_id][] = check_plain(ictfax_get_account_name($transmission['account_id']));  // to
		$rows[$row_id][] = check_plain(ictfax_get_contact_name($transmission['contact_id']));  // from
		$rows[$row_id][] = check_plain($transmission['status']);
		$rows[$row_id][] = _ictfax_list_links($transmission);
	}
	if (!isset($rows)) {
		$rows[] = array(array('data' => t('No faxes have been created to send.'), 'colspan' => 8));
	}
	$output = theme('table', array('header'=>$header, 'rows'=>$rows));
	$output .= theme('pager');

	if (!isset($_SESSION['fax_status']) || $_SESSION['fax_status'] == 'in_queue') {
		$output .= ictfax_getJaveScript();
	}

	return $output;
}

function _ictfax_list_links($transmission, $admin_links = false) {
	
	xoops_loadLanguage('forms', _ICTPBX_DIRNAME);
	xoops_loadLanguage('images', _ICTPBX_DIRNAME);
	
	$links['edit'] = array(
	 		'title' => _ICTPBX_FORM_EDIT,
			'image' => _ICTPBX_IMAGE_24x24_EDIT,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/fax/outbox/' . $transmission['cid'] . '/edit',
			'html' => TRUE,
	);
	$links['delete'] = array(
	 		'title' => _ICTPBX_FORM_DELETE,
			'image' => _ICTPBX_IMAGE_24x24_DELETE,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/fax/outbox/' . $transmission['transmission_id'] . '/delete',
			'html' => TRUE,
	);
	return theme_links($links);
}

/**
 * This function creates a list of incomming faxs
 */
function ictfax_inbox_list() {
	global $user;

	$service_flag = 2; // SERVICE_FAX
	$header = array(
			array('data' => t('Job ID'),    'field' => 'transmission_id', 'sort' => 'desc'),
			array('data' => t('File Name'), 'field' => 'message_id'),
			array('data' => t('Fax From'),  'field' => 'contact_id'),
			array('data' => t('Fax To'),    'field' => 'account_id'),
			array('data' => t('Status'),    'field' => 'status'),
			array('data' => t('Action'),    'field' => ''),
	);

	$query = CoreDB::db_select('transmission', 't')->extend('PagerDefault');
	$query->fields('t', array('transmission_id', 'message_id', 'contact_id', 'account_id', 'status'));
	$query->condition('t.direction', 'inbound','=');
	$query->condition('t.service_flag', $service_flag,'=');
	$result = $query->limit(50)
	->extend('TableSort')
	->orderByHeader($header)
	->execute();
	while ($transmission = $result->fetchAssoc()) {
		$row_id = $transmission['transmission_id'];
		$rows[$row_id][] = check_plain($transmission['transmission_id']);
		$rows[$row_id][] = check_plain(ictfax_get_message_filename($transmission['message_id']));
		$rows[$row_id][] = check_plain(ictfax_get_contact_name($transmission['contact_id']));
		$rows[$row_id][] = check_plain(ictfax_get_account_name($transmission['account_id']));
		$rows[$row_id][] = check_plain($transmission['status']);
		$rows[$row_id][] = _ictfax_list_links($transmission);
	}
	if (!isset($rows)) {
		$rows[] = array(array('data' => t('No faxes have been received.'), 'colspan' => 8));
	}
	$output = theme('table', array('header'=>$header, 'rows'=>$rows));
	$output .= theme('pager');

	if (!isset($_SESSION['fax_status']) || $_SESSION['fax_status'] == 'in_queue') {
		$output .= ictfax_getJaveScript();
	}

	return $output;
}

function ictfax_get_message_filename($message_id){
	$query    = 'SELECT file_name FROM document WHERE document_id = :document_id';
	$query_rs = CoreDB::db_query($query, array(':document_id' => $message_id));
	$document = $query_rs->fetchAssoc();
	return $document['file_name'];
}

function ictfax_get_account_name($account_id){
	$query    = 'SELECT CONCAT(first_name, last_name) AS name FROM account WHERE account_id = :account_id';
	$query_rs = CoreDB::db_query($query, array(':account_id' => $account_id));
	$account  = $query_rs->fetchAssoc();
	return $account['name'];
}

function ictfax_get_contact_name($contact_id){
	$query    = 'SELECT CONCAT(first_name, last_name) AS name FROM contact WHERE contact_id = :contact_id';
	$query_rs = CoreDB::db_query($query, array(':contact_id' => $contact_id));
	$contact  = $query_rs->fetchAssoc();
	return $contact['name'];
}

function _ictfax_list_incoming_links($transmission, $admin_links = false) {
	
	xoops_loadLanguage('forms', _ICTPBX_DIRNAME);
	xoops_loadLanguage('images', _ICTPBX_DIRNAME);
	
	$links['delete'] = array(
			'title' => _ICTPBX_FORM_DELETE,
			'image' => _ICTPBX_IMAGE_24x24_DELETE,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/fax/inbox/' . $transmission['transmission_id'] . '/delete',
			'html' => TRUE,
	);
	if (!empty($transmission['message_id'])) {
		$links['view'] = array(
				'title' => _ICTPBX_FORM_VIEW,
				'image' => _ICTPBX_IMAGE_24x24_VIEW,
				'href'  => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/fax/'.$transmission['message_id'].'/view',
				'html'  => TRUE,
		);
	}
	return theme_links($links);
}

/**
 * Display / Download fax document
 */

function ictfax_message_load($message_id) {
	global $user;
	if($cid != null) {
		$query  = 'SELECT * FROM document WHERE document_id = :document_id AND created_by=:created_by';
		$result = CoreDB::db_query($query, array(':document_id' => $message_id, ':created_by' => $user->uid));
		if ($document = $result->fetchAssoc()) {
			return $document;
		}
		else {
			drupal_set_message(t('Specified Fax does not exist! or you have not proper permissions!'), 'error');
			return FALSE;
		}
	}
}

function ictfax_view($message) {

}

function ictfax_download($message) {

}

/**
 * Loads a fax
 */
function ictfax_load($transmission_id) {
	global $user;
	if ($transmission_id != null) {
		$query  = 'SELECT * FROM transmission WHERE transmission_id = :transmission_id AND created_by=:created_by';
		$result = CoreDB::db_query($query, array(':transmission_id' => $transmission_id, ':created_by' => $user->uid));
		if ($transmission = $result->fetchAssoc()) {
			return $transmission;
		} else {
			drupal_set_message(t('Specified Fax does not exist! or you have not proper permissions!'), 'error');
			return FALSE;
		}
	}
}

/**
 * Persist the changes to the database
 */
function ictfax_form_submit($form, &$form_state) {
	global $user;

	include_once "/usr/ictcore/core/core.php";

	// first of all load user in ict framework
	do_login($user->uid);
	$data = $form_state['values'];

	// prepare new transmission
	$oTransmission = new Transmission();
	$oTransmission->set('service_flag',  2); // SERVICE_FAX
	$oTransmission->set('origin', 'web');
	$oTransmission->set('direction', 'outbound');
	$oTransmission->set('account_id', ACCOUNT_DEFAULT); // send from default account / source
	$oTransmission->set('try_allowed', $data['try_max']);

	$oTransmission->set('contact_id', NULL);
	$oTransmission->oContact->set('phone', $data['send_to']);
	$oTransmission->oContact->save();

	$oTransmission->set('message_id', NULL);
	if (!empty($data['file_name']->uri)) {
		$oTransmission->oMessage->set('file_name', drupal_realpath($data['file_name']->uri));
	} else if (!empty($text)) {
		$oTransmission->oMessage->set('text', $data['text']);
	}
	$oTransmission->oMessage->save();

	$result = $oTransmission->send();

	if (!$result) {
		drupal_set_message(t('Unable to send fax, error occured!'), 'error');
		return false;
	}
	drupal_set_message(t('FAX uploaded successfully!'), 'info');
	$form_state['redirect'] = 'ictfax/outbox/list';
	return true;
}

/**
 * Deletes a recording, given its unique ID.
 *
 * @param $recording
 *   An array of recording containing the ID of a recording
 */
function ictfax_delete($transmission) {
	include_once "/usr/ictcore/core/core.php";

	$oTransmission = new Transmission($transmission['transmission_id']);
	if ($oTransmission->delete()) {
		drupal_set_message(t('Fax deleted successfully!'), 'status');
	} else {
		drupal_set_message(t('Cannot delete specified fax! fax does not exist or you have not proper permissions!'), 'error');
	}
	drupal_goto('ictfax/outbox');
}

function ictfax_getPhoneNo($item, $key)
{
	$item = str_replace(array('-','_',' '),'',$item);
}

function ictfax_onload() {
	if (isset($_SESSION['fax_status']) && $_SESSION['fax_status'] == 'in_queue') {
		return array('setTimer()');
	}
}


function ictfax_getJaveScript() {
	$myjava = '
	<script language="JavaScript">
	var msgIX = 0
	 
	function setTimer() {
	window.setInterval("displayMessage()", 3000)
}

function displayMessage() {
try {
p = new XMLHttpRequest();
} catch (e) {
p = new ActiveXObject("Msxml2.XMLHTTP");
}
var interactiveCount = 0;
// p.onload would also work in Mozilla
p.onreadystatechange = FaxStatus;
try {
// Needed for Mozilla if local file tries to access an http URL
netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
} catch (e) {
// ignore
}
postq = "q=ictfax/getfaxstatus&tt=" + Math.random();
p.open("GET", "?" + postq);
p.send(postq);
}

function FaxStatus()
{
if (p.readyState != 4)
return;
var myresp = p.responseText;
var myresp_line=myresp.split("\n");
var myresp_array=myresp_line[0].split("|");
var loop_ct = 1;

document.getElementById("faxstatus").innerHTML = p.responseText;
}

</script>
';
	return $myjava;
}

function ictfax_getfaxstatus() {
	global $user;
	$aCid = $_SESSION['fax_cid'];
	$output = '';
	foreach ($aCid as $key => $transmission_id) {
		$query  = "SELECT contact_id, status, response FROM transmission
		WHERE transmission_id = :transmission_id AND created_by = :created_by";
		$tranRs = CoreDB::db_query($query, array(':transmission_id' => $transmission_id, ':created_by' => $user->uid));
		$transmission = $tranRs->fetchObject();
		if ($transmission->status == 'failed') {
			$color = "ff0000"; // red
		} elseif ($transmission->status == 'completed') {
			$color = "00ff00"; // green
		} else { // in queue, initiated etc ...
			$color = "0000ff"; // blue
		}
		$output  = '<font color="#000">Sending FAX to '.$transmission->contact_id.' stutus is:&nbsp;</font>';
		$output .= '<font color="#'.$color.'">'.$transmission->status.'</font>';
	}
	return $output;
}

function ictfax_popup() {

	ctools_include('ajax');
	ctools_include('modal');

	//ctools_include('form');
	// Have to do this or form submit won't work right.
	//drupal_add_js('misc/jquery.form.js');
	//ctools_add_js('ajax-responder');

	$form_state = array(
			'ajax' => TRUE,
			'title' => t('Phonebook'),
	);
	$output = ctools_modal_form_wrapper('ictfax_list_contacts', $form_state, array());
	//$output = ctools_build_form('ictfax_list_contacts', $form_state, array());

	if (isset($form_state['complete'])) {
		//$output[] = ctools_ajax_command_replace('#modal-complete', '<div id="modal-complete">' . $form_state['values']['#edit-send-to']['value'] . '</div>');
		$animal = '<div class="form-item form-type-textfield form-item-send-to">
		<label for="edit-send-to">Send To <span class="form-required" title="This field is required.">*</span></label>
		<input type="text" id="edit-send-to" name="send_to" value="'.$form_state['values']['phone_list'].'" size="60" maxlength="128" class="form-text required">
		<div class="description">fax #. where to send fax</div>
		</div>';
		//$animal = 'hello world';
		$output[] = ajax_command_html('.form-item-send-to', $animal);
		$output[] = ctools_modal_command_dismiss(t('Login Success'));
	}
	print ajax_render($output);

}


function ictfax_list_contacts_submit($form, &$form_state) {
	$contacts = $form_state['values'];
	$phone = array();
	$id = $contacts['table'];
	if($id > 0) {
		$sql    = "SELECT phone FROM contact WHERE contact_id =:contact_id";
		$result = CoreDB::db_query($sql, array(':contact_id' => $id))->fetchAssoc();
		//$phone[] = $result['phone'];
		$phone = $result['phone'];
	}
	//$phone = implode($phone, ',');
	$form_state['values']['phone_list'] = $phone;
	$form_state['complete'] = TRUE;
}

/**
 * Build the Forward and Release links for a single did.
 *
 * @see did_list()
 */
function _ictpbx_user_did_list_links($did) {
	
	xoops_loadLanguage('forms', _ICTPBX_DIRNAME);
	xoops_loadLanguage('images', _ICTPBX_DIRNAME);
	
	$links['forward'] = array(
			'title' => _ICTPBX_FORM_FORWARD,
			'image' => _ICTPBX_IMAGE_24x24_FORWARD,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/setting/did/' . $did['account_id'] . '/forward',
			'html' => TRUE,
	);
	return theme_links($links);
}


/**
 * Inserts a new did, or updates an existing one.
 *
 * @param $did
 *   A did to be saved. If $did['did_id'] is set, the did will be updated.
 *   Otherwise, a new did will be inserted into the database.
 * @return
 *   The saved did, with its ID set.
 */
function ictpbx_did_forward_submit($form, &$form_state) {
	$did = $form_state['values'];

	$data = $form_state['values'];
	// save did for ictcore
	$did = array(
			'email'          => $data['email']
	);
	if (!empty($data['account_id'])) {
		$did['account_id'] = $data['account_id'];
	}

	// save the did assignment
	if (isset($did['account_id'])) {
		CoreDB::db_update('account')->fields($did)->condition('account_id', $did['account_id'], '=')->execute();
	}
	drupal_set_message(t('DID assigned successfully!'), 'status');
	//  save_conf_did();
	$form_state['redirect'] = 'setting/did/list';
}


/**
 * Page function for trunk List
 *
 * It will show a list of available trunks in form of list
 *
 * @return
 * page html
 */
function ictpbx_trunk_list($admin_links = false) {

	$header = array(
			array('data' => t('Name'),          'field' => 'name',  'sort' => 'asc'),
			array('data' => t('Tech/Type'),     'field' => 'technology_name'),
			array('data' => t('Status'),        'field' => 'active'),
			array('data' => t('Operations')),
	);

	$query = CoreDB::db_select('provider', 'p')->extend('PagerDefault');
	$query->fields('p', array('provider_id', 'name', 'type', 'active', 'date_created'));
	$query->leftjoin('technology','t','t.technology_id = p.technology_id');
	$query->addfield('t', 'technology_name');
	$query->orderBy('date_created', 'DESC');
	//$query->condition('t.uid', $user->uid,'=');

	$result = $query->limit(50)
	->extend('TableSort')
	->orderByHeader($header)
	->execute();

	while ($trunk = $result->fetchAssoc()) {
		$row_id = $trunk['provider_id'];
		$rows[$row_id][] = check_plain($trunk['name']);
		$rows[$row_id][] = check_plain($trunk['technology_name']);
		$rows[$row_id][] = check_plain($trunk['active'] ? 'Active' : 'Blocked');
		$rows[$row_id][] = _ictpbx_trunk_list_links($trunk);
	}
	if (!isset($rows)) {
		$rows[] = array(array('data' => t('No trunks available'), 'colspan' => 6));
	}

	$output = theme('table', array('header'=>$header, 'rows'=>$rows));
	$output .= theme('pager');

	return $output;
}

/**
 * Build the Forward and Release links for a single trunk.
 *
 * @see trunk_list()
 */
function _ictpbx_trunk_list_links($trunk, $admin_links = false) {
	
	xoops_loadLanguage('forms', _ICTPBX_DIRNAME);
	xoops_loadLanguage('images', _ICTPBX_DIRNAME);
	
	$links['edit'] = array(
			'title' => _ICTPBX_FORM_EDIT,
			'image' => _ICTPBX_IMAGE_24x24_EDIT,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/trunk/' . $trunk['provider_id'] . '/edit',
			'html' => TRUE,
	);
	$links['delete'] = array(
			'title' => _ICTPBX_FORM_DELETE,
			'image' => _ICTPBX_IMAGE_24x24_DELETE,
			'href' => XOOPS_URL . '/modules/' . _ICTPBX_DIRNAME . ($admin_links==true?"/admin":"") . '/trunk/' . $trunk['provider_id'] . '/delete',
			'html' => TRUE,
	);
	return theme_links($links);
}

/**
 * Inserts a new trunk, or updates an existing one.
 *
 * @param $trunk
 *   A trunk to be saved. If $trunk['trunk_id'] is set, the trunk will be updated.
 *   Otherwise, a new trunk will be inserted into the database.
 * @return
 *   The saved trunk, with its ID set.
 */
function ictpbx_trunk_form_submit($form, &$form_state) {
	global $user;

	include_once "/usr/ictcore/core/core.php";

	do_login($user->uid);

	$trunk = $form_state['values'];

	if (!empty($trunk['provider_id'])) {
		$provider = new Provider($trunk['provider_id']);
	} else {
		$provider = new Provider();
	}
	$provider->set('name',          $trunk['name']);
	$provider->set('username',      $trunk['username']);
	$provider->set('password',      $trunk['password']);
	$provider->set('description',   $trunk['description']);
	$provider->set('host',          $trunk['host']);
	$provider->set('port',          $trunk['port']);
	$provider->set('technology_id', $trunk['technology_id']);
	$provider->set('dialstring',    $trunk['dialstring']);
	$provider->set('prefix',        $trunk['prefix']);
	$provider->set('register',      $trunk['register']);
	$provider->set('active',        $trunk['active']);
	$provider->set('service_flag',  SERVICE_FAX);

	$provider->save();
	drupal_set_message(t('Provider Trunk saved successfully!'), 'status');

	$form_state['redirect'] = 'ictcore/trunk/list';
}

/**
 * Loader function for individual trunks.
 */
function ictpbx_trunk_load($trunk_id) {
	$sql    = "SELECT * FROM provider WHERE provider_id = :provider_id";
	//$sql    = ictbilling_db_filter($sql);
	$result = CoreDB::db_query($sql, array(':provider_id' => $trunk_id));
	if ($trunk = $result->fetchAssoc()) {
		return $trunk;
	} else {
		drupal_set_message(t('Specified trunk does not exist! or you have not proper permissions!'), 'error');
		return FALSE;
	}
}

/**
 * Deletes a trunk, given its unique ID.
 *
 * @param $trunk
 *   An array of trunk containing the ID of a trunk.
 */
function ictpbx_trunk_delete($trunk) {
	$sql = 'DELETE FROM provider WHERE provider_id = :provider_id';
	CoreDB::db_query($sql, array(':provider_id'=>$trunk['provider_id']));
	drupal_set_message(t('Trunk deleted successfully!'), 'status');
	drupal_goto('ictcore/trunk/list');
}

/**
 * Loader function for all trunks.
 */
function ictpbx_trunk_option() {
	$sql = "SELECT provider_id, name FROM provider";
	$result = CoreDB::db_query($sql);
	$rows = array();
	while ($trunk = $result->fetchAssoc()) {
		$row_id = $trunk['provider_id'];
		$rows[$row_id] = check_plain($trunk['name']);
	}
	if (!isset($rows)) {
		return FALSE;
	}
	return $rows;
}

/**
 * Loader function for active trunks.
 */
function get_trunk_active() {
	$sql = "SELECT * FROM provider WHERE active=1 ORDER BY created DESC LIMIT 1";
	$result = CoreDB::db_query($sql);
	if ($trunk = $result->fetchAssoc()) {
		return $trunk;
	} else {
		return FALSE;
	}
}
