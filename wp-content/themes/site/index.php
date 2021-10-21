<?php

/*
 * Template Name: Главная страница
 */

$context            = Timber::get_context();
$context['post']    = Timber::get_post();
$templates          = array('pages/index.twig');

Timber::render($templates, $context);