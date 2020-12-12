<?php

namespace XoopsModules\Shiori;

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
 * Class BookmarkHandler
 * @package XoopsModules\Shiori
 */
class BookmarkHandler extends Shiori\Abstracts\BaseObjectHandler
{
    protected $object  = Bookmark::class;
    protected $table   = 'shiori_bookmark';
    protected $primary = 'id';

    /**
     * @param string $order
     * @param string $sort
     * @param null   $limit
     * @param null   $start
     * @return array
     */
    public function loadsStatics($order = 'users', $sort = 'desc', $limit = null, $start = null)
    {
        $orderParams = ['mid', 'url', 'title', 'clicks', 'users'];

        if (!\in_array($order, $orderParams)) {
            $order = 'users';
        }

        if ('desc' !== $sort) {
            $sort = 'asc';
        }

        $sql  = 'SELECT `mid`, `url`, `name`, SUM(`counter`) AS `clicks`, COUNT(*) AS `users` FROM `%s` GROUP BY `mid`, `url`, `name` ORDER BY `%s` %s';
        $sql  = \sprintf($sql, $this->table, $order, $sort);
        $rsrc = $this->db->query($sql, $limit, $start);

        $bookmarks = [];

        while (false !== ($vars = $this->db->fetchArray($rsrc))) {
            $bookmarks[] = [
                'mid'    => $vars['mid'],
                'url'    => $vars['url'],
                'name'   => $vars['name'],
                'clicks' => $vars['clicks'],
                'users'  => $vars['users'],
            ];
        }

        return $bookmarks;
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $sql  = 'SELECT COUNT(DISTINCT `url`) FROM `%s`';
        $sql  = \sprintf($sql, $this->table);
        $rsrc = $this->_query($sql);

        [$total] = $this->db->fetchRow($rsrc);

        return $total;
    }

    /**
     * @param      $uid
     * @param null $limit
     * @param null $start
     * @return array
     */
    public function loadsByUid($uid, $limit = null, $start = null)
    {
        $uid  = (int)$uid;
        $sql  = "SELECT * FROM `%s` WHERE `uid`='%u' ORDER BY `date` DESC";
        $sql  = \sprintf($sql, $this->table, $uid);
        $rsrc = $this->_query($sql, $limit, $start);

        $bookmarks = [];

        while (false !== ($vars = $this->db->fetchArray($rsrc))) {
            $bookmark = $this->create();
            $bookmark->unsetNew();
            $bookmark->setVars($vars);
            $bookmarks[] = $bookmark;
        }

        return $bookmarks;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function countByUid($uid)
    {
        $uid = (int)$uid;
        $sql = \sprintf("SELECT COUNT(`id`) FROM `%s` WHERE `uid` = '%u'", $this->table, $uid);

        $rsrc = $this->db->query($sql);
        [$total] = $this->db->fetchRow($rsrc);

        return $total;
    }

    /**
     * @param $uid
     * @param $url
     * @return bool
     */
    public function urlExists($uid, $url)
    {
        $uid = (int)$uid;
        $url = $this->db->escape($url);
        $sql = \sprintf("SELECT COUNT(`id`) FROM `%s` WHERE `uid` = '%u' AND `url` = '%s'", $this->table, $uid, $url);

        $rsrc = $this->db->query($sql);
        [$total] = $this->db->fetchRow($rsrc);

        return ($total > 0);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function incrementCounter($id)
    {
        $id  = (int)$id;
        $sql = "UPDATE `%s` SET `counter` = `counter` + 1 WHERE `id` = '%u'";
        $sql = \sprintf($sql, $this->table, $id);

        return $this->db->queryF($sql);
    }

    /**
     * @param $uid
     * @param $url
     * @return mixed
     */
    public function deleteByUrl($uid, $url)
    {
        $uid = (int)$uid;
        $url = $this->db->escape($url);
        $sql = \sprintf("DELETE FROM `%s` WHERE `uid` = '%u' AND `url` = '%s'", $this->table, $uid, $url);

        return $this->_query($sql);
    }
}
