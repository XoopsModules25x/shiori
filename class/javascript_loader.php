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
function shiori_get_javascript_link()
{
    $dirname = basename(dirname(__DIR__));

    $link = '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/javascript/load_jquery.js" id="shiori_load_jquery"></script>' . "\n";
    $link .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/javascript/bookmark.js"></script>' . "\n";

    return $link;
}
