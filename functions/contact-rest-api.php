<?php
/**
 * Contact Form REST API Endpoint
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Contact Form REST API Endpoint
 */
function doxa_register_contact_rest_route() {
    register_rest_route( 'doxa/v1', '/contact', array(
        'methods'             => 'POST',
        'callback'            => 'doxa_handle_contact_form',
        'permission_callback' => '__return_true',
    ) );
}
add_action( 'rest_api_init', 'doxa_register_contact_rest_route' );

/**
 * Handle Contact Form Submission
 */
function doxa_handle_contact_form( WP_REST_Request $request ) {
    $params = $request->get_params();

    // Verify Cloudflare Turnstile token
    $cf_token = $params['cf_turnstile'] ?? '';
    if ( empty( $cf_token ) ) {
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }

    $secret_key = get_option( 'dt_webform_cf_secret_key', '' );
    if ( empty( $secret_key ) ) {
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }

    $response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'body' => [
            'secret'   => $secret_key,
            'response' => $cf_token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }

    $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $response_body['success'] ) ) {
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }

    // Sanitize inputs
    $name    = sanitize_text_field( $params['name'] ?? '' );
    $email   = sanitize_email( $params['email'] ?? '' );
    $country = sanitize_text_field( $params['country'] ?? '' );
    $message = sanitize_textarea_field( $params['message'] ?? '' );

    // Validate required fields
    if ( empty( $email ) || empty( $message ) ) {
        return new WP_Error( 'missing_fields', 'Email and message are required', [ 'status' => 400 ] );
    }

    // Send to Prayer Tools app
    $api_url = defined( 'DOXA_PRAYER_TOOLS_URL' ) ? DOXA_PRAYER_TOOLS_URL : '';
    $api_key = defined( 'DOXA_FORM_API_KEY' ) ? DOXA_FORM_API_KEY : '';

    if ( empty( $api_url ) || empty( $api_key ) ) {
        return new WP_Error( 'config_error', 'Prayer Tools integration not configured', [ 'status' => 500 ] );
    }

    $response = wp_remote_post( rtrim( $api_url, '/' ) . '/api/contact', [
        'body'    => wp_json_encode( [
            'name'    => $name,
            'email'   => $email,
            'country' => $country,
            'message' => $message,
        ] ),
        'headers' => [
            'X-API-Key'    => $api_key,
            'Content-Type' => 'application/json',
        ],
        'timeout' => 15,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'api_error', 'Failed to send message. Please try again.', [ 'status' => 500 ] );
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    if ( $response_code < 200 || $response_code >= 300 ) {
        return new WP_Error( 'api_error', 'Failed to send message. Please try again.', [ 'status' => 500 ] );
    }

    return new WP_REST_Response( [ 'status' => 'success' ], 200 );
}
