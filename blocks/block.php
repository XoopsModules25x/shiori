<?php
/**
 * A simple description for this script
 *
 * PHP Version 7.2 or Upper version
 *
 * @param $options
 * @return array|bool
 * @copyright  2009 Hidehito NOZAWA
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package    Shiori
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 */

use XoopsModules\Shiori;

/**
 * @param $options
 * @return array|false
 */
function shiori_block_show($options)
{
    //    require_once  dirname(__DIR__) . '/shiori.php';
    Shiori\ShioriClass::setup();
    $content = Shiori\ShioriClass::block($options[0]);

    if (false === $content) {
        return false;
    }

    return ['content' => $content];
}
