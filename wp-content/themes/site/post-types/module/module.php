<?php

/**
 * Registers the post type.
 */

$post_type = 'module';

$labels = array(
    'name'                      => _x('Модуль', 'Post Type General Name', 'text_domain'),
    'singular_name'             => _x('Модуль', 'Post Type Singular Name', 'text_domain'),
    'menu_name'                 => __('Модуль', 'text_domain'),
    'name_admin_bar'            => __('Модуль', 'text_domain'),
    'archives'                  => __('Архив модуля', 'text_domain'),
    'attributes'                => __('Атрибуты материала', 'text_domain'),
    'parent_item_colon'         => __('Родитель материала:', 'text_domain'),
    'all_items'                 => __('Все материалы', 'text_domain'),
    'add_new_item'              => __('Добавить новый материал', 'text_domain'),
    'add_new'                   => __('Добавить материал', 'text_domain'),
    'new_item'                  => __('Новый материал', 'text_domain'),
    'edit_item'                 => __('Редактировать материал', 'text_domain'),
    'update_item'               => __('Обновить материал', 'text_domain'),
    'view_item'                 => __('Смотреть материал', 'text_domain'),
    'view_items'                => __('Смотреть материалы', 'text_domain'),
    'search_items'              => __('Искать материал', 'text_domain'),
    'not_found'                 => __('Материалов нет', 'text_domain'),
    'not_found_in_trash'        => __('Корзина пуста', 'text_domain'),
    'featured_image'            => __('Изображение материала', 'text_domain'),
    'set_featured_image'        => __('Установить избранное изображение', 'text_domain'),
    'remove_featured_image'     => __('Удалить избранное изображение', 'text_domain'),
    'use_featured_image'        => __('Использовать избранное изображение', 'text_domain'),
    'insert_into_item'          => __('Вставить в материал', 'text_domain'),
    'uploaded_to_this_item'     => __('Загрузить в материал', 'text_domain'),
    'items_list'                => __('Список материалов', 'text_domain'),
    'items_list_navigation'     => __('Навигация по списку материалов', 'text_domain'),
    'filter_items_list'         => __('Фильтр списка материалов', 'text_domain'),
);

$rewrite = array(
    'slug'          => $post_type,
    'with_front'    => true
);

$args = array(
    'labels'                => $labels,
    'public'                => true,
    'hierarchical'          => true,
    'show_ui'               => true,
    'supports'              => array('title', 'editor'),
    'menu_icon'             => 'dashicons-products',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'has_archive'           => true,
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'rewrite'               => $rewrite,
    'exclude_from_search'   => true,
);

register_post_type($post_type, $args);

/**
 * Registers the taxonomy.
 */

$labels_taxonomy = array(
    'name'                       => _x( 'Разделы', 'Taxonomy General Name', 'text_domain' ),
    'singular_name'              => _x( 'Разделы', 'Taxonomy Singular Name', 'text_domain' ),
    'menu_name'                  => __( 'Разделы', 'text_domain' ),
    'all_items'                  => __( 'Все разделы', 'text_domain' ),
    'parent_item'                => __( 'Родитель раздела', 'text_domain' ),
    'parent_item_colon'          => __( 'Родитель раздела:', 'text_domain' ),
    'new_item_name'              => __( 'Название нового раздела', 'text_domain' ),
    'add_new_item'               => __( 'Добавить новый раздел', 'text_domain' ),
    'edit_item'                  => __( 'Редактировать раздел', 'text_domain' ),
    'update_item'                => __( 'Обновить раздел', 'text_domain' ),
    'view_item'                  => __( 'Смотреть раздел', 'text_domain' ),
    'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
    'add_or_remove_items'        => __( 'Добавить или удалить раздел', 'text_domain' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
    'popular_items'              => __( 'Популярные разделы', 'text_domain' ),
    'search_items'               => __( 'Искать раздел', 'text_domain' ),
    'not_found'                  => __( 'Not Found', 'text_domain' ),
    'no_terms'                   => __( 'Нет разделов', 'text_domain' ),
    'items_list'                 => __( 'Список разделов', 'text_domain' ),
    'items_list_navigation'      => __( 'Навигация по списку разделов', 'text_domain' ),
);
$rewrite_taxonomy = array(
    'slug'                       => $post_type . '-category',
    'with_front'                 => true,
    'hierarchical'               => true,
);
$args_taxonomy = array(
    'labels'                     => $labels_taxonomy,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'rewrite'                    => $rewrite_taxonomy,
    'query_var'                  => true,
);
register_taxonomy( $post_type . '-category', array( $post_type ), $args_taxonomy );