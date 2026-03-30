<?php
/**
 * Template Name: Contact Page
 *
 * A contact page template with Cloudflare Turnstile
 */

get_header( 'top' );

$cf_site_key = get_option( 'dt_webform_cf_site_key', '' );
?>

<div class="page">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="container page-content">

            <h1 class="page-title"><?php echo esc_html__('Contact Us', 'doxa-website'); ?></h1>

            <form class="stack stack--md max-width-lg center" id="contact-form">
                <input type="hidden" name="action" value="contact_us">
                <input type="email" name="email" style="display:none;" autocomplete="off" tabindex="-1">
                <div class="">
                    <label for="name"><?php echo esc_html__('Name', 'doxa-website'); ?></label>
                    <input type="text" id="name" name="name" required placeholder="<?php echo esc_attr__('Enter your name', 'doxa-website'); ?>">
                </div>
                <div class="">
                    <label for="contact_email"><?php echo esc_html__('Email', 'doxa-website'); ?></label>
                    <input type="email" id="contact_email" name="contact_email" required placeholder="<?php echo esc_attr__('Enter your email', 'doxa-website'); ?>">
                </div>
                <div class="">
                    <label for="country"><?php echo esc_html__('Your Country (optional)', 'doxa-website'); ?></label>
                    <select id="country" name="country">
                        <option value=""><?php echo esc_html__('Select Country', 'doxa-website'); ?></option>
                        <?php foreach ( doxa_get_countries() as $country ) : ?>
                            <option value="<?php echo esc_attr( $country['value'] ); ?>"><?php echo esc_html( $country['label'] ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="">
                    <label for="message"><?php echo esc_html__('Message', 'doxa-website'); ?></label>
                    <textarea id="message" name="message" rows="5" required placeholder="<?php echo esc_attr__('Enter your message', 'doxa-website'); ?>"></textarea>
                </div>
                <div class="form-control color-primary-darker">
                    <input type="checkbox" id="consent-doxa-general" name="consent_doxa_general">
                    <label for="consent-doxa-general"><?php echo esc_html__('I would like to receive email updates from the DOXA partnership.', 'doxa-website'); ?></label>
                </div>
                <div class="cf-turnstile" data-sitekey="<?php echo esc_attr( $cf_site_key ); ?>" data-theme="light" data-callback="onTurnstileSuccess"></div>
                <div id="contact-message" class="contact-message" style="display: none;"></div>
                <button
                    type="submit"
                    class="button"
                    id="contact-submit"
                    disabled
                >
                    <?php echo esc_html__('Submit', 'doxa-website'); ?>
                </button>
            </form>

        </div>
    </main>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
    <script>
        let turnstileToken = '';

        function onTurnstileSuccess(token) {
            turnstileToken = token;
            document.getElementById('contact-submit').disabled = false;
        }

        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('contact-submit');
            const messageDiv = document.getElementById('contact-message');

            // Check honeypot
            const honeypot = form.querySelector('input[name="email"]');
            if (honeypot && honeypot.value) {
                return;
            }

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = '<?php echo esc_js(__('Submitting...', 'doxa-website')); ?>';

            const formData = {
                name: form.querySelector('#name').value,
                email: form.querySelector('#contact_email').value,
                country: form.querySelector('#country').value,
                message: form.querySelector('#message').value,
                consent_doxa_general: form.querySelector('#consent-doxa-general').checked,
                language: '<?php echo esc_js( doxa_get_language_code() ); ?>',
                cf_turnstile: turnstileToken
            };

            fetch('<?php echo esc_url( rest_url( 'doxa/v1/contact' ) ); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': '<?php echo wp_create_nonce( 'wp_rest' ); ?>'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                return response.json().then(data => ({ ok: response.ok, data }));
            })
            .then(({ ok, data }) => {
                if (ok && data.status === 'success') {
                    messageDiv.className = 'contact-message success';
                    messageDiv.textContent = '<?php echo esc_js(__('Thank you for your message. We will get back to you soon!', 'doxa-website')); ?>';
                    messageDiv.style.display = 'block';
                    form.reset();
                    turnstile.reset();
                    turnstileToken = '';
                    submitBtn.disabled = true;
                } else {
                    messageDiv.className = 'contact-message error';
                    messageDiv.textContent = data.message || '<?php echo esc_js(__('There was an error sending your message. Please try again.', 'doxa-website')); ?>';
                    messageDiv.style.display = 'block';
                    turnstile.reset();
                    turnstileToken = '';
                }
                submitBtn.textContent = '<?php echo esc_js(__('Submit', 'doxa-website')); ?>';
            })
            .catch(error => {
                messageDiv.className = 'contact-message error';
                messageDiv.textContent = '<?php echo esc_js(__('There was an error sending your message. Please try again.', 'doxa-website')); ?>';
                messageDiv.style.display = 'block';
                turnstile.reset();
                turnstileToken = '';
                submitBtn.textContent = '<?php echo esc_js(__('Submit', 'doxa-website')); ?>';
            });
        });
    </script>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>
