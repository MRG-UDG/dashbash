<?php
defined('TYPO3') or die();

(static function() {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:dashbash/Configuration/TypoScript/setup.typoscript"'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    $iconRegistry->registerIcon(
        'ext-dashbash-icon',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:dashbash/Resources/Public/Icons/dashbash-icon.svg']
    );
    $iconRegistry->registerIcon(
        'content-widget-dashbash',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:dashbash/Resources/Public/Icons/content-widget-dashbash.svg']
    );

})();
