<?php

namespace App;

/**
 * Provisions a Contact Form 7 “vacature sollicitatie” form when CF7 is active (same idea as {@see ContactForm7Newsletter}).
 */
class ContactForm7VacatureSollicitatie
{
    public const OPTION_SHORTCODE = 'boozed_cf7_vacature_sollicitatie_shortcode';

    public const FORM_TITLE = 'Boozed Vacature sollicitatie';

    public static function init(): void
    {
        add_action('wpcf7_init', [__CLASS__, 'maybe_create_form'], 21);
    }

    public static function maybe_create_form(): void
    {
        if (!class_exists('WPCF7_ContactForm')) {
            return;
        }

        $existing = self::get_form();
        if ($existing) {
            self::maybe_update_shortcode_option($existing);
            return;
        }

        $form = \WPCF7_ContactForm::get_template([
            'title'  => self::FORM_TITLE,
            'locale' => determine_locale(),
        ]);

        $form_markup = <<<'CF7'
<div class="vacature-sollicitatie-cf7-field">
<label> Voornaam *
[text* voornaam autocomplete:given-name]</label>
</div>
<div class="vacature-sollicitatie-cf7-field">
<label> Achternaam *
[text* achternaam autocomplete:family-name]</label>
</div>
<div class="vacature-sollicitatie-cf7-field">
<label> E-mailadres *
[email* email autocomplete:email]</label>
</div>
<div class="vacature-sollicitatie-cf7-field">
<label> Telefoonnummer *
[tel* telefoon autocomplete:tel]</label>
</div>
<div class="vacature-sollicitatie-cf7-field vacature-sollicitatie-cf7-field--file">
<label> CV * (PDF of Word, max. 5 MB)
[file* sollicitatie-cv filetypes:pdf|doc|docx limit:5242880]</label>
</div>
<div class="vacature-sollicitatie-cf7-field">
<label> Wat is je motivatie om te solliciteren voor deze functie? *
[textarea* motivatie rows:5]</label>
</div>
<div class="vacature-sollicitatie-cf7-field vacature-sollicitatie-cf7-field--checkbox">
[checkbox* consent use_label_element "Ik ga akkoord dat deze gegevens worden opgeslagen in de database."]
</div>
[submit "Verstuur mijn sollicitatie!"]
CF7;

        $mail_body = implode("\n", [
            'Nieuwe sollicitatie via de website.',
            '',
            'Vacature (pagina): [_post_title]',
            'Pagina-URL: [_url]',
            '',
            'Voornaam: [voornaam]',
            'Achternaam: [achternaam]',
            'E-mail: [email]',
            'Telefoon: [telefoon]',
            '',
            'Motivatie:',
            '[motivatie]',
            '',
            'Toestemming database: [consent]',
            '',
            '--',
            'Verzonden vanaf [_site_title] ([_site_url])',
        ]);

        $form->set_properties([
            'form' => $form_markup,
            'mail' => [
                'subject'            => '[_site_title] — Nieuwe sollicitatie: [_post_title]',
                'sender'             => sprintf('%s <%s>', '[_site_title]', \WPCF7_ContactFormTemplate::from_email()),
                'body'               => $mail_body,
                'recipient'          => '[_site_admin_email]',
                'additional_headers' => 'Reply-To: [email]',
                'attachments'        => '[sollicitatie-cv]',
                'use_html'           => 0,
                'exclude_blank'      => 1,
            ],
            'messages' => [
                'mail_sent_ok'    => __('Bedankt! We hebben je sollicitatie ontvangen en nemen zo snel mogelijk contact met je op.', 'boozed'),
                'mail_sent_ng'    => __('Er is een fout opgetreden bij het verzenden. Probeer het later opnieuw.', 'boozed'),
                'validation_error' => __('Een of meer velden zijn onjuist ingevuld. Controleer de gemarkeerde velden.', 'boozed'),
                'spam'            => __('Er is een probleem opgetreden bij het verzenden.', 'boozed'),
                'accept_terms'    => __('Je moet akkoord gaan met het opslaan van je gegevens.', 'boozed'),
                'invalid_required' => __('Dit veld is verplicht.', 'boozed'),
                'invalid_too_long' => __('Dit veld is te lang.', 'boozed'),
                'invalid_too_short' => __('Dit veld is te kort.', 'boozed'),
            ],
        ]);

        $id = $form->save();
        if ($id) {
            $saved = \wpcf7_contact_form($id);
            if ($saved) {
                update_option(self::OPTION_SHORTCODE, $saved->shortcode(), false);
            }
        }
    }

    /**
     * @return \WPCF7_ContactForm|null
     */
    private static function get_form()
    {
        $forms = \WPCF7_ContactForm::find(['title' => self::FORM_TITLE]);
        return !empty($forms) ? $forms[0] : null;
    }

    private static function maybe_update_shortcode_option(\WPCF7_ContactForm $form): void
    {
        $stored = get_option(self::OPTION_SHORTCODE, '');
        $current = $form->shortcode();
        if ($stored !== $current) {
            update_option(self::OPTION_SHORTCODE, $current, false);
        }

        self::maybe_fix_mail_attachments($form);
    }

    private static function maybe_fix_mail_attachments(\WPCF7_ContactForm $form): void
    {
        $props = $form->get_properties();
        $mail = $props['mail'] ?? [];
        $attachments = $mail['attachments'] ?? '';
        if (strpos($attachments, '[sollicitatie-cv]') !== false) {
            return;
        }
        $mail['attachments'] = '[sollicitatie-cv]';
        $form->set_properties(['mail' => $mail]);
        $form->save();
    }

    public static function get_shortcode(): string
    {
        return (string) get_option(self::OPTION_SHORTCODE, '');
    }
}
