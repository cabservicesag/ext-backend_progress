<?php

defined('TYPO3_MODE') or die();

(function () {
    // Add the menu toolbar
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1604323154] = \Cabag\BackendProgress\Toolbar\TaskProgressToolbarItem::class;

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    $iconRegistry->registerIcon(
        'tx-backend-progress-icon', // Icon-Identifier, e.g. tx-myext-action-preview
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:backend_progress/Resources/Public/Icons/Extension.svg']
    );
})();
