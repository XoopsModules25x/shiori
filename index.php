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

use XoopsModules\Shiori;

require_once dirname(__DIR__, 2) . '/mainfile.php';
//require_once __DIR__ . '/shiori.php';

Shiori\ShioriClass::setup();
Shiori\ShioriClass::execute();
