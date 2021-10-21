<?php

$post_types = get_post_types();

foreach ($post_types as $post_type) {
    add_action('wp_ajax_nopriv_' . $post_type . '', 'send_form');
    add_action('wp_ajax_' . $post_type . '', 'send_form');
}

function send_form()
{

    verify_recaptcha();

    validate_form();

    save_form_in_admin();

    send_form_in_mail();

    successful_message();

    wp_die();
}

function google_recaptcha()
{
    $recaptcha_key = get_field('recaptcha_key', 'option');
    return '<div class="g-recaptcha" data-sitekey="' . $recaptcha_key . '"></div>';
}

function verify_recaptcha()
{
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha_response = $_POST['g-recaptcha-response'];
    } else {
        return false;
    }
    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => get_field('secret_recaptcha_key', 'option'),
                'response' => $captcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        )
    );

    $success = false;

    if ($response && is_array($response)) {
        $decoded_response = json_decode($response['body']);
        $success = $decoded_response->success;
    }
    if (!$success) {
        $return = [
            'success' => true,
        ];
        wp_send_json($return);

        wp_die();
    }
}

function validate_form()
{
    if (empty($_POST['name']) || empty($_POST['phone'])) {
        $return = [
            'success' => false
        ];
        wp_send_json($return);
    }
}

function save_form_in_admin()
{
    foreach ($_POST as $post => $value) {
        $meta_input[$post] = $value;
    }

    $title = get_post_type_object($_POST['action'])->labels->new_item;

    $post_data = [
        'post_title' => $title . __(' от: ') . esc_html($_POST['name']),
        'post_status' => 'pending',
        'post_type' => $_POST['action'],
        'meta_input' => $meta_input,
    ];
    $post_id = wp_insert_post($post_data, true);
}

function send_form_in_mail()
{
    $to = get_field('email_form', 'option');

    $subject = get_post_type_object($_POST['action'])->labels->new_item . " !";

    $message = array(
        $subject . "\r\n",
        'Имя: ' . esc_html($_POST['name']) . "\r\n",
        'Телефон: ' . esc_html($_POST['phone']) . "\r\n"
    );
    $message = implode('', $message);

    $headers = array(
        'content-type: text/html',
    );

    wp_mail($to, $subject, $message, $headers);
}

function successful_message()
{
    $return = [
        'success' => true,
        'data' => [
            'form_title' => get_field('form_title', 'option'),
        ]
    ];
    wp_send_json($return);
}