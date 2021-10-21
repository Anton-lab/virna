<?php

/**
 * Убирает типы записей из меню админки.
 */
add_filter('admin_menu', 'remove_menus');

function remove_menus()
{
    remove_menu_page('edit.php');                 # Записи
    remove_menu_page('edit-comments.php');        # Комментарии
    remove_menu_page('upload.php');               # Медиафайлы
    if (!WP_DEBUG) {
        remove_menu_page('edit.php?post_type=acf-field-group');
    }
}

/**
 * Отключает уведомления об обновлении плагина.
 */
add_filter('site_transient_update_plugins', 'remove_plugin_updates');

function remove_plugin_updates($value)
{
    if (!is_object($value)) return $value;
    unset($value->response['advanced-custom-fields-pro/acf.php']);
    return $value;
}

/**
 * Считает ожидающие к подтверждению посты.
 **/
add_action('admin_menu', 'add_user_menu_bubble');

function add_user_menu_bubble()
{
    global $menu;

    $post_types = get_post_types();

    foreach ($post_types as $post_type) {
        $count = (array)wp_count_posts($post_type);
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == 'edit.php?post_type=' . $post_type . '') {
                $menu[$key][0] .= '<span class="update-plugins count-' . $count['pending'] . '"><span class="plugin-count">' . $count['pending'] . '</span></span>';
            }
        }
    }
}

/**
 * Полное удаление версии WP из заголовка, фидов и URL.
 */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/**
 * Отключает вывод ошибок на странице авторизации.
 */
add_filter('login_errors', 'login_obscure_func');

function login_obscure_func()
{
    return 'Ошибка: вы ввели неправильный логин или пароль.';
}

/**
 * Отключает возможность редактировать файлы в админке для тем, плагинов.
 */
define('DISALLOW_FILE_EDIT', true);

/**
 * Умный показ ошибок PHP.
 */
add_action('init', 'enable_errors');

function enable_errors()
{
    if ($GLOBALS['user_level'] < 5)
        return;

    error_reporting(E_ALL ^ E_NOTICE);
    ini_set("display_errors", 1);
}

