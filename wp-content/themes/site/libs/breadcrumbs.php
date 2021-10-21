<?php

function markup($url, $title, $position_bread)
{
    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . $url . '" itemprop="item"><span itemprop="name">' . $title . '</span></a><meta itemprop="position" content="' . $position_bread . '"/></li>';
}

function markup_tax($val, $position_bread)
{
    echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $val . '<meta itemprop="position" content="' . $position_bread . '"/></li>';
}

function get_term_list( $term_id, $taxonomy) {
    $list = '';
    $term = get_term( $term_id, $taxonomy );

    if ( is_wp_error( $term ) ) {
        return $term;
    }

    if ( ! $term ) {
        return $list;
    }

    $term_id = $term->term_id;

    $parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );

    foreach ( array_reverse( $parents ) as $term_id ) {
        $parent = get_term( $term_id, $taxonomy );
        $list .= '<a href="' . esc_url( get_term_link( $parent->term_id, $taxonomy ) ) . '" itemprop="item"><span itemprop="name">'. $parent->name . '</span></a>';
    }

    return $list;
}

function breadcrumbs()
{
    /* === ОПЦИИ === */
    $text['home'] = __('Главная');
    $text['category'] = __('%s');
    $text['search'] = __('Результаты поиска по запросу "%s"');
    $text['tag'] = __('Записи с тегом "%s"');
    $text['404'] = __('Ошибка 404');
    $text['page'] = __('Страница %s');

    $wrap_before = '<ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
    $wrap_after = '</ul>'; // закрывающий тег обертки

    $show_on_home = 1; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
    /* === КОНЕЦ ОПЦИЙ === */

    global $post;
    $home_url = home_url('/');
    $home_link = sprintf('%2$s', $home_url, $text['home'], 1);

    $parent_id = ($post) ? $post->post_parent : '';
    $position = 0;

    if (is_home() || is_front_page()) {
        if ($show_on_home) {
            echo $wrap_before;
            markup($home_url, $home_link, $position);
            echo $wrap_after;
        }
    } else {
        echo $wrap_before;
        markup($home_url, $home_link, $position);
        $position += 1;

        if (is_category()) {
            $parents = get_ancestors(get_query_var('cat'), 'category');
            foreach (array_reverse($parents) as $cat) {
                markup(get_category_link($cat), get_cat_name($cat), $position);
                $position += 1;
            }
            $cat = get_query_var('cat');
            markup(get_category_link($cat), single_cat_title('', false), $position);

        } elseif (is_tax()) {
            $term_id = get_queried_object()->term_id;
            $name = get_queried_object()->name;
            $slug = get_queried_object()->slug;
            $taxonomy = get_taxonomy(get_queried_object()->taxonomy);
            $taxonomy_link = get_queried_object()->taxonomy;

            $trail = $taxonomy->labels->singular_name;
            $list = get_term_list($term_id, $taxonomy->name);
            $list = explode(',', $list);

            markup($home_url . implode($taxonomy->object_type) . '/', $trail, $position);
            foreach ($list as $val) {
                $position += 1;
                if ($val != '') {
                    markup_tax($val, $position);
                }
            }
            $position += 1;
            markup($home_url . $taxonomy_link . '/' . $slug, $name, $position);
        } elseif (is_page() && !$parent_id) {
            markup(get_page_link(), get_the_title(), $position);
        } elseif (is_page() && $parent_id) {
            $parents = get_post_ancestors(get_the_ID());
            foreach (array_reverse($parents) as $pageID) {
                markup(get_page_link($pageID), get_the_title($pageID), $position);
                $position += 1;
            }
            markup(get_page_link(), get_the_title(), $position);
        } elseif (is_404()) {
            markup('', $text['404'], $position);
        } elseif (is_post_type_archive()) {
            $post_type = get_post_type_object(get_post_type());
            markup(get_post_type_archive_link($post_type->name), $post_type->labels->name, $position);
        } elseif (is_search()) {
            markup($home_url, sprintf($text['search'], get_search_query()), $position);
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                markup(get_post_type_archive_link($post_type->name), $post_type->labels->name, $position);

                if (taxonomy_exists($post_type->name . '-category')) {
                    $term = wp_get_post_terms(get_the_ID(), $post_type->name . '-category');
                    $term_parent = get_term_list($term[0]->term_id, $term[0]->taxonomy);
                    $term_parent = explode(',', $term_parent);
                    foreach ($term_parent as $val) {
                        $position += 1;
                        if ($val != '') {
                            markup_tax($val, $position);
                        }
                    }
                    $position += 1;
                    markup($home_url . $term[0]->taxonomy . '/' . $term[0]->slug, $term[0]->name, $position);
                }

                $position += 1;
                markup(get_permalink(), get_the_title(), $position);

            } else {
                $cat = get_the_category();
                $catID = $cat[0]->cat_ID;
                $parents = get_ancestors($catID, 'category');
                $parents = array_reverse($parents);
                $parents[] = $catID;
                foreach ($parents as $cat) {
                    markup(get_category_link($cat), get_cat_name($cat), $position);
                    $position += 1;
                }
                markup(get_page_link(), get_the_title(), $position);
            }
        } elseif (is_tag()) {
            markup('', sprintf($text['tag'], single_tag_title('', false)), $position);
        }
        echo $wrap_after;
    }
}