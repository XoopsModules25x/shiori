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
define('_SHIORI_NAME', 'Shiori');
define('_SHIORI_DESC', 'This module allows users to bookmark any pages in XOOPS');

define('_SHIORI_BLOCK1', 'Bookmark');
define('_SHIORI_BLOCK1_DESC', 'A block to add a new bookmark');
define('_SHIORI_BLOCK2', 'JavaScript read');
define('_SHIORI_BLOCK2_DESC', 'Block for reading the JavaScript of the "one-click bookmark');

define('_SHIORI_CONFIG1', 'Max bookmarks for each users');
define('_SHIORI_CONFIG1_DESC', '');
define('_SHIORI_CONFIG2', 'Bookmarks a page');
define('_SHIORI_CONFIG3', 'Allow users to bookmark URLs manually');
define('_SHIORI_CONFIG4', 'Allow users to bookmark outside websites');
define('_SHIORI_CONFIG4_DESC', '');

define('_SHIORI_ADMIN1', 'Statistics');
define('_MI_SHIORI_NAME', _SHIORI_NAME);

//Help
define('_MI_SHIORI_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_SHIORI_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_SHIORI_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_SHIORI_OVERVIEW', 'Overview');

//define('_MI_SHIORI_HELP_DIR', __DIR__);

//help multi-page
define('_MI_SHIORI_DISCLAIMER', 'Disclaimer');
define('_MI_SHIORI_LICENSE', 'License');
define('_MI_SHIORI_SUPPORT', 'Support');

//Menu
define('_MI_SHIORI_MENU_HOME', 'Home');
define('_MI_SHIORI_MENU_01', 'Admin');
define('_MI_SHIORI_MENU_ABOUT', 'About');
