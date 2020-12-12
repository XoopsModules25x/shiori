<?php
/**
 * A simple description for this script
 *
 * PHP Version 7.2 or Upper version
 *
 * @package    Shiori
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2009 Hidehito NOZAWA
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 */

use Xmf\Module\Admin;
use XoopsModules\Shiori;

require_once __DIR__ . '/admin_header.php';
// Display Admin header
//xoops_cp_header();
$adminObject = Admin::getInstance();

$adminObject->displayNavigation(basename(__FILE__));
//$adminObject->displayIndex();

Shiori\ShioriClass::setup();
Shiori\ShioriClass::execute(true);



require_once __DIR__ . '/admin_footer.php';
