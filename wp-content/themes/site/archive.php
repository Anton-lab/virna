<?php

/*
 * Template Name: Страница типа записи
 */

$context = Timber::context();
$context['post'] = Timber::get_post();

$templates = array('module/archive.twig');

if (is_post_type_archive()) {
    $sections = get_categories([
        'parent' => 0,
        'hide_empty' => 0,
        'taxonomy' => get_post_type() . '-category'
    ]);

    if ($sections){
        $data = array_map(function ($section) {
            $section->fields = get_field('category', $section);
            return $section;
        }, $sections);
        $context['data'] = $data;
        array_unshift($templates, 'post-types/' . get_post_type() . '/sections.twig');
    }
    else{
        array_unshift($templates, 'post-types/' . get_post_type() . '/items.twig');
    }

    $context['title'] = post_type_archive_title('', false);
} elseif (is_tax()) {

    $term_id = get_queried_object()->term_id;
    $taxonomy = get_queried_object()->taxonomy;
    $post_name = get_taxonomy(get_queried_object()->taxonomy);
    $post_name = $post_name->object_type[0];

    $context['section'] = get_term($term_id, $taxonomy);

    $sections = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => 0,
        'parent' => $term_id,
    ]);

    $items = get_posts([
        'tax_query' => [[
            'taxonomy' => $taxonomy,
            'terms' => $term_id
        ]],
        'post_type' => $post_name,
    ]);

    if ($sections) {
        $data = array_map(function ($section) {
            $section->fields = get_field('category', $section);
            return $section;
        }, $sections);
        $context['data'] = $data;
        array_unshift($templates, 'post-types/' . $post_name . '/sections.twig');
    } else {
        $context['data'] = $items;
        array_unshift($templates, 'post-types/' . $post_name . '/items.twig');
    }

    $context['title'] = single_tag_title('', false);
}

Timber::render($templates, $context);