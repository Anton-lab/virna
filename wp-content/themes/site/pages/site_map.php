<?php

/*
 * Template Name: Карта сайта
 */

$context = Timber::get_context();
$context['post'] = Timber::get_post();

Timber::render('pages/site-map.twig', $context);