<?php
defined('TYPO3') || die();


// Register css processing parser
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/scss-compiler/css']['parser'][\Passionweb\ScssCompiler\Parser\ScssParser::class] =
    Passionweb\ScssCompiler\Parser\ScssParser::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][\Passionweb\ScssCompiler\Hooks\PageRenderer\PreProcessHook::class]
    = \Passionweb\ScssCompiler\Hooks\PageRenderer\PreProcessHook::class . '->execute';

