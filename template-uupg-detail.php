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
$pray_url .= '?source=doxalife';

?>


<?php if ( isset( $uupg['error'] ) ) : ?>
    <?php get_header( 'top' ); ?>
    <div class="page">
        <?php get_header(); ?>
        <main class="site-main">
            <div class="container page-content uupg-detail-page">
                <div class="stack stack--lg">
                    <h1><?php echo __('People Group Not Found', 'doxa-website'); ?></h1>
                    <p><?php echo __('The people group you are looking for could not be found. Please try again.', 'doxa-website'); ?></p>
                    <a class="button font-size-lg" href="<?php echo home_url('/research'); ?>">
                        <span class="sr-only"><?php echo __('Back', 'doxa-website'); ?></span>
                        <svg class="icon | rotate-270" viewBox="0 0 489.67 289.877">
                            <path d="M439.017,211.678L263.258,35.919c-3.9-3.9-8.635-6.454-13.63-7.665-9.539-2.376-20.051.161-27.509,7.619L46.361,211.632c-11.311,11.311-11.311,29.65,0,40.961h0c11.311,11.311,29.65,11.311,40.961,0L242.667,97.248l155.39,155.39c11.311,11.311,29.65,11.311,40.961,0h0c11.311-11.311,11.311-29.65,0-40.961Z"/>
                        </svg>
                        <?php echo __('Back', 'doxa-website'); ?>
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
        <div class="container page-content uupg-detail-page">
            <div class="stack stack--lg">
                <div class="stack stack--2xl">
                    <div class="card switcher" padding-small>
                        <div class="center | grow-none">
                            <div class="position-relative">
                                <img class="uupg__image" data-size="medium" src="<?php echo esc_attr( $uupg['image_url'] ); ?>" alt="<?php echo isset( $uupg['name'] ) ? esc_attr( $uupg['name'] ) : 'People Group Photo'; ?>">

                                <?php if ( isset( $uupg['picture_credit'] ) && is_array( $uupg['picture_credit'] ) ) : ?>

                                    <button class="info__button" id="info-button" type="button" aria-haspopup="dialog" aria-expanded="false" data-state="closed" aria-label="Photo credit">
                                        <span class="info__icon" aria-hidden="true"></span>
                                        <div class="info__content">
                                            <?php foreach ( $uupg['picture_credit'] as $credit ) : ?>
                                                <?php if ( !is_null( $credit['link'] ) ) : ?>
                                                    <a class="light-link" href="<?php echo esc_url( $credit['link'] ); ?>" target="_blank"><?php echo esc_html( $credit['text'] ); ?></a>
                                                <?php else : ?>
                                                    <span><?php echo esc_html( $credit['text'] ); ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </button>

                                    <script>
                                        document.getElementById('info-button').addEventListener('click', function() {
                                            this.setAttribute('data-state', this.getAttribute('data-state') === 'open' ? 'closed' : 'open');
                                            this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
                                        });
                                    </script>

                                <?php endif; ?>

                            </div>
                            <div class="engaged-stamp" data-engaged="<?php echo $uupg['engagement_status']['value'] === 'engaged' ? 'true' : 'false'; ?>">
                                <?php if ( $uupg['engagement_status']['value'] === 'engaged' ) : ?>
                                    <span><?php echo __('Engaged', 'doxa-website'); ?></span>
                                <?php else : ?>
                                    <span><?php echo __('Not Engaged', 'doxa-website'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="stack stack--xs | uupg__header">
                            <h4 class="font-base font-weight-medium"><?php echo esc_html( $uupg['name'] ); ?></h4>
                            <p class="font-weight-medium font-size-lg"><?php echo esc_html( $uupg['country_code']['label'] ); ?> (<?php echo esc_html( $uupg['rop1']['label'] ); ?>)</p>
                            <p><?php echo esc_html( $uupg['imb_people_description'] ); ?></p>

                            <style>
                                .resources-button {
                                    display: none;
                                }
                                [lang="en-US"] .resources-button {
                                    display: block;
                                }
                            </style>
                            <a href="./resources" class="button compact | resources-button"><?php echo esc_html__('View Adoption Resources', 'doxa-website'); ?></a>
                        </div>
                    </div>
                    <div class="card stack stack--2xs" id="engagement-status" padding-small>
                        <h2 class="text-center"><?php echo __('Engagement Status', 'doxa-website'); ?></h2>
                        <div class="cluster justify-center">
                            <div class="cluster justify-center align-start" data-width="md">
                                <div class="status-item">
                                    <?php if ( $uupg['people_committed'] > 0 ) : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                    <?php endif; ?>
                                    <p><?php echo __('Prayer Status', 'doxa-website'); ?></p>
                                </div>
                                <div class="status-item">
                                    <?php if ( isset( $uupg['adopted_by_churches'] ) && $uupg['adopted_by_churches'] > 0 ) : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                    <?php endif; ?>
                                    <p><?php echo __('Adoption Status', 'doxa-website'); ?></p>
                                </div>
                                <div class="status-item">
                                    <?php if ( !empty( $uupg['workers_long_term'] ) ) : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                    <?php endif; ?>
                                    <p><?php echo __('Cross-cultural workers present', 'doxa-website'); ?></p>
                                </div>
                                <div class="status-item">
                                    <?php if ( !empty( $uupg['work_in_local_language'] ) ) : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                    <?php endif; ?>
                                    <p><?php echo __('Work in local language &amp; culture', 'doxa-website'); ?></p>
                                </div>
                                <div class="status-item">
                                    <?php if ( !empty( $uupg['disciple_and_church_multiplication'] ) ) : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                    <?php endif; ?>
                                    <p><?php echo __('Disciple &amp; church multiplication', 'doxa-website'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="switcher" data-width="xl">
                        <div class="stack stack--xl | card | text-center lh-0" data-variant="secondary">
                            <h2><?php echo __('Prayer Status', 'doxa-website'); ?></h2>
                            <p class="font-size-4xl font-weight-medium"><?php echo $uupg['people_committed'] ?></p>
                            <p class="font-size-lg"><?php echo __('People committed to praying', 'doxa-website'); ?></p>
                            <div class="stack stack--sm">
                                <div class="progress-bar" data-size="md">
                                    <div class="progress-bar__slider" style="width: <?php echo esc_attr( $uupg['people_committed'] / 144 * 100 ); ?>%"></div>
                                </div>
                                <p class="font-size-lg font-weight-medium"><?php echo __('24-Hour Prayer Coverage', 'doxa-website'); ?></p>
                            </div>
                            <a class="button fit-content mx-auto stack-spacing-4xl clamp-padding" href="<?php echo esc_url( $pray_url ); ?>"><?php echo __('Sign up to pray', 'doxa-website'); ?></a>
                        </div>
                        <div class="stack stack--xl | card | text-center lh-0" data-variant="primary">
                            <h2><?php echo __('Adoption Status', 'doxa-website'); ?></h2>
                            <p class="font-size-4xl font-weight-medium"><?php echo $uupg['adopted_by_churches'] ? $uupg['adopted_by_churches'] : 0; ?></p>
                            <p class="font-size-lg margin-bottom-md"><?php echo __('churches / individuals have adopted this people group', 'doxa-website'); ?></p>
                            <?php if ( isset( $uupg['adopted_by_names'] ) && count( $uupg['adopted_by_names'] ) > 0 ) : ?>
                                <ul>
                                    <?php foreach ( $uupg['adopted_by_names'] as $name ) : ?>
                                        <li><?php echo esc_html( $name ); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <a class="button fit-content mx-auto mt-auto clamp-padding" href="<?php echo doxa_translation_url('adopt' ) . $uupg['slug']; ?>"><?php echo __('Adopt people group', 'doxa-website'); ?></a>
                        </div>
                    </div>

                    <?php if ( isset( $uupg['latitude'] ) && isset( $uupg['longitude'] )) : ?>

                        <div class="map-card">
                            <iframe
                                class="map"
                                src="https://www.openstreetmap.org/export/embed.html?bbox=<?php echo esc_html( (float) $uupg['longitude'] - 10 ) ?>,<?php echo esc_html( (float) $uupg['latitude'] - 10 ) ?>,<?php echo esc_html( (float) $uupg['longitude'] + 10 ) ?>,<?php echo esc_html( (float) $uupg['latitude'] + 10 ) ?>&layer=mapnik&marker=<?php echo esc_html( $uupg['latitude'] ) ?>,<?php echo esc_html( $uupg['longitude'] ) ?>"
                                loading="lazy"
                            ></iframe>
                            <div class="overlay"></div>
                        </div>

                    <?php endif; ?>

                    <div class="switcher" data-width="xl">
                        <div class="card" data-variant="primary">
                            <div class="stack">
                                <h2 class="color-primary"><?php echo __('Overview', 'doxa-website'); ?></h2>
                                <p><strong><?php echo __('Country', 'doxa-website'); ?>:</strong> <?php echo esc_html( $uupg['country_code']['label'] ); ?></p>

                                <?php if ( isset( $uupg['imb_alternate_name'] ) ) : ?>
                                    <p><strong><?php echo __('Alternate Names', 'doxa-website'); ?>:</strong> <?php echo esc_html( $uupg['imb_alternate_name'] ); ?></p>
                                <?php endif; ?>
                                <p><strong><?php echo __('Population', 'doxa-website'); ?>:</strong> ~<?php echo esc_html( $uupg['population'] ); ?></p>
                                <p><strong><?php echo __('Primary Language', 'doxa-website'); ?>:</strong> <?php echo esc_html( $uupg['primary_language']['label'] ); ?></p>
                                <p><strong><?php echo __('Primary Religion', 'doxa-website'); ?>:</strong> <?php echo esc_html( $uupg['religion']['label'] ); ?></p>
                                <p><strong><?php echo __('Religious Practices', 'doxa-website'); ?>:</strong> <br><?php echo esc_html( $uupg['religion']['description'] ); ?></p>
                            </div>
                        </div>
                        <div class="stack | card" data-variant="primary">
                            <h2 class="color-primary"><?php echo __('Progress', 'doxa-website'); ?></h2>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_bible_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Bible Translation', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_bible_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_bible_stories_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Bible Stories', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_bible_stories_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_jesus_film_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Jesus Film', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_jesus_film_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_radio_broadcast_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Radio Broadcasts', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_radio_broadcast_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_gospel_recordings_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Gospel Recordings', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_gospel_recordings_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                            <p class="progress-item">
                                <?php if ( $uupg['imb_audio_scripture_available'] ) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Check-GreenCircle.png" alt="<?php echo __('Done', 'doxa-website'); ?>">
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/RedX-Circle.png" alt="<?php echo __('Not Done', 'doxa-website'); ?>">
                                <?php endif; ?>
                                <strong><?php echo __('Audio Scripture', 'doxa-website'); ?>:</strong> <?php echo $uupg['imb_audio_scripture_available'] ? __('Yes', 'doxa-website') : __('No', 'doxa-website'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>

