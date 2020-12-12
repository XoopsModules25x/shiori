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

use XoopsModules\Shiori;

/**
 * Class shiori_blocks_javascript
 */
class Javascript extends Shiori\Abstracts\Block
{
    /**
     * @return false
     */
    public function main()
    {
        global $xoopsTpl;
        $dirname = basename(dirname(__DIR__,2));

        require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/class/javascript_loader.php';

        $xoopsModuleHeader = $xoopsTpl->get_template_vars('xoops_module_header') . shiori_get_javascript_link();
        $xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader);

        return false;
    }
}
