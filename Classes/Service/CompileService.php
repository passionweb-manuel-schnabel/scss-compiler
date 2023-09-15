<?php declare(strict_types=1);

namespace Passionweb\ScssCompiler\Service;

use Passionweb\ScssCompiler\Parser\ParserInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CompileService
{
    /**
     * @var string
     */
    protected $tempDirectory = 'typo3temp/assets/compiledscss/css/';

    /**
     * @var string
     */
    protected $tempDirectoryRelativeToRoot = '../../../../';

    /**
     * @param string $file
     * @return string|null
     * @throws \Exception
     */
    public function getCompiledFile(string $file): ?string
    {
        $absoluteFile = GeneralUtility::getFileAbsFileName($file);
        $configuration = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_scsscompiler.']['settings.'] ?? [];

        // Ensure cache directory exists
        if (!file_exists(Environment::getPublicPath() . '/' . $this->tempDirectory)) {
            GeneralUtility::mkdir_deep(Environment::getPublicPath() . '/' . $this->tempDirectory);
        }

        // Settings
        $settings = [
            'file' => [
                'absolute' => $absoluteFile,
                'relative' => $file,
                'info' => pathinfo($absoluteFile)
            ],
            'cache' => [
                'tempDirectory' => $this->tempDirectory,
                'tempDirectoryRelativeToRoot' => $this->tempDirectoryRelativeToRoot,
            ],
            'options' => [
                'override' => (bool) ($configuration['overrideParserVariables'] ?? false),
                'sourceMap' => (bool) ($configuration['cssSourceMapping'] ?? false),
                'compress' => true
            ],
            'variables' => []
        ];

        // Parser
        if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/scss-compiler/css']['parser'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/scss-compiler/css']['parser'])
        ) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/scss-compiler/css']['parser'] as $className) {
                /** @var class-string<ParserInterface> $className */
                $parser = GeneralUtility::makeInstance($className);
                if ($parser instanceof ParserInterface
                    && isset($settings['file']['info']['extension'])
                    && $parser->supports($settings['file']['info']['extension'])
                ) {
                    if ((bool) ($configuration['overrideParserVariables'] ?? false)) {
                        $settings['variables'] = $this->getVariablesFromConstants($settings['file']['info']['extension']);
                    }
                    try {
                        return $parser->compile($file, $settings);
                    } catch (\Exception $e) {
                        $this->clearCompilerCaches();
                        throw $e;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param string $extension
     * @return array
     */
    protected function getVariablesFromConstants(string $extension): array
    {
        $constants = $this->getConstants();
        $extension = strtolower($extension);
        $variables = [];

        // Fetch settings
        $prefix = 'plugin.tx_scsscompiler.settings.' . $extension . '.';
        foreach ($constants as $constant => $value) {
            if (strpos($constant, $prefix) === 0) {
                $variables[substr($constant, strlen($prefix))] = $value;
            }
        }

        return $variables;
    }

    /**
     * @return array
     */
    protected function getConstants(): array
    {
        if ($GLOBALS['TSFE']->tmpl->flatSetup === null
            || !is_array($GLOBALS['TSFE']->tmpl->flatSetup)
            || count($GLOBALS['TSFE']->tmpl->flatSetup) === 0) {
            $GLOBALS['TSFE']->tmpl->generateConfig();
        }
        return $GLOBALS['TSFE']->tmpl->flatSetup;
    }

    /**
     * Clear all caches for the compiler.
     */
    protected function clearCompilerCaches(): void
    {
        GeneralUtility::rmdir(Environment::getPublicPath() . '/' . $this->tempDirectory, true);
    }
}
