<?php
/**
 * Стартовая тема Timber
 *
 * https://github.com/timber/starter-theme
 *
 * @package  site
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * Если вы устанавливаете Timber как зависимость Composer в своей теме, вам понадобится этот блок
 * для загрузки ваших зависимостей и инициализации Timber.
 * */
require_once( __DIR__ . '/vendor/autoload.php' );

$timber = new Timber\Timber();

/**
 * Устанавливает каталоги (внутри вашей темы) для поиска файлов .twig
 */
Timber::$dirname = array('templates', 'post-types');

/**
 * По умолчанию Timber НЕ выполняет автоматическое экранирование значений. Хотите включить автоэскейп Twig?
 * Нет проблем! Просто установите для этого значения значение true
 */
Timber::$autoescape = false;

/**
 * Включает кэширования файлов .twig (но не данные).
 */
Timber::$twig_cache = true;

/**
 * Мы собираемся настроить нашу тему внутри подкласса Timber\Site
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_libs'));
        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));
        add_filter('wp_enqueue_scripts', array($this, 'register_scripts'));
        add_filter('init', array($this, 'register_acf_options_pages'));
        add_filter('init', array($this, 'admin_setup'));
        parent::__construct();
    }

    public function theme_supports()
    {
        /*
		 * Сделать тему доступной для перевода.
		 * Переводы можно хранить в каталоге /languages/.
		 */
        load_theme_textdomain('site', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Разрешите WordPress управлять заголовком документа.
         * Добавляя поддержку темы, мы заявляем, что эта тема не использует
         * жестко запрограммированный тег <title> в заголовке документа и ожидайте, что WordPress
         * предоставьте это нам.
         */
        add_theme_support('title-tag');

        /*
        * Включите поддержку миниатюр для постов и страниц.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
        add_theme_support('post-thumbnails');

        /*
		 * Переключение основной разметки по умолчанию для формы поиска, формы комментариев и комментариев
		 * для вывода действительного HTML5.
		 */

        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
        add_theme_support(
            'post-formats', array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');

        register_nav_menus([
            'header_menu' => 'header_menu',
        ]);
    }

    /** Здесь вы добавляете глобальные переменные
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     * @return string
     */
    public function add_to_context($context)
    {
        $context['current_link'] = get_permalink();

//        $context['logo'] = get_field('logo', 'option');

        $context['menu'] = new Timber\Menu('header_menu');
        $context['site'] = $this;
        return $context;
    }

    /** Это вернет foo bar.
     *
     * @param string $text
     * @return string
     */
    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    /** Здесь вы можете добавить свои собственные функции в twig.
     *
     * @param string $twig get extension.
     * @return string
     */
    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig_Extension_StringLoader());
        $twig->addFilter(new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
        return $twig;
    }

    /**
     * Ставьте скрипты и стили в очередь.
     */
    public function register_scripts()
    {
        wp_deregister_script('jquery');
        wp_register_script('jquery', get_template_directory_uri() . '/scripts/jquery.js', false, null, true);
        wp_enqueue_script('jquery');
        wp_enqueue_script('system', get_template_directory_uri() . '/scripts/system.js', array('jquery'), null, true);
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, false );
    }

    /**
     * Здесь вы можете зарегистрировать типы записей.
     */
    public function register_post_types()
    {
        $dir = get_theme_file_path('/post-types/');

        $files = glob($dir . "/*.*", GLOB_NOSORT);
        do {
            $dir = $dir . "/*";
            $files2 = glob($dir . "/*.php", GLOB_NOSORT);
            $files = array_merge($files, $files2);
        } while (sizeof($files2) > 0);

        foreach ($files as $file) {
            require($file);
        }
    }


    /**
     * Здесь вы можете зарегистрировать библиотеки.
     */
    public function register_libs()
    {
        $dir = get_theme_file_path('/libs/');
        $libs = array_diff(scandir($dir), array('.', '..'));
        foreach ($libs as $lib) {
            if($lib){
                include __DIR__ . '/libs/' . $lib;
            }
        }
    }

    /**
     * Добавляет тип записи дополнительных настроек.
     */
    public function register_acf_options_pages()
    {
        // Check function exists.
        if (!function_exists('acf_add_options_page'))
            return;

        // register options page.
        $option_page = acf_add_options_page(array(
            'page_title' => __('Additional settings'),
            'menu_title' => __('Additional settings'),
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));
    }

    /**
     * Подключение настроек админки.
     **/
    public function admin_setup()
    {
        include __DIR__ . '/admin/settings.php';
    }
}

new StarterSite();