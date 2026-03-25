<?php
/**
 * Template Name: UUPG Detail
 *
 * Custom template for the UUPG detail page - completely independent of posts/archive templates
 */

$slug = get_query_var( 'uupg_slug' );

$uupg = get_uupg_by_slug( $slug );

$lang_code = doxa_get_language_code();
if ( $lang_code !== 'en' ) {
    $pray_url = 'https://pray.doxa.life/' . $lang_code . '/' . $slug;
} else {
    $pray_url = 'https://pray.doxa.life/' . $slug;
}

?>


<?php if ( isset( $uupg['error'] ) ) : ?>
    <?php get_header( 'top' ); ?>
    <div class="page">
        <?php get_header(); ?>
        <main class="site-main">
            <div class="container page-content uupg-detail-page">
                <div class="stack stack--lg">
                    <h1><?php echo esc_html__('People Group Not Found', 'doxa-website'); ?></h1>
                    <p><?php echo esc_html__('The people group you are looking for could not be found. Please try again.', 'doxa-website'); ?></p>
                    <a class="button font-size-lg" href="<?php echo home_url('/research'); ?>">
                        <span class="sr-only"><?php echo esc_html__('Back', 'doxa-website'); ?></span>
                        <svg class="icon | rotate-270" viewBox="0 0 489.67 289.877">
                            <path d="M439.017,211.678L263.258,35.919c-3.9-3.9-8.635-6.454-13.63-7.665-9.539-2.376-20.051.161-27.509,7.619L46.361,211.632c-11.311,11.311-11.311,29.65,0,40.961h0c11.311,11.311,29.65,11.311,40.961,0L242.667,97.248l155.39,155.39c11.311,11.311,29.65,11.311,40.961,0h0c11.311-11.311,11.311-29.65,0-40.961Z"/>
                        </svg>
                        <?php echo esc_html__('Back', 'doxa-website'); ?>
                    </a>
                </div>
            </div>
        </main>
        <?php get_footer(); ?>
    </div>
    <?php get_footer( 'bottom' ); ?>
<?php endif; ?>

<?php get_header( 'top' ); ?>

<div class="page">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="uupg-detail-page">
            <div class="surface-brand-light py-xl color-secondary">
                <div class="container">
                    <h1 class="text-center"><?php echo esc_html__('Adoption Resources', 'doxa-website'); ?></h1>
                </div>
            </div>
            <div class="surface-white py-xl stack-spacing-none">
                <div class="container">
                    <div class="switcher">
                        <div class="center | grow-none">
                            <img class="uupg__image" data-size="medium" data-shape="portrait" src="<?php echo esc_attr( $uupg['image_url'] ); ?>" alt="<?php echo isset( $uupg['name'] ) ? esc_attr( $uupg['name'] ) : 'People Group Photo'; ?>">
                        </div>
                        <div class="stack stack--xs | uupg__header">
                            <h2 class="color-primary"><?php echo esc_html__('Your UUPG', 'doxa-website'); ?></h2>
                            <h3 class="h1 font-weight-medium"><?php echo esc_html( $uupg['display_name'] ); ?></h3>
                            <p class="font-weight-medium font-size-lg"><?php echo esc_html( $uupg['imb_isoalpha3']['label'] ); ?> (<?php echo esc_html( $uupg['imb_reg_of_people_1']['label'] ); ?>)</p>
                            <a href="<?php echo esc_url( doxa_translation_url('research/' ) . $slug ); ?>" class="button compact"><?php echo esc_html__('View full profile', 'doxa-website'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <section class="surface-white mt-2xl">
                <div class="container stack stack--xl">
                    <h2><?php echo esc_html__('Introduction', 'doxa-website'); ?></h2>
                    <h3 class="color-brand-lighter stack-spacing-3xl"><?php echo esc_html__('Welcome to the adoption Journey', 'doxa-website'); ?></h3>
                    <p class="max-width-lg"><?php echo esc_html__('Thank you for adopting this Unengaged, Unreached People Group. Your church is joining a global movement asking God to open the way for the gospel among people who currently have little or no access to it. This page provides resources to help your church stay informed, mobilize prayer, and explore how God may use your community to help bring the good news of Jesus to this people group.', 'doxa-website'); ?></p>

                    <h3 class="color-brand-lighter stack-spacing-3xl"><?php echo esc_html__('Your role as an adopting church', 'doxa-website'); ?></h3>
                    <ul>
                        <li><?php echo esc_html__('Pray regularly for this people group and for gospel breakthrough', 'doxa-website'); ?></li>
                        <li><?php echo esc_html__('Mobilize others in your church to join in prayer', 'doxa-website'); ?></li>
                        <li><?php echo esc_html__('Give to support gospel work among unreached peoples', 'doxa-website'); ?></li>
                        <li><?php echo esc_html__('Send or raise up workers who may go and serve among them', 'doxa-website'); ?></li>
                    </ul>

                    <h3 class="color-brand-lighter stack-spacing-3xl"><?php echo esc_html__('Resources available on this page', 'doxa-website'); ?></h3>
                    <ul>
                        <li><?php echo esc_html__('Printable prayer cards and people group images', 'doxa-website'); ?></li>
                        <li><?php echo esc_html__('Graphics, QR code and slides for sharing with your congregation', 'doxa-website'); ?></li>
                        <li><?php echo esc_html__('Additional tools to help your church stay engaged in prayer for this people group', 'doxa-website'); ?></li>
                    </ul>
                </div>
            </section>
            <section class="container stack stack--3xl">
                <h2 class="text-center"><?php echo esc_html__('Your Adoption Resources', 'doxa-website'); ?></h2>

                <?php

                    $s3_url = 'https://s3.doxa.life/';
                    $image_url = get_template_directory_uri() . '/assets/images/';
                    $adoption_resources = [
                        'adoption_certificate' => [
                            'title' => esc_html__('Adoption Certificate', 'doxa-website'),
                            'image_url' => $image_url . 'certificate.png',
                            'download_link' => $s3_url . "adoption-resources/certificate-$uupg_slug-$lang_code.pdf",
                        ],
                        'uupg_photo' => [
                            'title' => esc_html__('UUPG Photo', 'doxa-website'),
                            'image_url' => $image_url . 'profile.png',
                            'style' => 'width: 50%;',
                            'download_link' => $s3_url . "adoption-resources/uupg-photo-$uupg_slug.jpg",
                        ],
                        'prayer_campaign_qr_code' => [
                            'title' => esc_html__('Prayer Campaign QR Code', 'doxa-website'),
                            'image_url' => $image_url . 'qr.png',
                            'style' => 'width: 50%; padding-top: 5%; padding-bottom: 5%;',
                            'download_link' => $s3_url . "adoption-resources/qr-code-$uupg_slug-$lang_code.png",
                        ],
                        'prayer_card' => [
                            'title' => esc_html__('Printable Prayer Cards', 'doxa-website'),
                            'image_url' => $image_url . 'card.png',
                            'download_link' => $s3_url . "adoption-resources/prayer-card-$uupg_slug-$lang_code.pdf",
                        ],
                        'promo_slide' => [
                            'title' => esc_html__('Promo Slide', 'doxa-website'),
                            'image_url' => $image_url . 'slide.png',
                            'download_link' => $s3_url . "adoption-resources/promo-slide-$uupg_slug-$lang_code.jpg",
                        ],
                        'social_share' => [
                            'title' => esc_html__('Social Share', 'doxa-website'),
                            'image_url' => $image_url . 'social.png',
                            'style' => 'width: 40%;',
                            'download_link' => $s3_url . "adoption-resources/social-share-$uupg_slug-$lang_code.jpg",
                        ],
                    ];

                    ?>


                    <div class="grid" data-width-md>

                        <?php foreach ( $adoption_resources as $resource ) : ?>

                            <div class="card | resource-card | stack stack--xs | align-center rounded-md" padding-small>
                                <div class="resource-card__image" style="<?php echo isset( $resource['style'] ) ? esc_attr( $resource['style'] ) : ''; ?>"><img crossorigin="anonymous" src="<?php echo esc_attr( $resource['image_url'] ); ?>" alt="<?php echo esc_attr( $resource['title'] ); ?>"></div>
                                <h3 class="h4 text-center font-heading mb-auto"><?php echo esc_html( $resource['title'] ); ?></h3>
                                <div class="switcher | text-center gap-md" data-width="xs">
                                    <a target="_blank" href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact outline"><?php echo esc_html__('View', 'doxa-website'); ?></a>
                                    <a download href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact"><?php echo esc_html__('Download', 'doxa-website'); ?></a>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

            </section>

            <section class="surface-secondary-light">
                <div class="container stack stack--3xl">
                    <h2 class="text-center"><?php echo esc_html__('General Resources', 'doxa-website'); ?></h2>
                    <?php echo do_shortcode( '[general_resources layout="on-page"]' ); ?>
                </div>
            </section>
        </div>
    </main>


    <?php get_footer(); ?>

</div>

<script>
/* Also in general-resources-shortcode.php */
document.querySelectorAll('a[download]').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var url = this.href;
        var filename = url.split('/').pop().split('?')[0];
        var a;
        fetch(url)
            .then(function(response) {
                if (!response.ok) throw new Error('Download failed');
                return response.blob();
            })
            .then(function(blob) {
                a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = filename;
                document.body.appendChild(a);
                a.click();
            })
            .catch(function(error) {
                console.error('Download failed', error);
                window.open(url, '_blank');
            })
            .finally(function() {
                if (a) {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(a.href);
                }
            });
    });
});
</script>

<?php get_footer( 'bottom' ); ?>

