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
require_once __DIR__ . '/preloads/autoloader.php';

$mydirname = basename(__DIR__);

// Main
$modversion['version']       = 2.00;
$modversion['module_status'] = 'Alpha 1';
$modversion['release_date']  = '2020/12/12';
$modversion['name']          = _SHIORI_NAME;
$modversion['description']   = _SHIORI_DESC;
$modversion['credits']       = 'Hidehito NOZAWA aka Suin';
$modversion['author']        = 'Suin <http://suin.asia>';
//$modversion['help']          = 'ReadMe-Japanese.html';
$modversion['license']       = 'GNU GPL v2 or later';
$modversion['image']         = 'images/shiori_logo.png';
$modversion['dirname']       = $mydirname;
$modversion['min_php']       = '7.2';
$modversion['min_xoops']     = '2.5.10';
$modversion['min_admin']     = '1.2';
$modversion['min_db']        = ['mysql' => '5.5'];
$modversion['modicons16']    = 'assets/images/icons/16';
$modversion['modicons32']    = 'assets/images/icons/32';

$modversion['system_menu'] = 1;
$modversion['hasMain']     = 1;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

$modversion['hasSearch'] = 0;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'shiori_bookmark';

// ------------------- Help files ------------------- //
$modversion['help']        = 'page=help';
$modversion['helpsection'] = [
    ['name' => _MI_SHIORI_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_SHIORI_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_SHIORI_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_SHIORI_SUPPORT, 'link' => 'page=support'],
];

$modversion['blocks'] = [
    [
        'file'        => 'block.php',
        'show_func'   => 'shiori_block_show',
        'name'        => _SHIORI_BLOCK1,
        'description' => _SHIORI_BLOCK1_DESC,
        'options'     => 'Bookmark',
    ],
    [
        'file'        => 'block.php',
        'show_func'   => 'shiori_block_show',
        'name'        => _SHIORI_BLOCK2,
        'description' => _SHIORI_BLOCK2_DESC,
        'options'     => 'Javascript',
    ],
];

$modversion['config'] = [
    [
        'name'        => 'capacity',
        'title'       => '_SHIORI_CONFIG1',
        'description' => '',
        'formtype'    => 'text',
        'valuetype'   => 'int',
        'default'     => 30,
    ],
    [
        'name'        => 'per_page',
        'title'       => '_SHIORI_CONFIG2',
        'description' => '',
        'formtype'    => 'text',
        'valuetype'   => 'int',
        'default'     => 30,
    ],
    [
        'name'        => 'free_input_url',
        'title'       => '_SHIORI_CONFIG3',
        'description' => '',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 0,
    ],
    [
        'name'        => 'bookmark_other_sites',
        'title'       => '_SHIORI_CONFIG4',
        'description' => '',
        'formtype'    => 'yesno',
        'valuetype'   => 'int',
        'default'     => 0,
    ],
];

$modversion['hasNotification'] = 0;

$modversion['hasComments'] = 0;

$modversion['onInstall'] = 'class/installer.php';
$modversion['onUpdate']  = 'class/installer.php';
