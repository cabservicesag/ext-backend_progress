<?php

defined('TYPO3_MODE') or die();

(function () {
    // Add the menu toolbar
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1604323154] = \Cabag\BackendProgress\Toolbar\TaskProgressToolbarItem::class;
})();
