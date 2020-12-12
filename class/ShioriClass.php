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

if (\class_exists('ShioriClass')) {
    return;
}

/**
 * Class ShioriClass
 * @package XoopsModules\Shiori
 */
class ShioriClass
{
    /**
     * Names cosist of [a-z0-9_]
     * Those are usually used for file names.
     */
    public static $_controller;
    public static $_action;
    /**
     * Names cosist of [A-Za-z0-9]
     * Those are usually used for class or method names.
     */
    public static $Controller;
    public static $Action;
    /**
     * Names cosist of [a-z0-9]
     * Those are usually used for template file names.
     */
    public static $controller;
    public static $action;
    public static $lang = [];

    /**
     * Main frame
     */
    public static function setup()
    {
        if (\defined('SHIORI_LOADED')) {
            return;
        }

        \define('SHIORI_DIR', \basename(dirname(__DIR__)));
        \define('SHIORI_PATH', XOOPS_ROOT_PATH . '/modules/' . \SHIORI_DIR);
        \define('SHIORI_URL', XOOPS_URL . '/modules/' . \SHIORI_DIR);

        //        spl_autoload_register([__CLASS__, 'autoload']);

        self::_language();

        \define('SHIORI_LOADED', true);
    }

    /**
     * @param false $isAdmin
     */
    public static function execute($isAdmin = false)
    {
        $controller = self::get('controller', 'default');
        $action     = self::get('action', 'default');

        self::$Controller = self::putintoClassParts($controller);
        self::$Action     = self::putintoClassParts($action);
        self::$Action[0]  = mb_strtolower(self::$Action[0]);

        self::$controller = mb_strtolower(self::$Controller);
        self::$action     = mb_strtolower(self::$Action);

        self::$_controller = self::putintoPathParts(self::$Controller);
        self::$_action     = self::putintoPathParts(self::$Action);

        if ($isAdmin) {
            $class = '\XoopsModules\Shiori\Controller\\' .self::$Controller . 'AdminController';
        } else {
            $class = '\XoopsModules\Shiori\Controller\\' . self::$Controller . 'Controller';
        }

        $instance = new $class();
        $instance->main();

        unset($instance);
    }

    /**
     * @param $blockName
     * @return mixed
     */
    public static function block($blockName)
    {
        $class    = '\XoopsModules\Shiori\Blocks\\' . $blockName;
        $instance = new $class($blockName);
        $result   = $instance->main();
        unset($instance);

        return $result;
    }

    //    public static function autoload($class)
    //    {
    //        if (class_exists($class, false)) {
    //            return;
    //        }
    //        if (!preg_match('/^Shiori_/', $class)) {
    //            return;
    //        }
    //
    //        $parts = explode('_', $class);
    //        $parts = array_map([__CLASS__, 'putintoPathParts'], $parts);
    //
    //        $module = array_shift($parts);
    //
    //        $class = implode('/', $parts);
    //        $path  = sprintf('%s/%s.php', SHIORI_PATH, $class);
    //
    //        if (!file_exists($path)) {
    //            return;
    //        }
    //
    //        require_once $path;
    //    }

    /**
     * Useful functions
     * @param      $name
     * @param null $default
     * @return null|string
     */
    public static function get($name, $default = null)
    {
        $request = $_GET[$name] ?? $default;
        if (@\get_magic_quotes_gpc() and !\is_array($request)) {
            $request = \stripslashes($request);
        }

        return $request;
    }

    /**
     * @param      $name
     * @param null $default
     * @return array|mixed|string|null
     */
    public static function post($name, $default = null)
    {
        $request = $_POST[$name] ?? $default;
        if (@\get_magic_quotes_gpc() and !\is_array($request)) {
            $request = \stripslashes($request);
        }

        return $request;
    }

    /**
     * @param null  $apps
     * @param null  $controller
     * @param null  $action
     * @param array $params
     * @return string
     */
    public static function url($apps = null, $controller = null, $action = null, $params = [])
    {
        if ($action) {
            $params = \array_unshift($params, ['action' => $action]);
        }
        if ($controller) {
            $params = \array_unshift($params, ['controller' => $controller]);
        }
        if ($apps) {
            $params = \array_unshift($params, ['apps' => $apps]);
        }
        $param = \http_build_query($params);
        $url   = \SHIORI_URL . '/index.php?' . $param;

        return $url;
    }

    /**
     * @param $message
     * @return mixed|string|string[]
     */
    public static function msg($message)
    {
        if (isset(self::$lang[$message])) {
            $message = self::$lang[$message];
        }

        if (1 == \func_num_args()) {
            return $message;
        }

        $params = \func_get_args();

        foreach ($params as $i => $param) {
            $message = \str_replace('{' . $i . '}', $param, $message);
        }

        return $message;
    }

    /**
     * @param $str
     * @return string
     */
    public static function putintoClassParts($str)
    {
        $str = \preg_replace('/[^a-z0-9_]/', '', $str);
        $str = \explode('_', $str);
        $str = \array_map('\trim', $str);
        $str = \array_diff($str, ['']);
        $str = \array_map('\ucfirst', $str);
        $str = \implode('', $str);

        return $str;
    }

    /**
     * @param $str
     * @return false|mixed|string
     */
    public static function putintoPathParts($str)
    {
        $str = \preg_replace('/[^a-zA-Z0-9]/', '', $str);
        $str = \preg_replace('/([A-Z])/', '_$1', $str);
        $str = mb_strtolower($str);
        $str = mb_substr($str, 1, mb_strlen($str));

        return $str;
    }

    /**
     * @param      $msg
     * @param null $url
     */
    public static function redirect($msg, $url = null)
    {
        \redirect_header($url, 3, self::msg($msg));
    }

    /**
     * @param $msg
     */
    public static function error($msg)
    {
        \xoops_error(self::msg($msg));
        exit();
    }

    /**
     * @return \XoopsDatabase
     */
    public static function database()
    {
        static $db;

        if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }

        return $db;
    }

    /**
     * @param $string
     * @return string
     */
    public static function escapeHtml($string)
    {
        return \htmlspecialchars($string, \ENT_QUOTES);
    }

    /**
     * @return int
     */
    public static function uid()
    {
        global $xoopsUser;

        return $xoopsUser->uid();
    }

    protected static function _language()
    {
        $encode   = mb_strtolower(_CHARSET);
        $langcode = mb_strtolower(_LANGCODE);

        $langFile = \SHIORI_PATH . '/language/' . $langcode . '.xml';

        if (!\file_exists($langFile)) {
            return;
        }

        if (\defined('XOOPS_TRUST_PATH') and \file_exists(XOOPS_TRUST_PATH . '/cache')) {
            $cacheDir = XOOPS_TRUST_PATH . '/cache';
        } else {
            $cacheDir = XOOPS_ROOT_PATH . '/cache';
        }

        $cacheFile = $cacheDir . '/shiori_' . $langcode . '_' . $encode . '.php';

        if (\is_file($cacheFile)) {
            self::$lang = require_once $cacheFile;

            return;
        }

        $langXml  = \simplexml_load_file($langFile, Language::class);
        $messages = $langXml->messages();

        if ('utf-8' !== $encode) {
            mb_convert_variables(_CHARSET, 'UTF-8', $messages);
        }

        self::$lang = $messages;

        $cacheContent = "<?php\nreturn " . \var_export($messages, true) . ";\n?>\n";

        file_put_contents($cacheFile, $cacheContent);
    }
}
