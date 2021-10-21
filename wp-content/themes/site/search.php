<?php

/**
 * Страница результатов поиска.
 */

$context           = Timber::context();
$context['post']   = Timber::get_post();

$templates = array('pages/search.twig');

Timber::render( $templates, $context );