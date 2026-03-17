<?php
/**
 * The front page template file
 */

get_header( 'top' ); ?>

<div class="page">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="front-page">
            <section class="stack stack--md container">
                <div>
                    <h2 class="color-brand"><?php echo __('Our gift to Jesus', 'doxa-website'); ?>:</h2>
                    <h1 class="color-brand-light highlight" data-highlight-index="1" data-highlight-last data-highlight-color="primary"><?php echo __('Engage every people by 2033', 'doxa-website'); ?></h1>
                </div>
                <div class="video-modal-button">
                    <svg class="icon">
                        <use href="<?php echo get_template_directory_uri(); ?>/assets/icons/play-button.svg#play-button"></use>
                    </svg>
                    <img
                        class="rounded-xlg"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/home-01-hero.jpg"
                        alt="<?php echo __('Engage every people by 2033', 'doxa-website'); ?>"
                    >
                </div>
                <p class="text-center color-primary uppercase font-button font-weight-medium"><?php echo __('The DOXA Vision: Click image to watch the video', 'doxa-website'); ?></p>
            </section>
            <section class="stack stack--md | surface-brand-light">
                <div class="container stack stack--4xl">
                    <div class="stack stack--2xl">
                        <h2 class="highlight" data-highlight-index="1"><?php echo sprintf( _x( '%s unengaged people groups', 'number of unengaged people groups', 'doxa-website'), '2,085' ); ?></h2>
                        <p class="subtext">
                            <?php echo __('Our hope is to see each of them covered in 24-hour prayer, and your church can be part of it.', 'doxa-website'); ?>
                        </p>
                    </div>
                    <div class="reel" id="reel-people-groups" data-reel-mode="auto-scroll" data-research-url="<?php echo esc_url( doxa_translation_url( 'research' ) ); ?>"></div>
                    <div class="stack stack--2xl">
                        <h2 class="highlight" data-highlight-last><?php echo __('A simple path to faithful obedience', 'doxa-website'); ?></h2>
                        <div class="switcher | gap-md">
                            <div class="step-card">
                                <div class="step-card__number">1</div>
                                <div class="step-card__content">
                                    <h2 class="step-card__title"><?php echo __('Pray', 'doxa-website'); ?></h2>
                                    <p><?php echo __('Receive daily prayer points and join believers worldwide in prayer for the unengaged peoples.', 'doxa-website'); ?></p>
                                </div>
                                <a href="<?php echo esc_url( doxa_translation_url( 'pray' ) ); ?>" class="button | compact"><?php echo __('Join', 'doxa-website'); ?></a>
                            </div>
                            <div class="step-card">
                                <div class="step-card__number">2</div>
                                <div class="step-card__content">
                                    <h2 class="step-card__title"><?php echo __('Adopt', 'doxa-website'); ?></h2>
                                    <p><?php echo __('Churches and networks take ownership – praying, giving, and preparing the way for gospel workers.', 'doxa-website'); ?></p>
                                </div>
                                <a href="<?php echo esc_url( doxa_translation_url( 'adopt' ) ); ?>" class="button | compact"><?php echo __('Commit', 'doxa-website'); ?></a>
                            </div>
                            <div class="step-card">
                                <div class="step-card__number">3</div>
                                <div class="step-card__content">
                                    <h2 class="step-card__title"><?php echo __('Engage', 'doxa-website'); ?></h2>
                                    <p><?php echo __('God raises up men and women to go, serve, and proclaim Christ among the nations.', 'doxa-website'); ?></p>
                                </div>
                                <a href="#" class="button | compact invisible-placeholder"><?php echo __('Commit', 'doxa-website'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-02-WhoAreTheUnreached-new.jpg');">
                <h2 class="text-center banner-title invisible-placeholder"><?php echo __('Who are the unengaged?', 'doxa-website'); ?></h2>
            </section>
            <section class="overflow-hidden progress-section">
                <div class="container stack stack--xl">
                    <h2 class="color-white text-center progress-section__title"><?php echo __('Who are the unengaged?', 'doxa-website'); ?></h2>
                    <div class="concentric-circles | gap-md">
                        <div class="switcher-item">
                            <div class="info-card color-brand-dark justify-center">
                                <div class="stack stack--lg | info-card__content">
                                    <h3 class="color-brand-lighter"><?php echo __('Unreached', 'doxa-website'); ?></h3>
                                    <span><?php echo sprintf( _x( '%s Billion', 'number of billions', 'doxa-website'), '3.9' ); ?></span>
                                    <span class="color-brand-lighter"><?php echo sprintf( _x( '%s People Groups', 'number of people groups', 'doxa-website'), '6,602' ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="switcher-item">
                            <div class="info-card color-secondary-very-light justify-center">
                                <div class="stack stack--lg | info-card__content">
                                    <h3><?php echo __('Under-Engaged', 'doxa-website'); ?></h3>
                                    <span class="color-secondary-light"><?php echo sprintf( _x( '%s Billion', 'number of billions', 'doxa-website'), '3.3' ); ?></span>
                                    <span><?php echo sprintf( _x( '%s People Groups', 'number of people groups', 'doxa-website'), '5,119' ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="switcher-item">
                            <div class="info-card color-secondary-very-light justify-center">
                                <div class="stack stack--lg | info-card__content">
                                    <h3><?php echo __('Frontier People', 'doxa-website'); ?></h3>
                                    <span class="color-secondary-light"><?php echo sprintf( _x( '%s Billion', 'number of billions', 'doxa-website'), '2' ); ?></span>
                                    <span><?php echo sprintf( _x( '%s People Groups', 'number of people groups', 'doxa-website'), '4,788' ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="switcher-item">
                            <div class="info-card surface-brand-dark justify-center">
                                <div class="stack stack--lg | info-card__content">
                                    <h3><?php echo __('Unengaged', 'doxa-website'); ?></h3>
                                    <span><?php echo sprintf( _x( '%s Million', 'number of millions', 'doxa-website'), '202' ); ?></span>
                                    <span><?php echo sprintf( _x( '%s People Groups', 'number of people groups', 'doxa-website'), '2,085' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo esc_url( doxa_translation_url( 'about/definitions' ) ); ?>" class="with-icon | light-link mx-auto">
                        <?php echo __('Learn More', 'doxa-website'); ?>
                        <svg class="icon | rotate-90">
                            <use href="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-chevron.svg#chevron-up"></use>
                        </svg>
                    </a>
                </div>
            </section>
            <section class="surface-white">
                <div class="container stack stack--xl">
                    <h2><?php echo __('Vision 2033', 'doxa-website'); ?></h2>
                    <div class="">
                        <div class="switcher" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img
                                    class="center"
                                    src="<?php echo get_template_directory_uri(); ?>/assets/images/home-03-Vision-2033.jpg"
                                    alt="<?php echo __('Vision 2033', 'doxa-website'); ?>"
                                    style="width: clamp(150px, 25vw, 350px);"
                                >
                            </div>
                            <div class="switcher-item align-center justify-center">
                                <div class="stack stack--xl">
                                    <h3 class="subtext"><?php echo __('In partnership with the global church, our vision is for...', 'doxa-website'); ?></h3>
                                    <ul class="stack stack--md" data-list-color="primary">
                                        <li><?php echo __('Daily 24-hour prayer coverage for all 2,085 unengaged peoples', 'doxa-website'); ?></li>
                                        <li><?php echo __('No unengaged people groups by 2033', 'doxa-website'); ?></li>
                                        <li><?php echo __('Mobilization of 20,000+ DOXA partnership missionaries', 'doxa-website'); ?></li>
                                        <li><?php echo __('Fruitful engagement among frontier peoples and the under-engaged', 'doxa-website'); ?></li>
                                        <li><?php echo __('Church planting movements among every unreached people on earth', 'doxa-website'); ?></li>
                                    </ul>
                                    <a href="<?php echo esc_url( doxa_translation_url( 'about/vision' ) ); ?>" class="with-icon | color-primary-darker ms-auto">
                                        <?php echo __('More', 'doxa-website'); ?>
                                        <svg class="icon | rotate-90">
                                            <use href="<?php echo get_template_directory_uri(); ?>/assets/icons/arrow-chevron.svg#chevron-up"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="surface-brand-light">
                <div class="container stack stack--3xl">
                    <h2 class="highlight" data-highlight-last><?php echo __('Engagement starts with prayer', 'doxa-website'); ?></h2>
                    <div class="switcher | align-center">
                        <div class="switcher-item center grow-none">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/home-04-EngagementStartsWithPrayer.jpg" alt="<?php echo __('Engagement starts with prayer', 'doxa-website'); ?>">
                        </div>
                        <div>
                            <div class="stack stack--3xl | align-center">
                                <p class="text-center max-width-md font-size-lg"><?php echo __('Every movement of the gospel begins with intercession. Cover an unengaged people group in daily prayer and help prepare the way.', 'doxa-website'); ?></p>
                                <a href="<?php echo esc_url( doxa_translation_url( 'pray' ) ); ?>" class="button | compact"><?php echo __('Pray', 'doxa-website'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="switcher container | gap-md" data-width="xl">
                    <div class="switcher-item card | padding-clamp-2xl bg-image align-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-doxa-background.jpg');">
                        <div class="stack stack--md | text-center text-secondary">
                            <h2><?php echo __('What does "DOXA" mean?', 'doxa-website'); ?></h2>
                            <p class="subtext"><?php echo __('DOXA is the Greek word for "GLORY".', 'doxa-website'); ?></p>
                            <p><?php echo __('We chose this name because Jesus is worthy of glory from every tribe, tongue, people, and nation. DOXA reminds us that we partner with the whole Church to take the whole gospel to the whole world – until people from every nation are worshipping Jesus and He alone receives all the glory.', 'doxa-website'); ?></p>
                        </div>
                    </div>
                    <div class="switcher-item center grow-none">
                        <img class="rounded-xlg" src="<?php echo get_template_directory_uri(); ?>/assets/images/home-05-WhatDoesDoxaMean.jpg" alt="<?php echo __('Engagement starts with prayer', 'doxa-website'); ?>">
                    </div>
                </div>
            </section>
        </div>
        <div class="video-modal-overlay" data-state="closed"></div>
        <div class="video-modal" data-state="closed">
            <div style="padding:41.89% 0 0 0;position:relative;">
                <iframe id="vimeo-player" src="https://player.vimeo.com/video/1143355099?h=39f8c1f131&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="Doxa Video"></iframe>
            </div>
            <script src="https://player.vimeo.com/api/player.js"></script>
        </div>
    </main>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>


