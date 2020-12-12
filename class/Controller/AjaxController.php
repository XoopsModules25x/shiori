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
 * Class AjaxController
 * @package XoopsModules\Shiori\Controller
 */
class AjaxController extends Shiori\Abstracts\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            exit(json_encode('ERROR'));
        }

        if ('ticket' === Shiori\ShioriClass::$Action) {
            $this->_ticket();
        } elseif ('delete' === Shiori\ShioriClass::$Action) {
            $this->_delete();
        } else {
            $this->_default();
        }
    }

    protected function _default()
    {
        $url = Shiori\ShioriClass::post('url');

        if (!$url) {
            exit(json_encode('ERROR'));
        }

        $uid             = Shiori\ShioriClass::uid();
        $bookmarkHandler = new Shiori\BookmarkHandler();
        $urlExists       = $bookmarkHandler->urlExists($uid, $url);

        if ($urlExists) {
            exit(json_encode('1'));
        }
        exit(json_encode('0'));
    }

    protected function _ticket()
    {
        exit(json_encode(Shiori\Ticket::issue()));
    }

    protected function _delete()
    {
        $ticket = Shiori\ShioriClass::post('ticket');

        if (!Shiori\Ticket::check($ticket)) {
            exit(json_encode('ERROR'));
        }

        $url = Shiori\ShioriClass::post('url');

        if (!$url) {
            exit(json_encode('ERROR'));
        }

        $uid             = Shiori\ShioriClass::uid();
        $bookmarkHandler = new Shiori\BookmarkHandler();
        $result          = $bookmarkHandler->deleteByUrl($uid, $url);

        if ($result) {
            exit(json_encode('1'));
        }
        exit(json_encode('0'));
    }
}
