<?php
/**
 * Template Name: Pray
 *
 * Custom template for the Pray page - completely independent of posts/archive templates
 */

get_header( 'top' ); ?>

<div class="page bg-secondary">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="pray-page">
            <section>
                <div class="container stack stack--2xl">
                    <div class="stack stack-md">
                        <h1 class="h2 highlight" data-highlight-index="1"><?php echo __('Prayer for an unengaged people group', 'doxa-website'); ?></h1>
                        <p class="subtext"><?php echo __('Help prepare the way for gospel engagement through prayer', 'doxa-website'); ?></p>
                    </div>
                    <div class="three-part-switcher">
                        <div class="card-two-tone | text-center grow-1">
                            <div class="stack stack--lg">
                                <h2 class="h3"><?php echo __('Prayer Goal', 'doxa-website'); ?></h2>
                                <p class="subtext font-size-md"><?php echo __('24-Hour Prayer Coverage', 'doxa-website'); ?></p>
                                <p class="subtext font-size-md"><?php echo __('Mobilize 144+ people praying 10 minutes a day for all 2,085 people groups', 'doxa-website'); ?></p>
                            </div>
                            <div>
                                <h2 class="h3"><?php echo __('Current Status', 'doxa-website'); ?></h2>
                                <span class="font-size-4xl font-weight-bold font-button"><span id="prayer-current-status">0</span> / 2085</span>
                                <div class="stack stack--3xs">
                                    <p class="subtext font-size-md"><?php echo __('People groups with committed 24-hour prayer coverage', 'doxa-website'); ?></p>
                                    <div class="progress-bar" data-size="md">
                                        <div class="progress-bar__slider" id="prayer-current-status-percentage" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grow-2 bg-image rounded-md" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/pray-01-hero.jpg');">
                        </div>
                    </div>
                </div>
            </section>
            <section class="surface-brand-light">
                <div class="container stack stack--3xl">
                    <h2><?php echo __('Where do I start?', 'doxa-website'); ?></h2>
                    <div class="switcher | gap-md">
                        <div class="step-card">
                            <div class="step-card__number">1</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Choose', 'doxa-website'); ?></h2>
                                <p><?php echo __('Choose an unengaged people group you will commit to pray for daily.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-card__number">2</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Sign up', 'doxa-website'); ?></h2>
                                <p><?php echo __('Sign up to receive daily prayer points and updates for this people group.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-card__number">3</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Pray', 'doxa-website'); ?></h2>
                                <p><?php echo __('Pray for 10 minutes a day and help provide 24-hour prayer coverage.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                    </div>
                    <a href="#choose-people-group" class="button | compact mx-auto"><?php echo __('Get Started', 'doxa-website'); ?></a>
                </div>
            </section>
            <section>
                <div class="container">
                    <div class="switcher | align-center" data-width="xl">
                        <div class="stack | grow-2 align-center">
                            <div class="stack stack--2xl">
                                <h2 class="highlight" data-highlight-index="2"><?php echo __('Your daily prayer guide', 'doxa-website'); ?></h2>
                                <ul class="stack stack--sm" data-list-color="primary">
                                    <li><?php echo __('Scripture-centered prayer themes', 'doxa-website'); ?></li>
                                    <li><?php echo __('Spirit-led reflection and guidance', 'doxa-website'); ?></li>
                                    <li><?php echo __('Real prayer needs from the field', 'doxa-website'); ?></li>
                                    <li><?php echo __('Photos, stories and testimonies', 'doxa-website'); ?></li>
                                    <li><?php echo __('Key insights about the people group', 'doxa-website'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <img class="center" src="<?php echo get_template_directory_uri(); ?>/assets/images/pray-02-PrayerFUEL-Phone-graphic-2.png" alt="<?php echo __('Your daily prayer guide', 'doxa-website'); ?>">
                        </div>
                    </div>
                </div>
            </section>
            <section class="surface-white">
                <div class="container">
                    <div class="stack stack--lg">
                        <h2><?php echo __('Why prayer matters', 'doxa-website'); ?></h2>
                        <div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Pray-04-Doxa.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl"><?php echo __('They have no one praying for them', 'doxa-website'); ?></h4>
                                <p><?php echo __('Many unengaged people groups have no churches, no missionaries, and often no believers, meaning little to no consistent prayer is being offered on their behalf.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Pray-05-Doxa.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl"><?php echo __('Prayer prepares the way for the gospel', 'doxa-website'); ?></h4>
                                <p><?php echo __('Prayer softens hearts, opens doors, and invites the work of the Holy Spirit long before workers arrive or the gospel is proclaimed.', 'doxa-website'); ?></p>
                            </div>
                        </div><div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Pray-06-Doxa.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl"><?php echo __('Prayer unites the global church', 'doxa-website'); ?></h4>
                                <p><?php echo __('When we pray, we join believers around the world in God\'s mission, standing together for peoples still waiting to hear the name of Jesus.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="surface-brand-dark">
                <div class="container stack stack--3xl">
                    <div class="stack stack--md">
                        <h2 id="choose-people-group"><?php echo __('Choose a people group', 'doxa-website'); ?></h2>
                        <p class="subtext"><?php echo __('Select a highlighted unengaged people group, or search for a specific group or location below.', 'doxa-website'); ?></p>
                    </div>

                    <?php

                        $language_code = doxa_get_language_code();
                        $select_url = $language_code !== 'en' ? 'https://pray.doxa.life/' . $language_code . '/' : 'https://pray.doxa.life/';

                    ?>

                    <uupgs-list
                        languageCode="<?php echo $language_code; ?>"
                        selectUrl="<?php echo $select_url; ?>"
                        researchUrl="<?php echo esc_url( doxa_translation_url( 'research' ) ); ?>"
                        t="<?php echo esc_attr( json_encode( [
                            'select' => __('Select', 'doxa-website'),
                            'full_profile' => __('Full Profile', 'doxa-website'),
                            'prayer_coverage' => __('Intercessors', 'doxa-website'),
                            'loading' => __('Loading results...', 'doxa-website'),
                            'load_more' => __('Load More', 'doxa-website'),
                            'total' => __('Total', 'doxa-website'),
                            'search' => __('Search names, country/continent, religions...', 'doxa-website'),
                            'see_all' => __('See All', 'doxa-website'),
                        ])); ?>"
                        perPage="6"
                        morePerPage="12"
                        dontShowListOnLoad
                        useSelectCard
                        useHighlightedUUPGs
                    ></uupgs-list>
                </div>
            </section>
            <section>
                <div class="container stack stack--lg">
                    <h2><?php echo __('Prayer Progress', 'doxa-website'); ?></h2>
                    <div id="prayer-map"></div>
                </div>
            </section>
            <section>
                <div class="container stack stack--5xl">
                    <figure class="text-center font-size-5xl font-heading">
                        <blockquote><?php echo __('Pray earnestly to the Lord of the harvest ...that He would send laborers to the [Unengaged].', 'doxa-website'); ?></blockquote>
                        <figcaption>- <?php echo __('Jesus', 'doxa-website'); ?></figcaption>
                    </figure>
                    <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/pray-03-bottom-unsplash.jpg" alt="<?php echo __('Jesus', 'doxa-website'); ?>"></div>
                </div>
            </section>
        </div>
    </main>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>
