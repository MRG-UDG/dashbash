<?php
namespace MRG\Dashbash\Widgets;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class CTypesWidget implements WidgetInterface
{
    private WidgetConfigurationInterface $configuration;
    private StandaloneView $view;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView $view
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
    }

    public function renderWidgetContent(): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $result = $queryBuilder
            ->select('CType')
            ->addSelectLiteral('COUNT(*) AS count')
            ->from('tt_content')
            ->groupBy('CType')
            ->executeQuery()
            ->fetchAllAssociative();

        $this->view->setTemplatePathAndFilename('EXT:dashbash/Resources/Private/Templates/Widget/CTypesWidget.html');
        $this->view->assignMultiple([
            'configuration' => $this->configuration,
            'ctypes' => $result,
        ]);

        return $this->view->render();
    }

    public function getOptions(): array
    {
        return [];
    }
}
