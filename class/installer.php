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

$mydirname = basename(dirname(__DIR__));

eval(
    '
function xoops_module_install_' . $mydirname . '($module)
{
    return shiori_installer($module, \'' . $mydirname . '\', \'install\');
}
function xoops_module_update_' . $mydirname . '($module)
{
    return shiori_installer($module, \'' . $mydirname . '\', \'update\');
}
'
);

/**
 * @param $module
 * @param $mydirname
 * @param $event
 * @return bool
 */
function shiori_installer($module, $mydirname, $event)
{
    if ('update' === $event) {
        global $msgs;
        $ret = &$msgs;
    } else {
        global $ret;
    }

    if (!is_array($ret)) {
        $ret = [];
    }
    $mid = $module->getVar('mid');

    /** @var \XoopsTplfileHandler $tplfileHandler */
    $tplfileHandler = xoops_getHandler('tplfile');
    $tplPath        = dirname(__DIR__) . '/templates';

    $handler = @opendir($tplPath . '/');
    if ($handler) {
        while (false !== ($file = readdir($handler))) {
            if (0 === mb_strpos($file, '.')) {
                continue;
            }

            $filePath = $tplPath . '/' . $file;

            if (is_file($filePath) && '.tpl' === mb_substr($file, -4)) {
                $mtime   = (int)(@filemtime($filePath));
                $tplfile = $tplfileHandler->create();
                $tplfile->setVar('tpl_source', file_get_contents($filePath), true);
                $tplfile->setVar('tpl_refid', $mid);
                $tplfile->setVar('tpl_tplset', 'default');
                $tplfile->setVar('tpl_file', $file);
                $tplfile->setVar('tpl_desc', '', true);
                $tplfile->setVar('tpl_module', $mydirname);
                $tplfile->setVar('tpl_lastmodified', $mtime);
                $tplfile->setVar('tpl_lastimported', 0);
                $tplfile->setVar('tpl_type', 'module');

                if (!$tplfileHandler->insert($tplfile)) {
                    $ret[] = '<span style="color:#ff0000;">ERROR: Could not insert template <b>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</b> to the database.</span><br>';
                } else {
                    $tplid = $tplfile->getVar('tpl_id');
                    $ret[] = 'Template <b>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</b> added to the database. (ID: <b>' . $tplid . '</b>)<br>';
                    // generate compiled file
                    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
                    require_once XOOPS_ROOT_PATH . '/class/template.php';

                    if (!shiori_template_touch($tplid)) {
                        $ret[] = '<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b>.</span><br>';
                    } else {
                        $ret[] = 'Template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b> compiled.</span><br>';
                    }
                }
            }
        }
        closedir($handler);
    }

    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    xoops_template_clear_module_cache($mid);

    // delete shiori language cache.
    if (defined('XOOPS_TRUST_PATH') and file_exists(XOOPS_TRUST_PATH . '/cache')) {
        $cacheDir = XOOPS_TRUST_PATH . '/cache';
    } else {
        $cacheDir = XOOPS_ROOT_PATH . '/cache';
    }

    $dir = opendir($cacheDir);
    while ($file = readdir($dir)) {
        if (is_file($cacheDir . '/' . $file)) {
            if (preg_match('/^shiori_/', $file)) {
                if (@unlink($cacheDir . '/' . $file)) {
                    $ret[] = 'Language cache was deleted: <strong>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</strong>';
                } else {
                    $ret[] = '<span style="color:#ff0000;">ERROR: Language cache could not be deleted: <strong>' . htmlspecialchars($file, ENT_QUOTES | ENT_HTML5) . '</strong></span>';
                }
            }
        }
    }
    closedir($dir);

    return true;
}

/**
 * @param      $tpl_id
 * @param bool $clear_old
 * @return bool
 */
function shiori_template_touch($tpl_id, $clear_old = true)
{
    $tpl = new \XoopsTpl();
    $tpl->register_modifier('shiori_msg', '\XoopsModules\Shiori\ShioriClass::msg');
    $tpl->force_compile = true;
    /** @var \XoopsTplfileHandler $tplfileHandler */
    $tplfileHandler = xoops_getHandler('tplfile');
    $tplfile        = $tplfileHandler->get($tpl_id);
    if (is_object($tplfile)) {
        $file = $tplfile->getVar('tpl_file');
        if ($clear_old) {
            $tpl->clear_cache('db:' . $file);
            $tpl->clear_compiled_tpl('db:' . $file);
        }
        $tpl->fetch('db:' . $file);

        return true;
    }

    return false;
}

// dummy class

/**
 * Class ShioriDummy
 */
class ShioriDummy
{
    public static function msg()
    {
    }
}

?>
