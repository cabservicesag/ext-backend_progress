<?php

namespace Cabag\BackendProgress\Toolbar;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TaskProgressToolbarItem implements ToolbarItemInterface
{

    public function __construct()
    {
        GeneralUtility::makeInstance(PageRenderer::class)
            ->loadRequireJsModule('Cabag/BackendProgress/ProgressBarToolbarItem');
    }

    public function checkAccess()
    {
        return true;
    }

    public function getItem()
    {
        // TODO: Make this as fluid template (icon)
        return '<span id="t3-backend-backend-progress" class="toolbar-item-icon" title="Backend Task Progress"></span><span class="toolbar-item-title"></span>';
    }

    public function hasDropDown()
    {
        return true;
    }

    public function getDropDown()
    {
        return '<div id="t3-backend-progress-container"></div>';
    }

    public function getAdditionalAttributes()
    {
        return [];
    }

    public function getIndex()
    {
        return 100;
    }
}
