# Request Account section – CF7 form markup

Use this in **Contact Form 7** (Contact → Add new or edit the “Request Account” form). The theme hides labels and uses placeholders only; submit button is styled coral with “Account aanvragen >”.

---

## Form template

Paste this in the **Form** tab **exactly** (including the `<div>` wrappers) so Voornaam/Achternaam and Email/Telefoonnummer sit next to each other. Use **placeholders** only (labels are hidden in the section).

```
<div class="section-request-account__row section-request-account__row--half">
[text* voornaam placeholder "Voornaam"]
[text* achternaam placeholder "Achternaam"]
</div>
[text* bedrijfsnaam placeholder "Bedrijfsnaam"]
<div class="section-request-account__row section-request-account__row--half">
[email* email placeholder "Email adres"]
[tel telefoon placeholder "Telefoonnummer"]
</div>
[url website placeholder "website"]
[checkbox* privacy use_label_element "Ik ga akkoord met de voorwaarden van de Privacyverklaring *"]
[checkbox nieuwsbrief use_label_element "Ik wil graag via de nieuwsbrief op de hoogte blijven van Boozed."]
[submit "Account aanvragen >"]
```

- **voornaam**, **achternaam**: required; side by side (first row).
- **bedrijfsnaam**: required; full width.
- **email**, **telefoon**: side by side (second row).
- **website**: optional URL; full width.
- **privacy**: required checkbox. To add a link to your privacy page, add a line of text with a link above/below this checkbox in the form, or link in the Mail template.
- **nieuwsbrief**: optional newsletter checkbox.
- The two `<div class="section-request-account__row ...">` wrappers are required for the two-column layout; do not remove them.

---

## Redirect to thank-you page

1. **Create a thank-you page** in WordPress (e.g. slug `bedankt` or `thank-you`) and note its URL.

2. **Redirect after submit** – choose one:

   - **Contact Form 7 Redirection** (plugin): install “Redirection for Contact Form 7” (or similar), then set the redirect URL in the form’s settings.
   - **Additional settings (legacy):** in the form’s **Additional Settings** tab add (replace with your thank-you URL):
     ```
     on_sent_ok: "location = '/bedankt/';"
     ```
     Note: `on_sent_ok` is deprecated in newer CF7 versions; prefer a redirect plugin or the snippet below.

   - **Theme/plugin snippet** (recommended): redirect via the `wpcf7_redirect_url` filter (or your redirect plugin’s API). Example for this form only:
     ```php
     add_filter('wpcf7_redirect_url', function ($url, $status, $form) {
         // Use your form title or ID, e.g. "Request Account" or form id 123
         if ($form && (strpos($form->title(), 'Request Account') !== false)) {
             return home_url('/bedankt/');
         }
         return $url;
     }, 10, 3);
     ```

3. In the **Request Account** section (ACF), paste the form shortcode (e.g. `[contact-form-7 id="123" title="Request Account"]`) and set the left column image and heading as needed.
