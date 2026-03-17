<?php

namespace App;

/**
 * Handles AJAX submission of the offerte-aanvraag (quote request) form.
 * Validates, sanitizes, handles file uploads, and sends email via wp_mail.
 */
class OfferteAanvraagHandler
{
    public const NONCE_ACTION = 'boozed_offerte_aanvraag';
    public const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB per file
    public const MAX_FILES = 5;
    public const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];

    public static function init(): void
    {
        add_action('wp_ajax_boozed_offerte_aanvraag_submit', [__CLASS__, 'handleSubmit']);
        add_action('wp_ajax_nopriv_boozed_offerte_aanvraag_submit', [__CLASS__, 'handleSubmit']);
    }

    public static function handleSubmit(): void
    {
        if (!check_ajax_referer(self::NONCE_ACTION, 'nonce', false)) {
            wp_send_json_error(['message' => __('Sessie verlopen. Vernieuw de pagina en probeer opnieuw.', 'boozed')]);
        }

        $data = [
            'voornaam'    => isset($_POST['voornaam']) ? sanitize_text_field(wp_unslash($_POST['voornaam'])) : '',
            'achternaam'  => isset($_POST['achternaam']) ? sanitize_text_field(wp_unslash($_POST['achternaam'])) : '',
            'bedrijfsnaam' => isset($_POST['bedrijfsnaam']) ? sanitize_text_field(wp_unslash($_POST['bedrijfsnaam'])) : '',
            'email'       => isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '',
            'telefoon'    => isset($_POST['telefoon']) ? sanitize_text_field(wp_unslash($_POST['telefoon'])) : '',
            'adres'       => isset($_POST['adres']) ? sanitize_text_field(wp_unslash($_POST['adres'])) : '',
            'locatie'     => isset($_POST['locatie']) ? sanitize_text_field(wp_unslash($_POST['locatie'])) : '',
            'datum_nodig' => isset($_POST['datum_nodig']) ? sanitize_text_field(wp_unslash($_POST['datum_nodig'])) : '',
            'opbouw'      => isset($_POST['opbouw']) ? sanitize_text_field(wp_unslash($_POST['opbouw'])) : '',
            'afbouw'      => isset($_POST['afbouw']) ? sanitize_text_field(wp_unslash($_POST['afbouw'])) : '',
            'experience'  => isset($_POST['experience']) ? sanitize_textarea_field(wp_unslash($_POST['experience'])) : '',
        ];

        if ($data['voornaam'] === '' || $data['achternaam'] === '' || $data['email'] === '') {
            wp_send_json_error(['message' => __('Vul verplichte velden in (voornaam, achternaam, e-mail).', 'boozed')]);
        }

        if (!is_email($data['email'])) {
            wp_send_json_error(['message' => __('Ongeldig e-mailadres.', 'boozed')]);
        }

        $attachments = [];
        if (!empty($_FILES['offerte_files']) && is_array($_FILES['offerte_files']['name'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            $names = $_FILES['offerte_files']['name'];
            $count = 0;
            foreach ($names as $i => $name) {
                if ($count >= self::MAX_FILES) {
                    break;
                }
                if (empty($name)) {
                    continue;
                }
                $file = [
                    'name'     => $names[$i],
                    'type'     => $_FILES['offerte_files']['type'][$i],
                    'tmp_name' => $_FILES['offerte_files']['tmp_name'][$i],
                    'error'    => $_FILES['offerte_files']['error'][$i],
                    'size'     => $_FILES['offerte_files']['size'][$i],
                ];
                if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > self::MAX_FILE_SIZE) {
                    continue;
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : $file['type'];
                if ($finfo) {
                    finfo_close($finfo);
                }
                if (!in_array($mime, self::ALLOWED_TYPES, true)) {
                    continue;
                }
                $move = wp_handle_upload($file, ['test_form' => false]);
                if (!empty($move['file'])) {
                    $attachments[] = $move['file'];
                    $count++;
                }
            }
        }

        $to = isset($_POST['recipient_email']) && is_email(wp_unslash($_POST['recipient_email']))
            ? sanitize_email(wp_unslash($_POST['recipient_email']))
            : get_option('admin_email');
        $subject = sprintf(
            '[%s] Offerte aanvraag van %s %s',
            get_bloginfo('name'),
            $data['voornaam'],
            $data['achternaam']
        );

        $body = self::buildEmailBody($data);
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        $sent = wp_mail($to, $subject, $body, $headers, $attachments);

        // Clean up temp uploads after send
        foreach ($attachments as $path) {
            if (is_file($path)) {
                wp_delete_file($path);
            }
        }

        if (!$sent) {
            wp_send_json_error(['message' => __('E-mail kon niet worden verzonden. Probeer het later opnieuw.', 'boozed')]);
        }

        wp_send_json_success();
    }

    private static function buildEmailBody(array $data): string
    {
        $rows = [
            'Voornaam'      => $data['voornaam'],
            'Achternaam'    => $data['achternaam'],
            'Bedrijfsnaam'  => $data['bedrijfsnaam'],
            'E-mail'        => $data['email'],
            'Telefoon'      => $data['telefoon'],
            'Adres'         => $data['adres'],
            'Locatie'       => $data['locatie'],
            'Datum nodig'   => $data['datum_nodig'],
            'Opbouw'        => $data['opbouw'],
            'Afbouw'        => $data['afbouw'],
            'Experience'     => nl2br(esc_html($data['experience'])),
        ];

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="font-family:sans-serif;line-height:1.5;">';
        $html .= '<h2>Offerte aanvraag</h2><table style="border-collapse:collapse;">';
        foreach ($rows as $label => $value) {
            if ((string) $value === '') {
                continue;
            }
            $html .= '<tr><td style="padding:6px 12px 6px 0;vertical-align:top;font-weight:bold;">' . esc_html($label) . '</td>';
            $html .= '<td style="padding:6px 0;">' . $value . '</td></tr>';
        }
        $html .= '</table></body></html>';
        return $html;
    }
}
