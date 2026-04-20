<?php
/**
 * Login section.
 * Styled to match Request Account section layout.
 */

$image_id = function_exists('get_sub_field') ? get_sub_field('login_image') : null;
$heading  = function_exists('get_sub_field') ? (string) get_sub_field('login_heading') : '';
$intro    = function_exists('get_sub_field') ? (string) get_sub_field('login_intro') : '';
$forgot_page_link = function_exists('get_sub_field') ? (string) get_sub_field('login_forgot_password_page') : '';

$heading   = $heading ?: __('Inloggen', 'boozed');
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$has_image = $image_url !== '';

$redirect_to_param = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect_to'])) {
	$redirect_to_param = trim((string) wp_unslash($_POST['redirect_to']));
} elseif (isset($_GET['redirect_to'])) {
	$redirect_to_param = trim((string) wp_unslash($_GET['redirect_to']));
}

if ($redirect_to_param !== '') {
	$redirect_candidate = (wp_parse_url($redirect_to_param, PHP_URL_HOST) !== null)
		? esc_url_raw($redirect_to_param)
		: home_url($redirect_to_param);
	$redirect_to = wp_validate_redirect($redirect_candidate, home_url());
} else {
	$redirect_to = home_url();
}

$login_form_action = get_permalink() ? trailingslashit((string) get_permalink()) : boozed_login_page_url();
$lost_url = $forgot_page_link !== ''
	? $forgot_page_link
	: (function_exists('boozed_forgot_password_page_url') ? boozed_forgot_password_page_url() : trailingslashit(home_url('/wachtwoord-vergeten')));

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['boozed_login_nonce'])) {
	$nonce_ok = wp_verify_nonce(sanitize_text_field((string) wp_unslash($_POST['boozed_login_nonce'])), 'boozed_login');
	$username = isset($_POST['log']) ? sanitize_text_field((string) wp_unslash($_POST['log'])) : '';
	$password = isset($_POST['pwd']) ? (string) wp_unslash($_POST['pwd']) : '';
	$remember = !empty($_POST['rememberme']);

	if (!$nonce_ok) {
		$login_error = __('Er ging iets mis. Probeer opnieuw.', 'boozed');
	} else {
		$user = wp_signon([
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => $remember,
		], is_ssl());

		if (is_wp_error($user)) {
			$login_error = __('Onjuiste inloggegevens. Probeer het opnieuw.', 'boozed');
		} else {
			wp_set_current_user($user->ID);
			wp_safe_redirect($redirect_to);
			exit;
		}
	}
}
if (isset($_GET['password-reset']) && $_GET['password-reset'] === '1') {
	$intro = __('Je wachtwoord is bijgewerkt. Log in met je nieuwe wachtwoord.', 'boozed');
}
?>
<section class="section-login bg-brand-white">
	<div class="section-login__inner max-w-section mx-auto px-4 md:px-section-x py-12 md:py-section-y">
		<div class="section-login__grid grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
			<?php if ($has_image) : ?>
				<div class="section-login__left order-2 lg:order-1 relative overflow-hidden rounded-lg bg-brand-border" style="aspect-ratio: 1 / 1;">
					<img src="<?php echo esc_url($image_url); ?>" alt="" class="block w-full h-full object-cover" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="section-login__right order-1 lg:order-2 <?php echo $has_image ? '' : 'lg:col-span-2'; ?>">
				<div class="section-login__form-card w-full max-w-xl">
					<h1 class="section-login__heading font-heading font-bold text-h4 md:text-h3 text-brand-purple mt-0 mb-4"><?php echo esc_html($heading); ?></h1>
					<?php if ($intro !== '') : ?>
						<p class="font-body text-body text-brand-black mt-0 mb-6"><?php echo esc_html($intro); ?></p>
					<?php endif; ?>

					<?php if ($login_error !== '') : ?>
						<div class="mb-4 rounded border border-brand-coral/30 bg-brand-coral/10 px-4 py-3 text-body-sm text-brand-indigo">
							<?php echo esc_html($login_error); ?>
						</div>
					<?php endif; ?>

					<form name="loginform" class="section-login__form flex flex-col gap-3" action="<?php echo esc_url($login_form_action); ?>" method="post">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>">
						<input type="hidden" name="boozed_login_nonce" value="<?php echo esc_attr(wp_create_nonce('boozed_login')); ?>">
						<input type="text" name="log" class="w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 font-body text-body text-brand-indigo placeholder:text-brand-purple/75 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus" placeholder="<?php esc_attr_e('Gebruikersnaam of e-mailadres', 'boozed'); ?>" autocomplete="username" required>
						<input type="password" name="pwd" class="w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 font-body text-body text-brand-indigo placeholder:text-brand-purple/75 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus" placeholder="<?php esc_attr_e('Wachtwoord', 'boozed'); ?>" autocomplete="current-password" required>
						<label class="inline-flex items-center gap-2 font-body text-body-sm text-brand-indigo mt-1">
							<input type="checkbox" name="rememberme" value="forever" class="h-4 w-4 rounded border-brand-border text-brand-purple focus:ring-brand-border-focus">
							<?php esc_html_e('Onthoud mij', 'boozed'); ?>
						</label>
						<div class="pt-2">
							<?php \App\Components::render('button', ['type' => 'submit', 'name' => 'wp-submit', 'label' => __('Inloggen', 'boozed'), 'variant' => 'primary', 'class' => '!bg-brand-coral']); ?>
						</div>
						<a href="<?php echo esc_url($lost_url); ?>" class="mt-1 inline-block font-body text-body-sm text-brand-purple hover:underline"><?php esc_html_e('Wachtwoord vergeten?', 'boozed'); ?></a>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
