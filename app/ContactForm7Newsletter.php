<?php

namespace App;

/**
 * Creates and registers the Boozed newsletter Contact Form 7 form when CF7 is active.
 * Stores the shortcode in option 'boozed_cf7_newsletter_shortcode' for use in the footer.
 */
class ContactForm7Newsletter {

    const OPTION_SHORTCODE = 'boozed_cf7_newsletter_shortcode';
    const FORM_TITLE       = 'Boozed Newsletter';

    public static function init() {
        add_action('wpcf7_init', [ __CLASS__, 'maybe_create_form' ], 20);
        add_filter('acf/load_value/name=footer_newsletter_shortcode', [ __CLASS__, 'default_shortcode_if_empty' ], 10, 3);
    }

    /**
     * Create the newsletter form if it doesn't exist.
     */
    public static function maybe_create_form() {
        if ( ! class_exists('WPCF7_ContactForm') ) {
            return;
        }

        $existing = self::get_newsletter_form();
        if ( $existing ) {
            self::maybe_update_shortcode_option( $existing );
            return;
        }

        $form = \WPCF7_ContactForm::get_template([
            'title'  => self::FORM_TITLE,
            'locale' => determine_locale(),
        ]);

        $form_template = '[email* newsletter-email placeholder "Jouw email"]' . "\n" . '[submit "→"]';
        $form->set_properties([
            'form' => $form_template,
            'mail' => [
                'subject'            => sprintf(
                    /* translators: %s: site name */
                    _x( 'Newsletter signup – %s', 'newsletter mail subject', 'boozed' ),
                    '[_site_title]'
                ),
                'sender'             => sprintf( '%s <%s>', '[_site_title]', \WPCF7_ContactFormTemplate::from_email() ),
                'body'               => _x( 'New newsletter signup:', 'newsletter mail body', 'boozed' ) . "\n" . '[newsletter-email]' . "\n\n" . '--' . "\n" . sprintf( _x( 'Sent from %s', 'newsletter mail footer', 'boozed' ), '[_site_url]' ),
                'recipient'          => '[_site_admin_email]',
                'additional_headers' => 'Reply-To: [newsletter-email]',
                'attachments'        => '',
                'use_html'            => 0,
                'exclude_blank'       => 0,
            ],
        ]);

        $id = $form->save();
        if ( $id ) {
            $form = \wpcf7_contact_form( $id );
            if ( $form ) {
                update_option( self::OPTION_SHORTCODE, $form->shortcode(), false );
            }
        }
    }

    /**
     * Return existing newsletter form by title if it exists.
     *
     * @return \WPCF7_ContactForm|null
     */
    private static function get_newsletter_form() {
        $forms = \WPCF7_ContactForm::find([ 'title' => self::FORM_TITLE ]);
        return ! empty( $forms ) ? $forms[0] : null;
    }

    /**
     * Keep the shortcode option in sync when form already exists (e.g. after plugin reinstall).
     */
    private static function maybe_update_shortcode_option( \WPCF7_ContactForm $form ) {
        $stored = get_option( self::OPTION_SHORTCODE, '' );
        $current = $form->shortcode();
        if ( $stored !== $current ) {
            update_option( self::OPTION_SHORTCODE, $current, false );
        }
    }

    /**
     * When ACF footer_newsletter_shortcode is empty, provide the CF7 newsletter shortcode as default.
     *
     * @param mixed $value
     * @param int   $post_id
     * @param array $field
     * @return mixed
     */
    public static function default_shortcode_if_empty( $value, $post_id, $field ) {
        if ( $value !== null && $value !== '' ) {
            return $value;
        }
        return get_option( self::OPTION_SHORTCODE, '' );
    }
}
