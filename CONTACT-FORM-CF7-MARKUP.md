# Contact section – CF7 form markup

Use **placeholders** (no labels on top) and include the **consent** checkbox. In Contact Form 7, edit your form and use markup like this:

```
[text* your-name placeholder "Je naam"]
[email* your-email placeholder "Je email"]
[tel your-phone placeholder "Je telefoonnummer"]
[textarea your-message placeholder "Waarmee kunnen we je helpen?"]
[checkbox consent use_label_element "Ik ga akkoord dat deze gegevens worden opgeslagen."]
[submit "Bericht versturen"]
```

- Use `placeholder "..."` on each field so the theme can show placeholders only (labels are hidden in the contact section).
- The consent line adds the required checkbox with the text: *Ik ga akkoord dat deze gegevens worden opgeslagen.*
- Adjust field names (e.g. `your-name`, `your-email`) if your mail template uses different names.
