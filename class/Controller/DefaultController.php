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
 * Class DefaultController
 * @package XoopsModules\Shiori\Controller
 */
class DefaultController extends Shiori\Abstracts\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        if ('form' === Shiori\ShioriClass::$Action) {
            $this->_form();
        } elseif ('save' === Shiori\ShioriClass::$Action) {
            $this->_save();
        } elseif ('delete' === Shiori\ShioriClass::$Action) {
            $this->_delete();
        } else {
            $this->_default();
        }

        $this->_view();
    }

    protected function _default()
    {
        $uid   = Shiori\ShioriClass::uid();
        $limit = $this->config['per_page'];
        $start = Shiori\ShioriClass::get('start', 0);

        $bookmarkHandler = new Shiori\BookmarkHandler();
        $bookmarkObjects = $bookmarkHandler->loadsByUid($uid, $limit, $start);

        $nav   = '';
        $total = $bookmarkHandler->countByUid($uid);

        if ($total > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $nav                    = new \XoopsPageNav($total, $limit, $start, 'start');
            $this->data['navi_raw'] = $nav->renderNav();
        }

        foreach ($bookmarkObjects as $bookmarkObject) {
            $bookmark              = new \stdClass();
            $bookmark->id          = $bookmarkObject->getVar('id');
            $bookmark->url         = $bookmarkObject->getVar('url');
            $bookmark->title       = $bookmarkObject->getVar('name');
            $bookmark->mid         = $bookmarkObject->getVar('mid');
            $bookmark->icon        = $bookmarkObject->getVar('icon');
            $bookmark->counter     = $bookmarkObject->getVar('counter');
            $bookmark->module_name = $this->_getModuleName($bookmark->mid);

            $this->data['bookmarks'][] = (array)$bookmark;
        }

        $this->data['ticket'] = Shiori\Ticket::issue();
    }

    protected function _form()
    {
        $url   = Shiori\ShioriClass::post('url');
        $title = Shiori\ShioriClass::post('title');

        $this->_encodeTitleForAjax($title);

        $this->_validateUrl($url);
        $this->_checkDatabase($url);

        $modname = Shiori\ShioriClass::msg('No module');
        $mid     = 0;

        $siteUrl = \str_replace('/', '\/', preg_quote(XOOPS_URL, '/'));

        if (\preg_match('#^' . $siteUrl . '\/modules\/([a-zA-Z0-9_\-]+)\/#i', $url, $matches)) {
            $dirname = $matches[1];
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler = \xoops_getHandler('module');
            $module        = $moduleHandler->getByDirname($dirname);
            $modname       = $module->getVar('name');
            $mid           = $module->getVar('mid');
        } elseif (\preg_match('#^' . $siteUrl . '\/userinfo\.php#', $url)) {
            $modname = Shiori\ShioriClass::msg('User Information');
            $mid     = -1;
        } elseif (\preg_match('#^' . $siteUrl . '\/search\.php#', $url)) {
            $modname = Shiori\ShioriClass::msg('Search');
            $mid     = -2;
        } elseif (\preg_match('#^' . $siteUrl . '\/readpmsg\.php#', $url) || \preg_match('#^' . $siteUrl . '\/viewpmsg\.php#', $url)) {
            $modname = Shiori\ShioriClass::msg('Private Message');
            $mid     = -3;
        } elseif (\preg_match('#^' . $siteUrl . '\/(index\.php)#', $url)) {
            $modname = Shiori\ShioriClass::msg('Home');
            $mid     = -4;
        } elseif (!\preg_match('#^' . $siteUrl . '#', $url)) {
            $modname = Shiori\ShioriClass::msg('Other Site');
            $mid     = -5;
        }

        if (!$title) {
            if ($this->_isThisSite($url)) {
                $title = $modname;
            } else {
                $title = $url;
            }
        }

        $this->data['url']            = $url;
        $this->data['title']          = $title;
        $this->data['icons']          = $this->_getIcons();
        $this->data['module']['id']   = $mid;
        $this->data['module']['name'] = $modname;

        $this->data['ticket'] = Shiori\Ticket::issue();
    }

    protected function _save()
    {
        $this->_validateTicket();

        $url   = Shiori\ShioriClass::post('url');
        $title = Shiori\ShioriClass::post('title');
        $mid   = Shiori\ShioriClass::post('mid');
        $icon  = Shiori\ShioriClass::post('icon');

        $this->_encodeTitleForAjax($title);

        $this->_validateUrl($url);
        $this->_checkDatabase($url);

        $icons = $this->_getIcons();
        $icon  = \in_array($icon, $icons) ? $icon : \reset($icons);

        $bookmarkHandler = new Shiori\BookmarkHandler();

        $bookmarkObject = $bookmarkHandler->create();
        $bookmarkObject->setVar('uid', Shiori\ShioriClass::uid());
        $bookmarkObject->setVar('mid', $mid);
        $bookmarkObject->setVar('icon', $icon);
        $bookmarkObject->setVar('url', $url);
        $bookmarkObject->setVar('date', \time());
        $bookmarkObject->setVar('name', $title);

        if (!$bookmarkHandler->save($bookmarkObject)) {
            Shiori\ShioriClass::error('Database Error');
        } elseif ($this->_isThisSite($url)) {
            Shiori\ShioriClass::redirect('Successly bookmarked.', $url);
        } else {
            Shiori\ShioriClass::redirect('Successly bookmarked.', \SHIORI_URL);
        }
    }

    protected function _delete()
    {
        $this->_validateTicket();

        $deletingBookmarks = Shiori\ShioriClass::post('del_bok');

        if (!$deletingBookmarks or !\is_array($deletingBookmarks)) {
            Shiori\ShioriClass::error('No delete.');
        }

        $bookmarkHandler = new Shiori\BookmarkHandler();
        $deleteMissing   = 0;

        foreach ($deletingBookmarks as $bookmark) {
            if (!$bookmarkHandler->delete($bookmark)) {
                $deleteMissing++;
            }
        }

        if ($deleteMissing > 0) {
            Shiori\ShioriClass::error(Shiori\ShioriClass::msg('{1} bookmarks was not deleted.', $deleteMissing));
        } else {
            Shiori\ShioriClass::redirect('Successly deleted.', \SHIORI_URL);
        }
    }

    protected function _validateTicket()
    {
        $ticket = Shiori\ShioriClass::post('ticket');

        if (!Shiori\Ticket::check($ticket)) {
            Shiori\ShioriClass::error('Ticket error.');
        }
    }

    /**
     * @param $url
     */
    protected function _validateUrl($url)
    {
        if (!$url) {
            Shiori\ShioriClass::error('URL is empty.');
        }

        if (!\preg_match('/^http[s]*:\/\//i', $url)) {
            Shiori\ShioriClass::error('URL is invalid.');
        }

        if (!$this->_isThisSite($url) && 0 == $this->config['bookmark_other_sites']) {
            Shiori\ShioriClass::error('Other site pages cannot be bookmarked.');
        }
    }

    /**
     * @param $url
     * @return false|int
     */
    protected function _isThisSite($url)
    {
//        $siteUrl = \str_replace('/', '\/', preg_quote(XOOPS_URL, '/'));
//
//        return \preg_match('#^' . $siteUrl . '#i', $url);



        $siteUrl = str_replace('/','\/',preg_quote(XOOPS_URL));

        return ( preg_match('/^'.$siteUrl.'/i', $url) );


    }

    /**
     * @param $url
     */
    protected function _checkDatabase($url)
    {
        $db              = Shiori\ShioriClass::database();
        $uid             = Shiori\ShioriClass::uid();
        $bookmarkHandler = new Shiori\BookmarkHandler();
        $total           = $bookmarkHandler->countByUid($uid);

        if ($total >= $this->config['capacity']) {
            Shiori\ShioriClass::error('No space.');
        }

        $urlExists = $bookmarkHandler->urlExists($uid, $url);

        if ($urlExists) {
            Shiori\ShioriClass::error('This URL has already been bookmarked.');
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

    /**
     * @return array|string[]
     */
    protected function _getIcons()
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
        $lists = new \XoopsLists();

        return $lists::getSubjectsList();
    }

    /**
     * @param $title
     */
    protected function _encodeTitleForAjax(&$title)
    {
        if (_CHARSET !== 'UTF-8') {
            $title = mb_convert_encoding($title, _CHARSET, 'UTF-8');
        }
    }
}
