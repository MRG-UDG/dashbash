<?php
defined('TYPO3') or die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '@import "EXT:dashbash/Configuration/TypoScript/constants.typoscript"'
    );
})();
