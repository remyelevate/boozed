<?php
/**
 * Forgot password section.
 */

$image_id = function_exists('get_sub_field') ? get_sub_field('forgot_password_image') : null;
$heading  = function_exists('get_sub_field') ? (string) get_sub_field('forgot_password_heading') : '';
$intro    = function_exists('get_sub_field') ? (string) get_sub_field('forgot_password_intro') : '';
$login_page_link = function_exists('get_sub_field') ? (string) get_sub_field('forgot_password_login_page') : '';
$reset_page_link = function_exists('get_sub_field') ? (string) get_sub_field('forgot_password_reset_page') : '';

$heading   = $heading ?: __('Wachtwoord vergeten', 'boozed');
$intro     = $intro ?: __('Vul je gebruikersnaam of e-mailadres in. We sturen je een link om je wachtwoord te resetten.', 'boozed');
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
$has_image = $image_url !== '';
$forgot_form_action = get_permalink() ? trailingslashit((string) get_permalink()) : (function_exists('boozed_forgot_password_page_url') ? boozed_forgot_password_page_url() : trailingslashit(home_url('/wachtwoord-vergeten')));
$login_url = $login_page_link !== ''
	? $login_page_link
	: (function_exists('boozed_login_page_url') ? boozed_login_page_url() : trailingslashit(home_url('/inloggen')));
$reset_url = $reset_page_link !== ''
	? $reset_page_link
	: (function_exists('boozed_reset_password_page_url') ? boozed_reset_password_page_url() : trailingslashit(home_url('/wachtwoord-resetten')));

$message = '';
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['boozed_forgot_nonce'])) {
	$nonce_ok = wp_verify_nonce(sanitize_text_field((string) wp_unslash($_POST['boozed_forgot_nonce'])), 'boozed_forgot_password');
	$user_login = isset($_POST['user_login']) ? sanitize_text_field((string) wp_unslash($_POST['user_login'])) : '';

	if (!$nonce_ok) {
		$error_message = __('Er ging iets mis. Probeer opnieuw.', 'boozed');
	} elseif ($user_login === '') {
		$error_message = __('Vul je gebruikersnaam of e-mailadres in.', 'boozed');
	} else {
		$result = retrieve_password($user_login);
		if (is_wp_error($result)) {
			$error_message = __('We konden geen account vinden met deze gegevens.', 'boozed');
		} else {
			$message = __('Als je account bestaat, heb je een e-mail ontvangen met instructies om je wachtwoord te resetten.', 'boozed');
		}
	}
}
?>
<section class="section-forgot-password bg-brand-white">
	<div class="section-forgot-password__inner max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<div class="section-forgot-password__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
			<?php if ($has_image) : ?>
				<div class="section-forgot-password__left order-2 lg:order-1 relative overflow-hidden rounded-lg bg-brand-border" style="aspect-ratio: 1 / 1;">
					<img src="<?php echo esc_url($image_url); ?>" alt="" class="block w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="section-forgot-password__right order-1 lg:order-2 <?php echo $has_image ? '' : 'lg:col-span-2'; ?>">
				<div class="section-forgot-password__form-card w-full max-w-xl">
					<h1 class="font-heading font-bold text-h4 md:text-h3 text-brand-purple mt-0 mb-4"><?php echo esc_html($heading); ?></h1>
					<p class="font-body text-body text-brand-black mt-0 mb-6"><?php echo esc_html($intro); ?></p>

					<?php if ($message !== '') : ?>
						<div class="mb-4 rounded border border-brand-purple/30 bg-brand-purple/10 px-4 py-3 text-body-sm text-brand-indigo">
							<?php echo esc_html($message); ?>
						</div>
					<?php endif; ?>
					<?php if ($error_message !== '') : ?>
						<div class="mb-4 rounded border border-brand-coral/30 bg-brand-coral/10 px-4 py-3 text-body-sm text-brand-indigo">
							<?php echo esc_html($error_message); ?>
						</div>
					<?php endif; ?>

					<form class="flex flex-col gap-3" action="<?php echo esc_url($forgot_form_action); ?>" method="post">
						<input type="hidden" name="boozed_forgot_nonce" value="<?php echo esc_attr(wp_create_nonce('boozed_forgot_password')); ?>">
						<input type="hidden" name="boozed_reset_target" value="<?php echo esc_attr($reset_url); ?>">
						<input type="text" name="user_login" class="w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 font-body text-body text-brand-indigo placeholder:text-brand-purple/75 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus" placeholder="<?php esc_attr_e('Gebruikersnaam of e-mailadres', 'boozed'); ?>" required>
						<div class="pt-2">
							<?php \App\Components::render('button', ['type' => 'submit', 'name' => 'wp-submit', 'label' => __('Stuur resetlink', 'boozed'), 'variant' => 'primary', 'class' => '!bg-brand-coral']); ?>
						</div>
					</form>

					<a href="<?php echo esc_url($login_url); ?>" class="mt-4 inline-block font-body text-body-sm text-brand-purple hover:underline">
						<?php esc_html_e('Terug naar inloggen', 'boozed'); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
