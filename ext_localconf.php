<?php
defined('TYPO3') or die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:dashbash/Configuration/TypoScript/setup.typoscript"'
    );
})();
