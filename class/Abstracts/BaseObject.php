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
 * Class BaseObject
 * @package XoopsModules\Shiori\Abstracts
 */
class BaseObject
{
    public const BOOL = 1;
    public const INTEGER = 2;
    public const FLOAT = 3;
    public const STRING = 4;
    public const TEXT = 5;
    public const DATETIME = 6;
    protected $vars = [];
    protected $new  = null;

    /**
     * @param      $name
     * @param      $type
     * @param null $default
     * @param null $size
     */
    public function val($name, $type, $default = null, $size = null)
    {
        $this->vars[$name]['value']   = $default;
        $this->vars[$name]['type']    = $type;
        $this->vars[$name]['default'] = $default;

        if (self::INTEGER == $type) {
            $this->vars[$name]['size'] = $size ?: 8;
        } elseif (self::STRING == $type) {
            $this->vars[$name]['size'] = $size ?: 255;
        }
    }

    public function setNew()
    {
        $this->new = true;
    }

    public function unsetNew()
    {
        $this->new = false;
    }

    /**
     * @return null
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setVar($name, $value)
    {
        $type = $this->vars[$name]['type'];

        if (self::BOOL == $type) {
            $value = $value ? true : false;
        } elseif (self::INTEGER == $type) {
            $value = (int)$value;
        } elseif (self::FLOAT == $type) {
            $value = (float)$value;
        } elseif (self::STRING == $type) {
            $value = (string)$value;
        } elseif (self::TEXT == $type) {
            $value = (string)$value;
        } elseif (self::DATETIME == $type) {
            if (false !== ($timestamp = \strtotime($value))) {
                $value = $timestamp;
            }
        }

        $this->vars[$name]['value'] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getVar($name)
    {
        return $this->vars[$name]['value'];
    }

    /**
     * @param $vars
     */
    public function setVars($vars)
    {
        foreach ($this->vars as $key => $v) {
            if (isset($vars[$key])) {
                $this->setVar($key, $vars[$key]);
            }
        }
    }

    /**
     * @return array
     */
    public function getVars()
    {
        $vars = [];

        foreach ($this->vars as $name => $var) {
            $vars[$name] = $var['value'];
        }

        return $vars;
    }

    /**
     * @param $name
     * @return bool|float|int|string
     */
    public function getVarSqlEscaped($name)
    {
        $type  = $this->vars[$name]['type'];
        $value = $this->vars[$name]['value'];

        if (self::BOOL == $type) {
            return $value ? true : false;
        }

        if (self::INTEGER == $type) {
            return (int)$value;
        }

        if (self::FLOAT == $type) {
            return (float)$value;
        }

        if (self::STRING == $type) {
            return $GLOBALS['xoopsDB']->escape($value); // todo : size check
        }

        if (self::TEXT == $type) {
            return $GLOBALS['xoopsDB']->escape($value);
        }

        if (self::DATETIME == $type) {
            return \date('Y-m-d H:i:s', $value);
        }
    }

    /**
     * @return array
     */
    public function getVarsSqlEscaped()
    {
        $vars = [];

        foreach ($this->vars as $name => $var) {
            $vars[$name] = $this->getVarSqlEscaped($name);
        }

        return $vars;
    }
}
