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
 * Class Block
 * @package XoopsModules\Shiori\Abstracts
 */
abstract class Block extends Controller
{
    protected $template  = null;
    protected $data      = [];
    protected $content   = null;
    protected $blockName = null;

    /**
     * Block constructor.
     * @param $blockName
     */
    public function __construct($blockName)
    {
        $this->blockName = $blockName;
    }

    public function main()
    {
    }

    protected function _view()
    {
        $template = 'shiori_block_' . mb_strtolower($this->blockName) . '.tpl';
        $this->_escapeHtml($this->data);
        $xoopsTpl = new \XoopsTpl();
        $xoopsTpl->assign('shiori', $this->data);
        $xoopsTpl->register_modifier('shiori_msg', '\XoopsModules\Shiori\ShioriClass::msg');
        $this->content = $xoopsTpl->fetch('db:' . $template);
        unset($xoopsTpl);
    }
}
