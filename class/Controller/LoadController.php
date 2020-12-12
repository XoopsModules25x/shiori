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
 * Class LoadController
 * @package XoopsModules\Shiori\Controller
 */
class LoadController extends Shiori\Abstracts\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $this->_default();
    }

    protected function _default()
    {
        $id = Shiori\ShioriClass::get('id');

        if (!$id) {
            Shiori\ShioriClass::error('Invalid access.');
        }

        $bookmarkHandler = new Shiori\BookmarkHandler();
        $bookmarkObject  = $bookmarkHandler->load($id);
        $uid             = Shiori\ShioriClass::uid();

        if ($bookmarkObject->getVar('uid') != $uid) {
            Shiori\ShioriClass::error('Invalid access.');
        }

        $bookmarkHandler->incrementCounter($id);

        $url = $bookmarkObject->getVar('url');

        \header("Location: $url");
        exit();
    }
}
