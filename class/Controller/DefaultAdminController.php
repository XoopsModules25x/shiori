<?php

namespace XoopsModules\Shiori\Controller;

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
 * Class AdminDefaultController
 * @package XoopsModules\Shiori\Controller
 */
class DefaultAdminController extends Shiori\Abstracts\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $this->_default();
        $this->_view();
    }

    protected function _default()
    {
        $start = Shiori\ShioriClass::get('start', 0);
        $limit = Shiori\ShioriClass::get('limit', 50);
        $order = Shiori\ShioriClass::get('order', -3);
        $order = (\abs($order) <= 4) ? $order : '-3';

        $this->data['limit'] = $limit;
        $this->data['order'] = $order;

        $sort        = ($order < 0) ? 'desc' : 'asc';
        $orderParams = ['name', 'mid', 'users', 'clicks'];
        $order       = $orderParams[\abs($order) - 1];

        $bookmarkHandler = new Shiori\BookmarkHandler();
        $bookmarks       = $bookmarkHandler->loadsStatics($order, $sort, $limit, $start);
        $total           = $bookmarkHandler->count();

        if (0 != $limit && $total > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $nav                    = new \XoopsPageNav($total, $limit, $start, 'start');
            $this->data['navi_raw'] = $nav->renderNav();
        }
        if (\is_array($bookmarks) && \count($bookmarks) > 0) {
            foreach ($bookmarks as $bookmark) {
                $bookmark['module_name'] = $this->_getModuleName($bookmark['mid']);
            }
            $this->data['bookmarks'] = $bookmarks;
        }
    }

    /**
     * @param $mid
     * @return array|int|mixed|string|string[]|null
     */
    protected function _getModuleName($mid)
    {
        $moduleName = Shiori\ShioriClass::msg('No module');

        if ($mid > 0) {
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = \xoops_getHandler('module');
            $module        = $moduleHandler->get($mid);
            $moduleName    = $module->getVar('name');
        } elseif (-1 == $mid) {
            $moduleName = Shiori\ShioriClass::msg('User Information');
        } elseif (-2 == $mid) {
            $moduleName = Shiori\ShioriClass::msg('Search');
        } elseif (-3 == $mid) {
            $moduleName = Shiori\ShioriClass::msg('Private Message');
        } elseif (-4 == $mid) {
            $moduleName = Shiori\ShioriClass::msg('Home');
        } elseif (-5 == $mid) {
            $moduleName = Shiori\ShioriClass::msg('Other Site');
        }

        return $moduleName;
    }
}
