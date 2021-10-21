<?php

add_action('init', 'get_scss');

function get_scss()
{
    $inputPath = get_theme_file_path('/scss');
    $outputPath = get_theme_file_path('/style');
    @mkdir($outputPath, 0775, true);
    $itemsToProcess = [];
    $dirIterator = new DirectoryIterator($inputPath);
    $time = '_' . time();

    $files = glob($outputPath . '/*');

    $link = $outputPath . '/';

    $f = '';

    if (WP_DEBUG) {

        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

        foreach ($dirIterator as $item) {
            if (!$item->isFile()) {
                continue;
            }
            if (!($item->getExtension() === "scss")) {
                continue;
            }
            if ($item->getFilename()[0] !== '_') {
                $inputFilename = $inputPath . '/' . $item->getFilename();
                $outputFilename = $outputPath . '/' . $item->getBasename('.scss') . $time . '.css';
                $itemsToProcess[$inputFilename] = $outputFilename;
            }
        }

        function sassCss($inputFilename, $outputFilename, $inputPath)
        {
            $compiler = new ScssPhp\ScssPhp\Compiler();
            $compiler->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');
            $compiler->addImportPath($inputPath);
            $input = file_get_contents($inputFilename);
            $output = $compiler->compile($input);
            file_put_contents($outputFilename, $output);
        }

        foreach ($itemsToProcess as $inputFilename => $outputFilename) {
            sassCss($inputFilename, $outputFilename, $inputPath);
        }

    }

    $files = array_diff(scandir($link), array('.', '..', '.gitkeep'));

    foreach ($files as $file) {
        if($file){
            $f .= '<link rel="stylesheet" href="' . get_template_directory_uri() . '/style/' . $file . '">';
        }
    }

    return ($f);

}