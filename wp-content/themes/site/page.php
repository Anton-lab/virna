<?php

/**
 * Шаблон для отображения всех страниц.
 * Это шаблон, который по умолчанию отображает все страницы.
 */

$context         = Timber::context();
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

$templates = array('pages/page-' . $timber_post->post_name . '.twig', 'pages/page.twig');

Timber::render( $templates, $context );