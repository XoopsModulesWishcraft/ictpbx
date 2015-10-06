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

require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'xoopsformloader.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'formselectcountry.php';

/**
 * Build the ictcore editing form.
 *
 * @ingroup forms
 * @see ictcore_form_submit()
 */
function ictcore_form_user_register_form_alter(&$form, &$form_state) {

	xoops_loadLanguage('forms', _ICTFAX_DIRNAME);

	$frm = array();
	$frm['name']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_USERNAME, 'name', 32, $form_state['values']['name']);
	$frm['name']['form']->addDescription(_ICTPBX_MN_FORM_USERNAME_DESC);
	$frm['name']['require'] = true;
	$frm['passwd']['form'] = new XoopsFormPassword(_ICTPBX_MN_FORM_PASSWD, 'passwd', 32, '');
	$frm['passwd']['form']->addDescription(_ICTPBX_MN_FORM_PASSWD_DESC);
	$frm['passwd']['require'] = false;
	$frm['vpasswd']['form'] = new XoopsFormPassword(_ICTPBX_MN_FORM_VPASSWD, 'vpasswd', 32, '');
	$frm['vpasswd']['form']->addDescription(_ICTPBX_MN_FORM_VPASSWD_DESC);
	$frm['vpasswd']['require'] = false;
	$frm['first_name']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_FIRSTNAME, 'first_name', 32, $form_state['values']['first_name']);
	$frm['first_name']['form']->addDescription(_ICTPBX_MN_FORM_FIRSTNAME_DESC);
	$frm['first_name']['require'] = true;
	$frm['last_name']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_LASTNAME, 'last_name', 32, $form_state['values']['last_name']);
	$frm['last_name']['form']->addDescription(_ICTPBX_MN_FORM_LASTNAME_DESC);
	$frm['last_name']['require'] = true;
	$frm['mail']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_EMAIL, 'mail', 32, $form_state['values']['mail']);
	$frm['mail']['form']->addDescription(_ICTPBX_MN_FORM_EMAIL_DESC);
	$frm['mail']['require'] = false;
	$frm['company']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_COMPANY, 'company', 32, $form_state['values']['company']);
	$frm['company']['form']->addDescription(_ICTPBX_MN_FORM_COMPANY_DESC);
	$frm['company']['require'] = false;
	$frm['website']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_WEBSITE, 'website', 32, $form_state['values']['website']);
	$frm['website']['form']->addDescription(_ICTPBX_MN_FORM_WEBSITE_DESC);
	$frm['website']['require'] = false;
	$frm['phone_number']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_PHONE, 'phone_number', 32, $form_state['values']['phone_number']);
	$frm['phone_number']['form']->addDescription(_ICTPBX_MN_FORM_PHONE_DESC);
	$frm['phone_number']['require'] = true;
	$frm['mobile_number']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_MOBILE, 'mobile_number', 32, $form_state['values']['mobile_number']);
	$frm['mobile_number']['form']->addDescription(_ICTPBX_MN_FORM_MOBILE_DESC);
	$frm['mobile_number']['require'] = false;
	$frm['fax_number']['form'] = new XoopsFormText(_ICTPBX_MN_FORM_FAX, 'fax_number', 32, $form_state['values']['fax_number']);
	$frm['fax_number']['form']->addDescription(_ICTPBX_MN_FORM_FAX_DESC);
	$frm['fax_number']['require'] = false;
	$frm['address']['form'] = new XoopsFormTextArea(_ICTPBX_MN_FORM_ADDRESS, 'address', $form_state['values']['address'], 5, 39);
	$frm['address']['form']->addDescription(_ICTPBX_MN_FORM_ADDRESS_DESC);
	$frm['address']['require'] = true;
	$frm['country']['form'] = new IctpbxFormSelectCountry(_ICTPBX_MN_FORM_COUNTRY, 'country', $form_state['values']['country'], 1, true);
	$frm['country']['form']->addDescription(_ICTPBX_MN_FORM_COUNTRY_DESC);
	$frm['country']['require'] = true;
	$frm['ictpbx_user_id']['form'] = new XoopsFormHidden('ictpbx_user_id', !isset($form_state['values']['ictpbx_user_id'])?'0':$form_state['values']['ictpbx_user_id']);
	$frm['ictpbx_user_id']['require'] = false;
	
	$form = new XoopsTableForm(_ICTPBX_MN_FORM_TITLE_USERREGO, _ICTPBX_MN_FORM_IDENTITY_USERREGO, $_SERVER["REQUEST_URI"]);
	foreach($frm as $key => $values)
		$form->addElement($values['form'], $values['require']);

	return $form->render();
}

/**
 * Build the did form.
 */
function ictpbx_did_form($form, &$form_state, $edit = array()) {
	// It's safe to use on both an empty array, and an incoming array with full or partial data.
	$edit += array(
			'account_id' => '',
			'phone'      => '',
			'first_name' => '',
			'email'      => '',
	);

	// If we're editing an existing fax, we'll add a value field to the form
	// containing the fax's unique ID.
	if (!empty($edit['account_id'])) {
		$form['account_id'] = array(
				'#type'          => 'value',
				'#value'         => $edit['account_id'],
		);
	}

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('did Information'),
	);

	$form['info']['phone'] = array(
			'#type'          => 'textfield',
			'#title'         => t('DID Number'),
			'#required'      => TRUE,
			'#default_value' => $edit['phone'],
	);

	$form['info']['first_name'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Title'),
			'#default_value' => $edit['first_name'],
	);

	$form['info']['email'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Email'),
			'#default_value' => $edit['email'],
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}



/**
 * Validate
 *
 */
function ictpbx_did_form_validate($form, &$form_state) {
	$did = $form_state['values'];
	$query  = "SELECT account_id FROM account WHERE phone = :phone";
	$result = CoreDB::db_query($query, array(':phone'=>$did['phone']));
	if ($res = $result->fetchAssoc()) {
		form_set_error('phone', t('DID already exist!'));
	}
}




/**
 * Build the did batch form.
 */
function ictpbx_did_batch($form, &$form_state, $edit = array()) {
	$edit += array(
			'from'       => '',
			'to'         => '',
			'first_name' => '',
			'email'      => '',
	);

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('DID Information'),
	);

	$form['info']['from'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Range From'),
			'#required'      => TRUE,
			'#default_value' => $edit['from'],
	);

	$form['info']['to'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Range To'),
			'#required'      => TRUE,
			'#default_value' => $edit['to'],
	);

	$form['info']['first_name'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Title'),
			'#default_value' => $edit['first_name'],
	);

	$form['info']['email'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Email'),
			'#default_value' => $edit['email'],
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}

/**
 * Validates did batch insert
 */
function ictpbx_did_batch_validate($form, &$form_state) {
	$did = $form_state['values'];

	// validation in case of batch insert
	if ($did['from'] == '' || $did['to'] == '') {
		form_set_error('from', t('Both fileds in DID Range cannot be empty'));
	} else {
		if (!is_numeric($did['from']) || !is_numeric($did['to'])) {
			form_set_error('from', t('Please enter a valid DID range only numbers are allowed!'));
		} else {
			$query  = "SELECT count(account_id) as num FROM account WHERE phone >= :from AND phone<= :to";
			$result = CoreDB::db_query($query, array(':from'=>$did['from'], ':to'=>$did['to']));
			$count = $result->fetchField();
		}
	}
}



/**
 * Build the did import form.
 */
function ictpbx_did_import($form, &$form_state, $edit = array()) {
	$edit += array(
			'upload'     => '',
			'to'         => '',
			'first_name' => '',
			'email'      => '',
	);

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('did Information'),
	);

	// helper field for file upload
	$form['#attributes'] = array('enctype' => "multipart/form-data");
	$form['info']['upload'] = array(
			'#type'          => 'file',
			'#title'         => t('Upload DID List'),
			'#default_value' => $edit['upload'],
	);
	$module_path = base_path() . drupal_get_path('module', 'ictpbx_did');
	$form['info']['example'] = array(
			'#type'          => 'markup',
			'#markup'        => t("Example File: <a href='$module_path/did_sample.csv'>did_sample.csv</a>"),
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}

/**
 * Validates did import insert
 */
function ictpbx_did_import_validate($form, &$form_state) {

}


/**
 * Build the did form.
 */
function ictpbx_did_assign($form, &$form_state, $edit = array()) {
	$edit += array(
			'phone'      => '',
			'first_name' => '',
			'email'      => '',
	);

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('DID Information'),
	);

	// containing the DID's unique ID.
	if (!empty($edit['account_id'])) {
		$form['info']['account_id'] = array(
				'#type'          => 'value',
				'#value'         => $edit['account_id'],
		);
	}

	$form['info']['phone'] = array(
			'#type'          => 'textfield',
			'#title'         => t('DID Number'),
			'#default_value' => $edit['phone'],
	);

	$form['info']['first_name'] = array(
			'#type'          => 'textfield',
			'#title'         => t('First_Name'),
			'#default_value' => $edit['first_name'],
	);

	$form['setting'] = array(
			'#type'  => 'fieldset',
			'#title' => t('User Information'),
	);

	$form['setting']['created_by'] = array(
			'#type'          => 'select',
			'#title'         => t('Assign to User'),
			'#options'       => array(0 => 'None') + ictcore_user_option(),
			'#default_value' => $edit['created_by'],
	);

	$form['setting']['email'] = array(
			'#type'          => 'textfield',
			'#title'         => t('E-mail to Forward DID'),
			'#default_value' => $edit['email'],
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}


/**
 * Crates a input form
 */
function ictfax_form($form, &$form_state, $edit = array()) {
	$edit += array(
			'send_to'    => '',
			'send_from'  => '',
			'text'       => '',
			'file_name'  => '',
			'try_max'    => 1,
	);

	// Include the CTools tools that we need.
	ctools_include('ajax');
	ctools_include('modal');
	// Add CTools' javascript to the page.
	ctools_modal_add_js();
	//  drupal_add_js(drupal_get_path('module', 'ictfax') . '/fax_answer.js');
	// If we're editing an existing fax, we'll add a value field to the form
	// containing the fax's unique ID.
	if (!empty($edit['cid'])) {
		$form['cid'] = array(
				'#type'        => 'value',
				'#value'       => $edit['cid'],
		);
	}

	$form['info'] = array(
			'#type'          => 'fieldset',
			'#title'         => t('Fax Information'),
	);

	$form['info']['send_to'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Send To'),
			'#required'      => TRUE,
			'#size'          => 60,
			'#maxlength'     => 128,
			'#description' => t('Click \'Phonebook\' to select fax #. where to send fax'),
			'#default_value' => $edit['send_to'],
	);

	$form['info']['url'] = array(
			'#type' => 'hidden',
			// The name of the class is the #id of $form['ajax_button'] with "-url"
			// suffix.
			'#attributes' => array('class' => array('contact-button-url')),
			'#value' => url('ictfax/phonebookpopup'),
	);

	$form['info']['ajax_button'] = array(
			'#type' => 'button',
			'#value' => 'Phonebook',
			'#attributes' => array('class' => array('ctools-use-modal')),
			'#id' => 'contact-button',
	);

	$form['info']['text'] = array(
			'#type'          => 'hidden',
			'#title'         => t('Message'),
			'#required'      => FALSE,
			'#cols'          => 60,
			'#rows'          => 10,
			'#default_value' => $edit['text'],
	);

	// helper field for file upload
	$form['#attributes'] = array('enctype' => "multipart/form-data");
	$form['info']['file_name'] = array(
			'#type'          => 'file',
			'#title'         => t('Fax file'),
			'#required'      => FALSE,
			'#description'   => t('Select a file to send as fax, please use only tif, pdf, jpg, png, gif or txt file'),
	);

	$form['info']['try_max'] = array(
			'#type'          => 'select',
			'#title'         => t('No. of Retries'),
			'#options'       => array(
					1 => t('[None]'), // no retry = 1 try
					2 => t('1'),          // 1 retry  = 2 tries and so on ...
					3 => t('2'),
					4 => t('3')),
			'#description'   => t('No. of tries if call failed'),
			'#default_value' => $edit['try_max'],
	);

	$form['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Create new Fax'),
	);

	return $form;
}

/**
 * Performs validation.
 *
 */
function ictfax_form_validate($form, &$form_state) {
	$transmission = $form_state['values'];
	if (isset($transmission['transmission_id'])) {
		// we are editing an existing record
		$existing_record = $transmission['transmission_id'];
	}
	if ($transmission['send_to'] == '') {
		form_set_error('send_to', t('Recipient cannot be empty'));
	}
	if (empty($transmission['send_to'])) {
		form_set_error('send_to', t('Fax Number is required.'));
	} else {
		if (!ctype_digit(str_replace(array('+', ','), '',$transmission['send_to']))) {
			form_set_error('send_to', t('Fax Number is not valid.'));
		}
	}
	// Validate file
	$allowedTypes = array(
			1=>'odt',  2=>'ott',  3=>'sxw',  4=>'stw',  5=>'doc',  6=>'dot',  7=>'sdw',  8=>'vor',  9=>'htm', 10=>'sdd',
			11=>'sdp', 12=>'wpd', 13=>'ods', 14=>'ots', 15=>'sxc', 16=>'stc', 17=>'xls', 18=>'xlw', 19=>'xlt', 20=>'sdc',
			21=>'csv', 22=>'odp', 23=>'otp', 24=>'sxi', 25=>'sti', 26=>'ppt', 27=>'pps', 28=>'pot', 29=>'sxd', 30=>'odt',
			31=>'ott', 32=>'sxw', 33=>'stw', 34=>'doc', 35=>'dot', 36=>'sdw', 37=>'vor', 38=>'htm', 39=>'sdd', 40=>'sdp',
			41=>'wpd', 42=>'ods', 43=>'ots', 44=>'sxc', 45=>'stc', 46=>'xls', 47=>'xlw', 48=>'xlt', 49=>'sdc', 50=>'csv',
			51=>'odp', 52=>'otp', 53=>'sxi', 54=>'sti', 55=>'ppt', 56=>'pps', 57=>'pot', 58=>'sxd', 59=>'txt', 60=>'tif',
			61=>'jpg', 62=>'pdf', 63=>'png', 64=>'gif',
	);
	$validators = array('file_validate_extensions' => $allowedTypes);
	$file = file_save_upload('file_name', $validators);
	if ($file) {
		$form_state['values']['file_name'] = $file; // drupal file object
	} else {
		form_set_error('file_name', "File is required");
	}
}


function ictfax_list_contacts($form, &$form_state, $edit = array()) {
	$edit += array(
			'table'      => '',
			'phone_list' => '',
	);
	global $user;

	$header = array(
			'first_name'   => t('First Name'),
			'last_name'    => t('Last Name'),
			'phone'        => t('phone'),
			'email'        => t('E-Mail'),
	);

	$query = CoreDB::db_select('contact', 'c')->extend('PagerDefault');
	$query->fields('c', array('contact_id', 'first_name','last_name', 'phone', 'email'));
	$query->condition('c.created_by', $user->uid,'=');
	$result = $query->limit(10)
	->extend('TableSort')
	->orderByHeader($header)
	->execute();

	$options = array();
	while ($contact = $result->fetchAssoc()) {
		$options[$contact['contact_id']] = array(
				'first_name' => check_plain($contact['first_name']),
				'last_name'  => check_plain($contact['last_name']),
				'phone'      => check_plain($contact['phone']),
				'email'      => check_plain($contact['email']),
		);
	}

	$form['info']['table'] = array(
			'#type'     => 'tableselect',
			'#header'   => $header,
			'#options'  => $options,
			'#multiple' => FALSE,
			//    '#input'=>true,
			'#empty' => t('No record available'),
			//    '#advanced_select'=>false,
			'#attributes' => '',
			//    '#default_value'=> $edit['table'],
	);

	$form['info']['pager'] = array(
			'#type'   => 'item',
			'#markup' => theme('pager'),
	);

	$form['phone_list'] = array(
			'#type' => 'hidden',
			//    '#value' => $edit['phone_list'],
	);

	$form['info']['submit'] = array(
			'#type'  => 'submit',
			'#value' => t('Select'),
	);

	return $form;
}


/**
 * Build the did form.
 */
function ictpbx_did_forward($form, &$form_state, $edit = array()) {

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('DID Information'),
	);

	// containing the DID's unique ID.
	if (!empty($edit['account_id'])) {
		$form['info']['account_id'] = array(
				'#type'        => 'value',
				'#value'       => $edit['account_id'],
		);
	}

	$form['info']['phone'] = array(
			'#type'          => 'textfield',
			'#title'         => t('DID Number'),
			'#value'         => $edit['phone'],
	);

	$form['setting'] = array(
			'#type'  => 'fieldset',
			'#title' => t('User Information'),
	);

	$form['setting']['email'] = array(
			'#type'          => 'textfield',
			'#title'         => t('E-mail to Forward DID'),
			'#default_value' => $edit['email'],
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}


/**
 * Build the trunk form.
 */
function ictpbx_trunk_form($form, &$form_state, $edit = array()) {
	// It's safe to use on both an empty array, and an incoming array with full or partial data.
	$edit += array(
			'technology_id'   => '',
			'name'            => '',
			'description'     => '',
			'username'        => '',
			'password'        => '',
			'host'            => '',
			'port'            => '',
			//    'channel'         => '1',
			'prefix'          => '00',
			'dialstring'      => 'sofia/gateway/%trunk/%phone',
			'register'        => '',
			'active'          => '',

	);

	// If we're editing an existing fax, we'll add a value field to the form
	// containing the fax's unique ID.
	if (!empty($edit['provider_id'])) {
		$form['provider_id'] = array(
				'#type'          => 'value',
				'#value'         => $edit['provider_id'],
		);
	}

	$form['info'] = array(
			'#type'  => 'fieldset',
			'#title' => t('Trunk Information'),
	);

	$form['info']['technology_id'] = array(
			'#type'          => 'select',
			'#title'         => t('Choose Provider Trunk Type'),
			'#options'       => ictcore_technology_option(),
			'#default_value' => $edit['technology_id'],
	);

	$form['info']['name'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Trunk Name'),
			'#required'      => TRUE,
			'#default_value' => $edit['name'],
			'#description' => t('Trunk name must match with gateway name that you created in freeswitch.'),
	);

	$form['info']['description'] = array(
			'#type'          => 'textarea',
			'#title'         => t('Trunk Description'),
			'#default_value' => $edit['description'],
	);

	$form['info']['active'] = array(
			'#type'          => 'radios',
			'#title'         => t('Choose Status'),
			'#options'       => array(0 => 'Blocked', 1 => 'Active'),
			'#default_value' => $edit['active'],
			'#description' => t('Trunk name must match with gateway name that you created in freeswitch.'),
	);

	$form['setting'] = array(
			'#type'  => 'fieldset',
			'#title' => t('Trunk Settings'),
	);

	$form['setting']['username'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Username'),
			'#default_value' => $edit['username'],
	);

	$form['setting']['password'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Password'),
			'#default_value' => $edit['password'],
	);

	$form['setting']['host'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Host'),
			'#default_value' => $edit['host'],
	);

	$form['setting']['port'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Port'),
			'#default_value' => $edit['port'],
	);

	$form['setting']['prefix'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Add Prefix'),
			'#default_value' => $edit['prefix'],
	);

	$form['setting']['dialstring'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Dial String'),
			'#default_value' => $edit['dialstring'],
	);

	$form['setting']['register'] = array(
			'#type'          => 'textfield',
			'#title'         => t('Register'),
			'#default_value' => $edit['register'],
	);

	$form['buttons']['submit'] = array(
			'#type'          => 'submit',
			'#value'         => t('Submit'),
	);

	return $form;
}

