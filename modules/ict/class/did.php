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
class IctDid extends XoopsObject
{
	/**
	 * Class Constructor
	 */
    function __construct()
    {
        $this->initVar('ictpbx_did_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('did', XOBJ_DTYPE_TXTBOX, '', false, 32);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('assigned_to', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('forward_to', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('trunk_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created_by', XOBJ_DTYPE_INT, 0, false);
    }

}


/**
 * @copyright copyright &copy; 2015 labs.coop
 */
class IctDidUserList extends XoopsObject
{
	/**
	 * Class Constructor
	 */
	function __construct($row = array())
	{
		$this->initVar('ictpbx_did_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('ictpbx_user_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('account_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('did', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('description', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('assigned_to', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('forward_to', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('first_name', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('last_name', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('phone_number', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('mobile_number', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('fax_number', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('mail', XOBJ_DTYPE_TXTBOX, '', false, 32);
		$this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('user_created', XOBJ_DTYPE_INT, 0, false);
		$this->assignVars($row);
	}

}

/**
 * @copyright copyright &copy; 2015 labs.coop
 */
class IctDidHandler extends XoopsPersistableObjectHandler
{
 	
	/**
	 * Class Constructor
	 * 
	 * @param unknown_type $db
	 */
    function __construct(&$db)
    {
        parent::__construct($db, "ictpbx_did", "IctDid", "ictpbx_did_id", 'did');
    }
    
    function getDidByUserList($criteria = NULL)
    {
    	$rows = array();
    	$sql = "SELECT `ictpbx_did_id`, `ictpbx_user_id`, `did`, `description`, `assigned_to`, `forward_to`, `b`.`name` as `name`, `b`.`first_name` as `first_name`, `b`.`last_name` as `last_name`, `b`.`phone_number` as `phone_number`, `b`.`mobile_number` as `mobile_number`, `b`.`fax_number` as `fax_number`,`b`.`mail` as `mail`, `b`.`uid` as `uid`, `created`, `b`.`created` as `user_created` ";
    	$sql .= "FROM " . CoreDB::prefix("ictpbx_did") . " a LEFT JOIN " . CoreDB::prefix("ictpbx_user") . " b ON a.created_by = b.uid ";
    	if (!is_null($criteria))
    		$sql .= $criteria->renderWhere();
    	$result = CoreDB::queryF($sql);
    	while($row = CoreDB::fetchArray($result))
    	{
    		$row['account_id'] = $row['ictpbx_did_id'] * $row['ictpbx_user_id'] + $row['uid'];
    		$rows[] = new IctDidUserList($row);
    	}
    	return $rows;
    }
}
?>