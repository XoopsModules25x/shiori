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

use Xmf\Request;
use XoopsModules\Shiori;

/**
 * Class Ticket
 * @package XoopsModules\Shiori
 */
class Ticket
{
    /**
     * @param int $timeout
     * @return string
     */
    public static function issue($timeout = 180)
    {
        $expire = \time() + (int)$timeout;
        $token  = \md5(\uniqid('', true) . \mt_rand());

        if (Request::hasVar('shiori_tickets', 'SESSION') && \is_array($_SESSION['shiori_tickets'])) {
            if (\count($_SESSION['shiori_tickets']) >= 5) {
                \asort($_SESSION['shiori_tickets']);
                $_SESSION['shiori_tickets'] = \array_slice($_SESSION['shiori_tickets'], -4, 4);
            }

            $_SESSION['shiori_tickets'][$token] = $expire;
        } else {
            $_SESSION['shiori_tickets'] = [$token => $expire];
        }

        return $token;
    }

    /**
     * @param $stub
     * @return bool
     */
    public static function check($stub)
    {
        if (!isset($_SESSION['shiori_tickets'][$stub])) {
            return false;
        }
        if (\time() >= $_SESSION['shiori_tickets'][$stub]) {
            return false;
        }

        unset($_SESSION['shiori_tickets'][$stub]);

        return true;
    }
}
