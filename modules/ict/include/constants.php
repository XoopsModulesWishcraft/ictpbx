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

define("_ICTPBX_DIRNAME", basename(dirname(__DIR__)));
define('_ICTPBX_MAILTEMPLATE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . "language" . DIRECTORY_SEPARATOR . $GLOBALS["xoopsConfig"]["language"] . DIRECTORY_SEPARATOR . "mail_template");
define('_ICTPBX_CSS_URI', XOOPS_URL  . "/modules/" .  _ICTPBX_DIRNAME . "/language/" . $GLOBALS["xoopsConfig"]["language"] . "/css");