<?php

namespace XoopsModules\Shiori\Abstracts;

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

/**
 * Class AdminController
 * @package XoopsModules\Shiori\Abstracts
 */
abstract class AdminController extends Controller
{
    protected function _view()
    {
        if (!$this->template) {
            $this->template = 'shiori_admin_' . Shiori\ShioriClass::$controller . '_' . Shiori\ShioriClass::$action . '.tpl';
        }

        require_once XOOPS_ROOT_PATH . '/class/template.php';
        require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';

        $xoopsTpl = new \XoopsTpl();

        \xoops_cp_header();

        $this->_escapeHtml($this->data);
        $xoopsTpl->assign('shiori', $this->data);
        $xoopsTpl->register_modifier('shiori_msg', '\XoopsModules\Shiori\ShioriClass::msg');
        $xoopsTpl->display('db:' . $this->template);

        \xoops_cp_footer();
    }
}
