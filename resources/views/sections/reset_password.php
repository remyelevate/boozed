<?php
/**
 * Reset password section.
 */

$image_id = function_exists('get_sub_field') ? get_sub_field('reset_password_image') : null;
$heading  = function_exists('get_sub_field') ? (string) get_sub_field('reset_password_heading') : '';
$intro    = function_exists('get_sub_field') ? (string) get_sub_field('reset_password_intro') : '';
$login_page_link = function_exists('get_sub_field') ? (string) get_sub_field('reset_password_login_page') : '';
$forgot_page_link = function_exists('get_sub_field') ? (string) get_sub_field('reset_password_forgot_page') : '';

$heading   = $heading ?: __('Wachtwoord resetten', 'boozed');
$intro     = $intro ?: __('Kies een nieuw wachtwoord voor je account.', 'boozed');
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
$has_image = $image_url !== '';
$reset_base_url = get_permalink() ? trailingslashit((string) get_permalink()) : (function_exists('boozed_reset_password_page_url') ? boozed_reset_password_page_url() : trailingslashit(home_url('/wachtwoord-resetten')));
$login_url = $login_page_link !== ''
	? $login_page_link
	: (function_exists('boozed_login_page_url') ? boozed_login_page_url() : trailingslashit(home_url('/login')));
$forgot_url = $forgot_page_link !== ''
	? $forgot_page_link
	: (function_exists('boozed_forgot_password_page_url') ? boozed_forgot_password_page_url() : trailingslashit(home_url('/wachtwoord-vergeten')));

$login = isset($_REQUEST['login']) ? sanitize_text_field((string) wp_unslash($_REQUEST['login'])) : '';
$key   = isset($_REQUEST['key']) ? sanitize_text_field((string) wp_unslash($_REQUEST['key'])) : '';

$error_message = '';
$user          = null;
if ($login === '' || $key === '') {
	$error_message = __('De resetlink is ongeldig of onvolledig. Vraag een nieuwe resetlink aan.', 'boozed');
} else {
	$user = check_password_reset_key($key, $login);
	if (is_wp_error($user)) {
		$error_message = __('Deze resetlink is verlopen of ongeldig. Vraag een nieuwe resetlink aan.', 'boozed');
		$user = null;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user instanceof \WP_User) {
	$nonce_ok = isset($_POST['boozed_reset_nonce']) && wp_verify_nonce(sanitize_text_field((string) wp_unslash($_POST['boozed_reset_nonce'])), 'boozed_reset_password');
	$pass1 = isset($_POST['pass1']) ? (string) wp_unslash($_POST['pass1']) : '';
	$pass2 = isset($_POST['pass2']) ? (string) wp_unslash($_POST['pass2']) : '';

	if (!$nonce_ok) {
		$error_message = __('Er ging iets mis. Probeer opnieuw.', 'boozed');
	} elseif ($pass1 === '' || $pass2 === '') {
		$error_message = __('Vul beide wachtwoordvelden in.', 'boozed');
	} elseif ($pass1 !== $pass2) {
		$error_message = __('De wachtwoorden komen niet overeen.', 'boozed');
	} else {
		reset_password($user, $pass1);
		wp_safe_redirect(add_query_arg('password-reset', '1', $login_url));
		exit;
	}
}
?>
<section class="section-reset-password bg-brand-white">
	<div class="section-reset-password__inner max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<div class="section-reset-password__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
			<?php if ($has_image) : ?>
				<div class="section-reset-password__left order-2 lg:order-1 relative overflow-hidden rounded-lg bg-brand-border" style="aspect-ratio: 1 / 1;">
					<img src="<?php echo esc_url($image_url); ?>" alt="" class="block w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="section-reset-password__right order-1 lg:order-2 <?php echo $has_image ? '' : 'lg:col-span-2'; ?>">
				<div class="section-reset-password__form-card w-full max-w-xl">
					<h1 class="font-heading font-bold text-h4 md:text-h3 text-brand-purple mt-0 mb-4"><?php echo esc_html($heading); ?></h1>
					<p class="font-body text-body text-brand-black mt-0 mb-6"><?php echo esc_html($intro); ?></p>

					<?php if ($error_message !== '') : ?>
						<div class="mb-4 rounded border border-brand-coral/30 bg-brand-coral/10 px-4 py-3 text-body-sm text-brand-indigo">
							<?php echo esc_html($error_message); ?>
						</div>
						<a href="<?php echo esc_url($forgot_url); ?>" class="inline-block font-body text-body-sm text-brand-purple hover:underline">
							<?php esc_html_e('Nieuwe resetlink aanvragen', 'boozed'); ?>
						</a>
					<?php elseif ($user instanceof \WP_User) : ?>
						<form class="flex flex-col gap-3" action="<?php echo esc_url(add_query_arg(['key' => $key, 'login' => $login], $reset_base_url)); ?>" method="post">
							<input type="hidden" name="boozed_reset_nonce" value="<?php echo esc_attr(wp_create_nonce('boozed_reset_password')); ?>">
							<input type="password" name="pass1" class="w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 font-body text-body text-brand-indigo placeholder:text-brand-purple/75 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus" placeholder="<?php esc_attr_e('Nieuw wachtwoord', 'boozed'); ?>" autocomplete="new-password" required>
							<input type="password" name="pass2" class="w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 font-body text-body text-brand-indigo placeholder:text-brand-purple/75 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus" placeholder="<?php esc_attr_e('Herhaal nieuw wachtwoord', 'boozed'); ?>" autocomplete="new-password" required>
							<div class="pt-2">
								<?php \App\Components::render('button', ['type' => 'submit', 'name' => 'wp-submit', 'label' => __('Wachtwoord opslaan', 'boozed'), 'variant' => 'primary', 'class' => '!bg-brand-coral']); ?>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
