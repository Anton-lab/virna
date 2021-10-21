<?php

$context = Timber::get_context();
$context['post'] = Timber::get_post();

Timber::render('pages/404.twig', $context);