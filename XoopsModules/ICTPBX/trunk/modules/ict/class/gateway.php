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
class IctGateway extends XoopsObject
{
	/**
	 * Class Constructor
	 */
    function __construct()
    {
        $this->initVar('ictpbx_gateway_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, 0, false, 32);
        $this->initVar('type', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('active', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created_by', XOBJ_DTYPE_INT, 0, false);
    }

}

/**
 * @copyright copyright &copy; 2015 labs.coop
 */
class IctGatewayHandler extends XoopsPersistableObjectHandler
{
 	
	/**
	 * Class Constructor
	 * 
	 * @param unknown_type $db
	 */
    function __construct(&$db)
    {
        parent::__construct($db, "ictpbx_gateway", "IctGateway", "ictpbx_gateway_id", 'name');
    }
}
?>