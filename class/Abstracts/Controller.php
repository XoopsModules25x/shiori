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
 * Class Controller
 * @package XoopsModules\Shiori\Abstracts
 */
abstract class Controller
{
    protected $template = null;
    protected $data     = [];
    protected $config = [];

    public function __construct()
    {
        if (!$this->_isUser()) {
            Shiori\ShioriClass::redirect('No permisson', XOOPS_URL);
        }

        $configHandler = \xoops_getHandler('config');
        //      $this->configs = $configHandler->getConfigsByDirname(SHIORI_DIR);
        global $xoopsModuleConfig;
        $this->config         = $xoopsModuleConfig;
        $this->data['config'] = $this->config;
        $this->data['url']    = \SHIORI_URL;
    }

    public function main()
    {
    }

    protected function _view()
    {
        if (!$this->template) {
            $this->template = 'shiori_' . Shiori\ShioriClass::$controller . '_' . Shiori\ShioriClass::$action . '.tpl';
        }

        global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin;

        $GLOBALS['xoopsOption']['template_main'] = $this->template;

        require_once XOOPS_ROOT_PATH . '/header.php';

        $this->_escapeHtml($this->data);
        $xoopsTpl->assign('shiori', $this->data);
        $xoopsTpl->register_modifier('shiori_msg', '\XoopsModules\Shiori\ShioriClass::msg');

        require_once XOOPS_ROOT_PATH . '/footer.php';
    }

    /**
     * @param $vars
     */
    protected function _escapeHtml(&$vars)
    {
        foreach ($vars as $key => &$var) {
            if (\preg_match('/_raw$/', $key)) {
                continue;
            }

            if (\is_array($var)) {
                $this->_escapeHtml($var);
            } elseif (!\is_object($var)) {
                $var = Shiori\ShioriClass::escapeHtml($var);
            }
        }
    }

    /**
     * @return bool
     */
    protected function _isUser()
    {
        global $xoopsUser;

        return \is_object($xoopsUser);
    }
}
