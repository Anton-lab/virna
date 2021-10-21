<?php

use MatthiasMullie\Minify;

add_action('init', 'get_js');

function get_js()
{
    $dir = get_theme_file_path('/scripts/js-compress/');

    $files = scandir($dir);
    asort($files);
    $time = '_' . time();

    $page = '';

    $path = get_theme_file_path('/scripts/');

    if (WP_DEBUG) {

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'js') {
                $page .= file_get_contents($dir . $file);
            }
        };

        $minifier = new Minify\JS($page);

        array_map('unlink', glob($path . 'plugins_*.js'));

        file_put_contents($path . 'plugins' . $time . '.js', $minifier->minify());

        return '<script type="text/javascript" src="' . get_template_directory_uri() . '/scripts/plugins' . $time . '.js' . '"></script>';
    } else {
        $link = scandir($path);
        if ($link[4]) {
            return '<script type="text/javascript" src="' . get_template_directory_uri() . '/scripts/' . $link[4] . '"></script>';
        } else {
            return '';
        }
    }
}

?>