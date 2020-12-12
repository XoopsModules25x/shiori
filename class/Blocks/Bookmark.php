<?php

namespace XoopsModules\Shiori\Blocks;

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
 * Class shiori_blocks_bookmark
 */
class Bookmark extends Shiori\Abstracts\Block
{
    /**
     * @return false|void|null
     */
    public function main()
    {
        if (!$this->_isUser()) {
            return false;
        }

        if (Request::hasVar('HTTPS', 'SERVER') && 'on' === $_SERVER['HTTPS']) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $uid             = Shiori\ShioriClass::uid();
        $bookmarkHandler = new Shiori\BookmarkHandler();
        $isBookmarked    = $bookmarkHandler->urlExists($uid, $url);

        $this->data['url']           = $url;
        $this->data['is_bookmarked'] = $isBookmarked;

        $this->_view();

        return $this->content;
    }
}
