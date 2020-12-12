<?php

/**
 * Class Shiori_AssignXoopsModuleHeader
 */
class Shiori_AssignXoopsModuleHeader extends XCube_ActionFilter
{
    public function preBlockFilter()
    {
        $this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl', [&$this, 'hook']);
    }

    /**
     * @param $xoopsTpl
     */
    public function hook($xoopsTpl)
    {
        $dirname = basename(dirname(__DIR__));
        require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/class/javascript_loader.php';
        $xoopsModuleHeader = $xoopsTpl->get_template_vars('xoops_module_header') . shiori_get_javascript_link();
        $xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader);
    }
}
