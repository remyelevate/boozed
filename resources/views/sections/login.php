<?php

/**
 * Login section
 * Two columns: left = image with decorative shapes (optional), right = login form.
 * Form POSTs to wp-login.php; redirect_to from URL is passed so user returns after login.
 */

// If already logged in and redirect_to is set, send them there
if (is_user_logged_in()) {
	$to = isset($_GET['redirect_to']) ? trim((string) wp_unslash($_GET['redirect_to'])) : '';
	if ($to !== '') {
		$resolved = (wp_parse_url($to, PHP_URL_HOST) !== null) ? esc_url_raw($to) : home_url($to);
		if (wp_validate_redirect($resolved, home_url()) !== false) {
			wp_safe_redirect($resolved);
			exit;
		}
	}
	// Only send users with dashboard access to wp-admin; others go to home or WooCommerce My Account
	$user = wp_get_current_user();
	if ($user->exists() && $user->has_cap('edit_posts')) {
		wp_safe_redirect(admin_url());
	} elseif (function_exists('wc_get_page_permalink') && wc_get_page_permalink('myaccount')) {
		wp_safe_redirect(wc_get_page_permalink('myaccount'));
	} else {
		wp_safe_redirect(home_url());
	}
	exit;
}

$image_id = function_exists('get_sub_field') ? get_sub_field('login_image') : null;
$heading  = function_exists('get_sub_field') ? (string) get_sub_field('login_heading') : '';

$heading   = $heading ?: __('Inloggen', 'boozed');
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$has_image = $image_url !== '';

// redirect_to: from URL param (absolute or path e.g. /assortiment); form needs absolute URL
$redirect_to_param = isset($_GET['redirect_to']) ? trim((string) wp_unslash($_GET['redirect_to'])) : '';
if ($redirect_to_param !== '') {
	$redirect_to = (wp_parse_url($redirect_to_param, PHP_URL_HOST) !== null)
		? esc_url_raw($redirect_to_param)
		: home_url($redirect_to_param);
	if (wp_validate_redirect($redirect_to, home_url()) === false) {
		$redirect_to = home_url();
	}
} else {
	$redirect_to = home_url();
}

$login_post_url = site_url('wp-login.php', 'login_post');
?>
<section class="section-login bg-brand-white min-h-[70vh] flex flex-col">
	<div class="section-login__inner max-w-section mx-auto w-full px-4 md:px-section-x py-12 md:py-section-y flex-1">
		<div class="section-login__grid grid grid-cols-1 lg:grid-cols-2 gap-0 min-h-[500px]">
			<!-- Left: image + decorative panel (square ratio) -->
			<div class="section-login__left order-2 lg:order-1 relative overflow-hidden rounded-l-none lg:rounded-l-lg aspect-square min-h-[300px] lg:min-h-0 <?php echo $has_image ? 'bg-brand-nude/30' : 'bg-[#f5e6e0]'; ?> flex items-center justify-center">
				<?php if ($has_image) : ?>
					<div class="absolute inset-0 z-0">
						<img src="<?php echo esc_url($image_url); ?>" alt="" class="w-full h-full object-cover object-center" loading="lazy">
					</div>
					<!-- Decorative overlay shapes -->
					<div class="absolute inset-0 z-10 pointer-events-none section-login__shapes" aria-hidden="true">
						<span class="section-login__shape section-login__shape--red-1"></span>
						<span class="section-login__shape section-login__shape--red-2"></span>
						<span class="section-login__shape section-login__shape--blue"></span>
						<span class="section-login__shape section-login__shape--brown"></span>
					</div>
				<?php else : ?>
					<div class="section-login__shapes-standalone absolute inset-0 flex items-center justify-center opacity-80" aria-hidden="true">
						<span class="section-login__shape section-login__shape--red-1"></span>
						<span class="section-login__shape section-login__shape--red-2"></span>
						<span class="section-login__shape section-login__shape--blue"></span>
						<span class="section-login__shape section-login__shape--brown"></span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Right: form -->
			<div class="section-login__right order-1 lg:order-2 flex items-center justify-center lg:justify-start p-8 md:p-12 lg:pl-16 bg-brand-white rounded-r-none lg:rounded-r-lg">
				<div class="section-login__form-card w-full max-w-md">
					<h1 class="section-login__heading font-heading font-bold text-h3 md:text-h3-lg text-brand-indigo mt-0 mb-8"><?php echo esc_html($heading); ?></h1>

					<form name="loginform" class="section-login__form" action="<?php echo esc_url($login_post_url); ?>" method="post">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>">

						<p class="section-login__field mb-4">
							<label for="section-login-user" class="sr-only"><?php esc_html_e('Gebruikersnaam of e-mail', 'boozed'); ?></label>
							<input type="text" name="log" id="section-login-user" class="section-login__input w-full border border-brand-black/20 rounded-md font-body text-body-md text-brand-indigo px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-indigo/30 focus:border-brand-indigo" placeholder="<?php esc_attr_e('Gebruikersnaam of wachtwoord', 'boozed'); ?>" autocomplete="username" required>
						</p>
						<p class="section-login__field mb-6">
							<label for="section-login-pwd" class="sr-only"><?php esc_html_e('Wachtwoord', 'boozed'); ?></label>
							<input type="password" name="pwd" id="section-login-pwd" class="section-login__input w-full border border-brand-black/20 rounded-md font-body text-body-md text-brand-indigo px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-indigo/30 focus:border-brand-indigo" placeholder="<?php esc_attr_e('Wachtwoord', 'boozed'); ?>" autocomplete="current-password" required>
						</p>
						<p class="section-login__submit">
							<?php \App\Components::render('button', ['type' => 'submit', 'name' => 'wp-submit', 'label' => __('Inloggen', 'boozed'), 'icon_right_html' => '&rsaquo;', 'variant' => 'primary', 'class' => '!bg-brand-red w-full md:w-auto min-h-[48px]']); ?>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<style>
/* Decorative abstract shapes on login left panel */
.section-login__shapes .section-login__shape,
.section-login__shapes-standalone .section-login__shape { position: absolute; }
.section-login__shape--red-1 { width: 120px; height: 24px; background: #C41E3A; border-radius: 999px; top: 15%; left: 10%; transform: rotate(-15deg); opacity: 0.9; }
.section-login__shape--red-2 { width: 80px; height: 80px; border: 4px solid #C41E3A; border-radius: 50%; bottom: 25%; right: 15%; opacity: 0.7; }
.section-login__shape--blue { width: 100%; height: 3px; background: #0C0A21; top: 40%; left: 0; opacity: 0.4; transform: rotate(5deg); }
.section-login__shape--brown { width: 60px; height: 6px; background: #8B7355; bottom: 40%; left: 20%; transform: rotate(10deg); opacity: 0.6; }
</style>
