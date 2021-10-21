<?php

/**
 * Registers the post type.
 */

$post_type = 'request_form';

$labels = array(
    'name'                  => _x( 'Заявки', 'Post Type General Name', 'text_domain' ),
    'singular_name'         => _x( 'Заявки', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'             => __( 'Заявки', 'text_domain' ),
    'name_admin_bar'        => __( 'Заявки', 'text_domain' ),
    'archives'              => __( 'Архив заявок', 'text_domain' ),
    'attributes'            => __( 'Атрибуты заявки', 'text_domain' ),
    'parent_item_colon'     => __( 'Родитель заявки:', 'text_domain' ),
    'all_items'             => __( 'Все заявки', 'text_domain' ),
    'add_new_item'          => __( 'Добавить новую заявку', 'text_domain' ),
    'add_new'               => __( 'Добавить заявку', 'text_domain' ),
    'new_item'              => __( 'Новая заявка', 'text_domain' ),
    'edit_item'             => __( 'Редактировать заявку', 'text_domain' ),
    'update_item'           => __( 'Обновить заявку', 'text_domain' ),
    'view_item'             => __( 'Смотреть заявку', 'text_domain' ),
    'view_items'            => __( 'Смотреть заявки', 'text_domain' ),
    'search_items'          => __( 'Искать заявку', 'text_domain' ),
    'not_found'             => __( 'Заявок нет', 'text_domain' ),
    'not_found_in_trash'    => __( 'Корзина пуста', 'text_domain' ),
    'featured_image'        => __( 'Featured Image', 'text_domain' ),
    'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
    'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
    'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
    'insert_into_item'      => __( 'Вставить в заявку', 'text_domain' ),
    'uploaded_to_this_item' => __( 'Загрузить в заявку', 'text_domain' ),
    'items_list'            => __( 'Список заявок', 'text_domain' ),
    'items_list_navigation' => __( 'Навигация по списку заявок', 'text_domain' ),
    'filter_items_list'     => __( 'Фильтр списка заявок', 'text_domain' ),
);

$args = array(
    'labels'                => $labels,
    'public'                => false,
    'hierarchical'          => false,
    'show_ui'               => true,
    'supports'              => array('title'),
    'menu_icon'             => 'dashicons-email-alt',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'has_archive'           => true,
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'exclude_from_search'   => true,
);

register_post_type($post_type, $args);