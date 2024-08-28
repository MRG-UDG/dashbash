<?php
namespace MRG\Dashbash\Widgets;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class CTypesWidget implements WidgetInterface
{
    private WidgetConfigurationInterface $configuration;
    private StandaloneView $view;
    private SiteFinder $siteFinder;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView $view,
        SiteFinder $siteFinder
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->siteFinder = $siteFinder;
    }

    public function renderWidgetContent(): string
    {
        $availableListTypes = $this->getListTypes();
        $allLanguages = $this->getAllSiteLanguages();
        $allCTypes = $this->getAllCTypes();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $results = $queryBuilder
            ->select('*')
            ->addSelectLiteral('COUNT(*) AS count')
            ->from('tt_content')
            ->groupBy('sys_language_uid', 'CType', 'list_type')
            ->executeQuery()
            ->fetchAllAssociative();

        $ctypes = [];
        $langaugeUids = [];
        foreach ($results as $result) {
            $langaugeUids[] = $result['sys_language_uid'];
            if (!isset($ctypes[$result['CType']])) {
                $ctypes[$result['CType']] = [
                    $result['sys_language_uid'] => $result['count']
                ];
            } else {
                $ctypes[$result['CType']][$result['sys_language_uid']] = $result['count'];
            }
        }
        $langaugeUids = array_unique($langaugeUids);

        $this->view->setTemplatePathAndFilename(
            'EXT:dashbash/Resources/Private/Templates/Widget/CTypesWidget.html'
        );
        $this->view->assignMultiple([
            'title' => 'CTypes Overview',
            'configuration' => $this->configuration,
            'allLanguages' => $allLanguages,
            'allCTypes' => $allCTypes,

            'ctypes' => $ctypes,
            'langaugeUids' => $langaugeUids,
            'availableListTypes' => $availableListTypes,
        ]);

        return $this->view->render();
    }

    private function getAllCTypes(): array
    {
        $allCTypes = [];

        $tcaCtypes = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'];
        foreach ($tcaCtypes as $ctype) {
            if (is_array($ctype) && isset($ctype[0], $ctype[1])) {
                $allCTypes[$ctype[1]] = [
                    'name' => $ctype[0],
                    'ctype' => $ctype[1],
                    'listTypes' => ($ctype[1] === 'list') ? $this->getListTypes() : []
                ];
            }
        }

        return $allCTypes;
    }

    private function getListTypes(): array
    {
        $listTypes = [];

        $tcaListTypes = $GLOBALS['TCA']['tt_content']['columns']['list_type']['config']['items'];
        foreach ($tcaListTypes as $listType) {
            if (is_array($listType) && isset($listType[0], $listType[1]) && $listType[0] != '') {
                $listTypes[$listType[1]] = $listType[0];
            }
        }

        return $listTypes;
    }

    private function getAllSiteLanguages(): array
    {
        $allLanguages = [];
        $sites = $this->siteFinder->getAllSites();

        foreach ($sites as $site) {
            foreach ($site->getAllLanguages() as $language) {
                $languageId = $language->getLanguageId();
                if (!isset($allLanguages[$languageId])) {
                    $allLanguages[$languageId] = [
                        'title' => $language->getTitle(),
                        'twoLetterIsoCode' => $language->getTwoLetterIsoCode(),
                        'hreflang' => $language->getHreflang(),
                        'flagIdentifier' => $language->getFlagIdentifier(),
                        'siteNames' => [$site->getIdentifier()],
                        'siteRootPageIds' => [$site->getRootPageId()],
                    ];
                } else {
                    // If the language is used in multiple sites, add the site name and rootpage to the list
                    if (!in_array($site->getIdentifier(), $allLanguages[$languageId]['siteName'])) {
                        $allLanguages[$languageId]['siteNames'][] = $site->getIdentifier();
                        $allLanguages[$languageId]['siteRootPageIds'][] = $site->getRootPageId();
                    }
                }
            }
        }

        // Sort languages by their ID
        ksort($allLanguages);

        return $allLanguages;
    }

    public function getOptions(): array
    {
        return [];
    }
}
