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
 * Class BaseObjectHandler
 * @package XoopsModules\Shiori\Abstracts
 */
class BaseObjectHandler
{
    protected $object  = '';
    protected $table   = '';
    protected $primary = 'id';
    protected $db = null;
    protected $errors = [];

    public function __construct()
    {
        $this->db    = Shiori\ShioriClass::database();
        $this->table = $this->db->prefix($this->table);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $obj = $this->object;
        $obj = new $obj();
        $obj->setNew();

        return $obj;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function load($id)
    {
        $id   = (int)$id;
        $sql  = "SELECT * FROM `%s` WHERE `%s`='%u'";
        $sql  = \sprintf($sql, $this->table, $this->primary, $id);
        $rsrc = $this->_query($sql, 1);
        $vars = $this->db->fetchArray($rsrc);

        $obj = $this->create();
        $obj->unsetNew();
        $obj->setVars($vars);

        return $obj;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function save($obj)
    {
        if ($obj->isNew()) {
            $this->_insert($obj);
        } else {
            $this->_update($obj);
        }

        return (0 === \count($this->errors));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $id  = (int)$id;
        $sql = "DELETE FROM `%s` WHERE `%s` = '%u'";
        $sql = \sprintf($sql, $this->table, $this->primary, $id);

        return $this->_query($sql);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $obj
     */
    protected function _insert($obj)
    {
        $vars = $obj->getVarsSqlEscaped();
        $data = $this->_buildData($vars);

        $sql = 'INSERT INTO `%s` SET %s ';
        $sql = \sprintf($sql, $this->table, $data);

        if (!$this->_query($sql)) {
            return;
        }

        $obj->unsetNew();
    }

    /**
     * @param $obj
     */
    protected function _update($obj)
    {
        $id   = $obj->getVar($this->primary);
        $vars = $obj->getVarsSqlEscaped();
        $data = $this->_buildData($vars);

        $sql = "UPDATE `%s` SET %s WHERE `%s` = '%u'";
        $sql = \sprintf($sql, $this->table, $data, $this->primary, $id);

        $this->_query($sql);
    }

    /**
     * @param      $sql
     * @param null $limit
     * @param null $start
     * @return mixed
     */
    protected function _query($sql, $limit = null, $start = null)
    {
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            $this->errors[] = $this->db->getError();
        }

        return $result;
    }

    /**
     * @param $vars
     * @return string
     */
    protected function _buildData($vars)
    {
        $ret = [];

        foreach ($vars as $name => $value) {
            $ret[] = \sprintf("`%s` = '%s'", $name, $value);
        }

        $ret = \implode(', ', $ret);

        return $ret;
    }
}
