# Vacature sollicitatie – Contact Form 7 template

The theme can **auto-create** this form when Contact Form 7 is active (title **Boozed Vacature sollicitatie**) and stores the shortcode in the option `boozed_cf7_vacature_sollicitatie_shortcode`. The vacancy modal (`#solliciteren`) renders that shortcode on single vacature pages.

**Preferred:** in **Global Settings → Vacatures**, use the field **Sollicitatieformulier (shortcode)**. Whatever you paste there overrides the auto-generated shortcode.

If you delete the auto-generated form or use another title, paste the shortcode from **Contact → Contact Forms** into the option (e.g. via WP-CLI `wp option update boozed_cf7_vacature_sollicitatie_shortcode '[contact-form-7 id="…" …]'`) or recreate the form using the templates below.

---

## Form tab

Paste this in **Contact → Contact Forms → [your form] → Form**:

```
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
```

- **File size**: `limit:5242880` is 5 MB in bytes. If your CF7 version prefers it, try `limit:5mb` instead.
- Field names (`voornaam`, `email`, `sollicitatie-cv`, …) must match the **Mail** tab below.

---

## Mail tab

Suggested **Subject**:

```
[_site_title] — Nieuwe sollicitatie: [_post_title]
```

Suggested **Message body** (plain text):

```
Nieuwe sollicitatie via de website.

Vacature (pagina): [_post_title]
Pagina-URL: [_url]

Voornaam: [voornaam]
Achternaam: [achternaam]
E-mail: [email]
Telefoon: [telefoon]

Motivatie:
[motivatie]

Toestemming database: [consent]

--
Verzonden vanaf [_site_title] ([_site_url])
```

- **Recipient**: your HR inbox or `[_site_admin_email]`.
- **Additional headers**: `Reply-To: [email]`
- **File attachments**: leave empty; CF7 attaches uploaded files from `[file* …]` automatically.

`[_post_title]` and `[_url]` refer to the page where the form was embedded (the vacature detail page when the modal is shown).

---

## Messages tab (Dutch)

Examples:


| Message type           | Text                                                                                      |
| ---------------------- | ----------------------------------------------------------------------------------------- |
| Mail successfully sent | Bedankt! We hebben je sollicitatie ontvangen en nemen zo snel mogelijk contact met je op. |
| Mail sending failed    | Er is een fout opgetreden bij het verzenden. Probeer het later opnieuw.                   |
| Validation errors      | Een of meer velden zijn onjuist ingevuld. Controleer de gemarkeerde velden.               |
| This field is required | Dit veld is verplicht.                                                                    |


---

## Styling

The theme styles CF7 inside the modal under `.vacature-sollicitatie-modal__cf7` (see `resources/css/app.css`). Keep the wrapper classes `vacature-sollicitatie-cf7-field` on the `<div>` elements so file and checkbox rows match the layout.