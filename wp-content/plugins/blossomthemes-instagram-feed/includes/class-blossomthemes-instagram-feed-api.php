<?php

class Blossomthemes_Instagram_Feed_API {
    /**
     * @var Blossomthemes_Instagram_Feed_API The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Instagram Access Token
     *
     * @var string
     */
    protected $access_token;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Blossomthemes_Instagram_Feed_API The *Singleton* instance.
     */
    public static function getInstance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function __construct() {
        $options = get_option( 'blossomthemes_instagram_feed_settings' );
        $token = isset ( $options['access-token'] ) ? $options['access-token'] : '';
        $this->access_token = $token;
    }

    /**
     * @param $screen_name string Instagram username
     * @param $image_limit int    Number of images to retrieve
     * @param $image_width int    Desired image width to retrieve
     *
     * @return array|bool Array of tweets or false if method fails
     */
    public function get_items( $image_limit, $photo_size ) {

        $transient = 'blossomthemes_instagram_is_configured';

        if ( false !== ( $data = get_transient( $transient ) ) && is_object( $data ) && ! empty( $data->data ) ) {

            return $this->processing_response_data( $data, $photo_size, $image_limit );
        }

        $api_image_limit = 30;
        $response        = wp_remote_get( sprintf( 'https://api.instagram.com/v1/users/self/media/recent/?access_token=%s&count=%s', $this->access_token, $api_image_limit ) );

        if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
            set_transient( $transient, false, MINUTE_IN_SECONDS );

            return false;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( ! empty( $data->data ) ) {
            set_transient( $transient, $data, 30 * MINUTE_IN_SECONDS );
        } else {
            set_transient( $transient, false, MINUTE_IN_SECONDS );

            return false;
        }

        return $this->processing_response_data( $data, $photo_size, $image_limit );
    }

    public function processing_response_data( $data, $photo_size, $image_limit ) {

        $result   = array();
        $username = '';

        $i = 0;
        foreach ( $data->data as $key => $item ) {

            if ( empty( $username ) ) {
                $username = $item->user->username;
            }

            if ( $i < $image_limit ) {

                $result[] = array(
                    'link'           => $item->link,
                    'image-standard' => $item->images->standard_resolution->url,
                    'image-url'      => $item->images->$photo_size->url,
                    'likes_count'    => ! empty( $item->likes->count ) ? esc_attr( $item->likes->count ) : 0,
                    'comments_count' => ! empty( $item->comments->count ) ? esc_attr( $item->comments->count ) : 0
                );
            }
            $i++;
        }

        $result = array( 'items' => $result, 'username' => $username );

        return $result;
    }

    /**
     * Check if given access token is valid for Instagram Api.
     */
    public static function is_access_token_valid( $access_token ) {
        $response = wp_remote_get( sprintf( 'https://api.instagram.com/v1/users/self/?access_token=%s', $access_token ) );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
            return false;
        }

        return true;
    }

    public function is_configured() {
        $transient = 'blossomthemes_instagram_is_configured';

        if ( false !== ( $result = get_transient( $transient ) ) ) {

            if ( 'yes' === $result ) {
                return true;
            }

            if ( 'no' === $result ) {
                return false;
            }
        }

        $condition = $this->is_access_token_valid( $this->access_token );

        if ( true === $condition ) {
            set_transient( $transient, 'yes', DAY_IN_SECONDS );

            return true;
        }

        set_transient( $transient, 'no', DAY_IN_SECONDS );

        return false;
    }

    public static function reset_cache() {
        delete_transient( 'blossomthemes_instagram_is_configured' );
    }

    public function get_access_token() {
        return $this->access_token;
    }

    public function set_access_token( $access_token ) {
        $this->access_token = $access_token;
    }

}
