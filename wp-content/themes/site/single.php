<?php
/**
 * Шаблон для отображения отдельной записи.
 */

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

$templates = array('module/item.twig');

//if ( post_password_required( $timber_post->ID ) ) {
//    Timber::render('pages/single-password.twig', $context);
//} else {
array_unshift($templates, 'post-types/' . $timber_post->post_type . '/item.twig');
//}

Timber::render($templates, $context);