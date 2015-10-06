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

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * @copyright copyright &copy; 2015 labs.coop
 */
class IctUser extends XoopsObject
{
	/**
	 * Class Constructor
	 */
    function __construct()
    {
        $this->initVar('ictpbx_user_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('first_name', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('last_name', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('mail', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('phone_number', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('mobile_number', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('fax_number', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('address', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('company', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('website', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('active', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('credit', XOBJ_DTYPE_FLOAT, 0, false);
        $this->initVar('free_bundle', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('reserved_credit', XOBJ_DTYPE_FLOAT, 0, false);
        $this->initVar('reserved_free_bundle', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('package', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created_by', XOBJ_DTYPE_INT, 0, false);
    }

}

/**
 * @copyright copyright &copy; 2015 labs.coop
 */
class IctUserHandler extends XoopsPersistableObjectHandler
{
 	
	/**
	 * Class Constructor
	 * 
	 * @param unknown_type $db
	 */
    function __construct(&$db)
    {
        parent::__construct($db, "ictpbx_user", "IctUser", "ictpbx_user_id", 'name');
    }
    
    /**
     * Handles Record Submition from Form Designations
     * 
     * @param IctUser $user
     * @param boolean $force
     * @param array $formvalues
     */
    function insertFromForm(IctUser $user, $force = true, $formvalues = array())
    {
    	if ($user->isNew())
    	{
    		$isnew = true;
    		$mailpasswd = false;
    		if (isset($formvalues['passwd']) && empty($formvalues['passwd']))
    		{
    			$formvalues['vpasswd'] = $formvalues['passwd'] = xoops_makepass();
    			$mailpasswd = true;
    		} elseif (isset($formvalues['passwd']) && !empty($formvalues['passwd']) && isset($formvalues['vpasswd']) && !empty($formvalues['vpasswd']) && $formvalues['vpasswd'] != $formvalues['passwd'])
    		{
    			xoops_loadLanguages('errors');
    			$GLOBALS['errors'][] = _ICTPBX_ERRORS_PASSWORD_MISMATCH;
    			return false;
    		}
    		$user_handler = xoops_gethandler('user');
    		$xoopsuser = $user_handler->create();
    		$xoopsuser->setVar('email', $user->getVar('mail'));
    		$xoopsuser->setVar('uname', $user->getVar('name'));
    		$xoopsuser->setVar('name', $user->getVar('first_name') . " " . $user->getVar('last_name'));
    		$xoopsuser->setVar('pass', md5($formvalues['passwd']));
    		$xoopsuser->setVar('user_avatar', "ictpbx/blank.gif");
    		$xoopsuser->setVar('user_from', $user->getVar('country'));
    		$xoopsuser->setVar('user_regdate', time());
    		$xoopsuser->setVar('user_viewemail', $GLOBALS['ictModuleConfig']['newuser_user_viewmail']);
    		$xoopsuser->setVar('user_mailok', $GLOBALS['ictModuleConfig']['newuser_user_mailok']);
    		$user->setVar('uid', $user_handler->insert($xoopsuser, $force));
    		$member_handler = xoops_gethandler('member');
    		$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $user->getVar('uid'));
    		if ($GLOBALS['ictModuleConfig']['newuser_group']!=XOOPS_GROUP_USERS)
    			$member_handler->addUserToGroup($GLOBALS['ictModuleConfig']['newuser_group'], $user->getVar('uid'));
    		$user->setVar('active', $GLOBALS['ictModuleConfig']['newuser_active']);
    		$user->setVar('credit', $GLOBALS['ictModuleConfig']['newuser_credit']);
    		$user->setVar('free_bundle', $GLOBALS['ictModuleConfig']['newuser_bundle']);
    		$user->setVar('reserved_credit', $GLOBALS['ictModuleConfig']['newuser_reserved_credit']);
    		$user->setVar('reserved_free_bundle', $GLOBALS['ictModuleConfig']['newuser_reserved_bundle']);
    		$user->setVar('package', $GLOBALS['ictModuleConfig']['newuser_package']);
    		$user->setVar('created', time());
    		if (is_a($GLOBALS["xoopsUser"], "XoopsUser"))
    			$user->setVar('created_by', $GLOBALS["xoopsUser"]->getVar('uid'));
    	} else {
    		$isnew = false;
    		if (isset($formvalues['passwd']) && !empty($formvalues['passwd']) && isset($formvalues['vpasswd']) && !empty($formvalues['vpasswd']) && $formvalues['vpasswd'] != $formvalues['passwd'])
    		{
    			xoops_loadLanguages('errors');
    			$GLOBALS['errors'][] = _ICTPBX_ERRORS_PASSWORD_MISMATCH;
    			return false;
    		}
    		if ($user->getVar('uid')!=0)
    		{
	    		$user_handler = xoops_gethandler('user');
	    		$xoopsuser = $user_handler->get($user->getVar('uid'));
	    		$xoopsuser->setVar('email', $user->getVar('mail'));
	    		$xoopsuser->setVar('uname', $user->getVar('name'));
	    		$xoopsuser->setVar('name', $user->getVar('first_name') . " " . $user->getVar('last_name'));
	    		if (!empty($formvalues['passwd']))
	    		{
	    			$xoopsuser->setVar('pass', md5($formvalues['passwd']));
	    			$mailpasswd = true;
	    		}
	    		$user_handler->insert($xoopsuser, $force);
	    		
    		}
    	}
    	$id = parent::insert($user, $force);
    	if ($mailpasswd == true && is_a($xoopsuser, "XoopsUser"))
    	{
    		xoops_loadLanguages('emails');
    		xoops_load("XoopsMailer");
    		$xoopsMailer =& xoops_getMailer();
    		$xoopsMailer->reset();
    		$xoopsMailer->useMail();
    		$xoopsMailer->setHTML(true);
    		$xoopsMailer->setTemplate(_ICTPBX_MAILTEMPLATE_PATH . DIRECTORY_SEPARATOR . ($isnew==true?'new-ictpbx-user-created.html':'existing-user-password-changed.html'));
    		$xoopsMailer->assign('FIRSTNAME', $user->getVar('first_name'));
    		$xoopsMailer->assign('LASTNAME', $user->getVar('last_name'));
    		$xoopsMailer->assign('USERNAME', $user->getVar('name'));
    		$xoopsMailer->assign('USEREMAIL', $user->getVar('mail'));
    		$xoopsMailer->assign('PASSWORD', $user->getVar('passwd'));
    		$xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
    		$xoopsMailer->assign('ADMINMAIL', $GLOBALS['xoopsConfig']['adminmail']);
    		$xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
    		$xoopsMailer->setToUser($xoopsuser);
    		$xoopsMailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
    		$xoopsMailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
    		$xoopsMailer->setSubject(sprintf(($isnew==true?_ICTPBX_EMAIL_SUBJECT_NEWUSER:_ICTPBX_EMAIL_SUBJECT_PASSCHANGE), $GLOBALS['xoopsConfig']['sitename']));
    		if (!$xoopsMailer->send()) {
    			xoops_loadLanguages('errors');
    			$GLOBALS['errors'][] = sprintf(_ICTPBX_ERRORS_EMAIL_NOTSENT, $user->getVar('mail'));
    		}
    	}
    	return $id;
    }
}
?>