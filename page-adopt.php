<?php
/**
 * Template Name: Adopt
 *
 * Custom template for the Adopt page - completely independent of posts/archive templates
 */

get_header( 'top' ); ?>

<div class="page bg-secondary">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="adopt-page">
            <section>
                <div class="container stack stack--2xl">
                    <div class="stack stack-md">
                        <h1 class="h2 highlight" data-highlight-index="1"><?php echo __('Adopt an unengaged people group', 'doxa-website'); ?></h1>
                        <p class="subtext"><?php echo __('A church-led commitment to pray, give, and send so that gospel access begins', 'doxa-website'); ?></p>
                    </div>
                    <div class="three-part-switcher">
                        <div class="card-two-tone | text-center grow-1">
                            <div class="stack stack--lg">
                                <h2 class="h3"><?php echo __('Adoption Goal', 'doxa-website'); ?></h2>
                                <p class="subtext font-size-md"><?php echo __('Every unengaged people group adopted by a church committed to prayer, giving, and sending', 'doxa-website'); ?></p>
                            </div>
                            <div>
                                <h2 class="h3"><?php echo __('Current Status', 'doxa-website'); ?></h2>
                                <span class="font-size-4xl font-weight-bold font-button"><span id="adopted-current-status">0</span> / 2085</span>
                                <div class="stack stack--3xs">
                                    <p class="subtext font-size-md"><?php echo __('people groups adopted', 'doxa-website'); ?></p>
                                    <div class="progress-bar" data-size="md">
                                        <div class="progress-bar__slider" id="adopted-current-status-percentage" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grow-2 bg-image rounded-md" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/adopt-01-africa-4women.jpg');">
                        </div>
                    </div>
                </div>
            </section>
            <section class="stack stack--md container">
                <div>
                    <h1 class="color-brand-light highlight" data-highlight-index="1" data-highlight-last data-highlight-color="primary"><?php echo __( 'Adoption Progress', 'doxa-website' ); ?></h1>
                </div>
                <div class="doxa-map-slot rounded-md">
                    <doxa-map
                        id="adopt-map"
                        profile-config="<?php echo esc_attr( wp_json_encode( [
                            'profile'    => 'doxa-simple-map',
                            'tk'         => defined( 'MAPBOX_PUBLIC_TOKEN' ) ? MAPBOX_PUBLIC_TOKEN : '',
                            'instanceId' => 'adopt-map',
                            'dataSource' => 'pray-tools',
                            'tabs'       => [
                                [
                                    'id'            => 'adoption',
                                    'label'         => 'Adoption',
                                    'colorStrategy' => 'adoption',
                                    'legend'        => 'adoption',
                                    'popup'         => 'adoption',
                                ],
                            ],
                        ] ) ); ?>"
                    ></doxa-map>
                    <div class="feedback-widget-slot feedback-widget-slot--adopt">
                        <feedback-widget
                            profile-config="<?php echo esc_attr( wp_json_encode( [
                                'profile'    => 'chat-bubble',
                                'instanceId' => 'fb-adopt-map',
                                'projectId'  => 'dd1d9144-3da9-4a3b-87e8-7c17d9e94af0',
                                'apiBase'    => 'https://support.gospelambition.org',
                                'enabled'    => true,
                            ] ) ); ?>"
                        ></feedback-widget>
                    </div>
                </div>
            </section>
            <section class="surface-brand-light">
                <div class="container stack stack--3xl">
                    <h2><?php echo __('How Adoption Works', 'doxa-website'); ?></h2>
                    <div class="switcher | gap-md">
                        <div class="step-card">
                            <div class="step-card__number">1</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Choose', 'doxa-website'); ?></h2>
                                <p><?php echo __('Prayerfully select an unengaged people group to adopt.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-card__number">2</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Mobilize', 'doxa-website'); ?></h2>
                                <p><?php echo __('Raise up 144+ daily intercessors to pray 10 minutes a day.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-card__number">3</div>
                            <div class="step-card__content" data-no-action>
                                <h2 class="step-card__title overflow-wrap-anywhere"><?php echo __('Partner', 'doxa-website'); ?></h2>
                                <p><?php echo __('Partner through prayer, giving, and sending so that gospel access begins.', 'doxa-website'); ?></p>
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
                                <h2 class="highlight" data-highlight-index="1"><?php echo __('Adoption Guide & Resources', 'doxa-website'); ?></h2>
                                <ul class="stack stack--sm" data-list-color="primary">
                                    <li><?php echo __('Step-by-step guidance for churches adopting a people group', 'doxa-website'); ?></li>
                                    <li><?php echo __('Tools to mobilize prayer and participation across your congregation', 'doxa-website'); ?></li>
                                    <li><?php echo __('Printable and digital resources to promote awareness and engagement', 'doxa-website'); ?></li>
                                    <li><?php echo __('Helpful tips for sustaining long-term commitment', 'doxa-website'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <img class="center" src="<?php echo get_template_directory_uri(); ?>/assets/images/adopt-02-ipad-mockup.png" alt="<?php echo __('Your daily prayer guide', 'doxa-website'); ?>">
                        </div>
                    </div>
                </div>
            </section>
            <section class="surface-white">
                <div class="container">
                    <div class="stack stack--lg">
                        <h2><?php echo __('Why adoption matters', 'doxa-website'); ?></h2>
                        <div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Adopt-03-DurbetInMongolia.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl"><?php echo __('They have no gospel access', 'doxa-website'); ?></h4>
                                <p><?php echo __('Unengaged people groups have no missionaries, no churches, and often no known believers. Adoption helps ensure they are finally seen, prayed for, and intentionally pursued with the gospel.', 'doxa-website'); ?></p>
                            </div>
                        </div>
                        <div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Adopt-04-Maha-Brahmin-in-India.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl overflow-wrap-anywhere"><?php echo __('Prayer opens the door for engagement', 'doxa-website'); ?></h4>
                                <p><?php echo __('Adoption mobilizes 144+ daily intercessors, creating 24 hours of prayer that prepares the soil, breaks spiritual barriers, and supports workers who go. Every gospel movement begins with prayer.', 'doxa-website'); ?></p>
                            </div>
                        </div><div class="switcher | gap-md" data-width="xl">
                            <div class="switcher-item center grow-none">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Adopt-05-KamaraInGhana.jpg" alt="<?php echo __('Adopt an unengaged people group', 'doxa-website'); ?>">
                            </div>
                            <div class="stack stack--lg | text-card | surface-brand-lightest justify-center">
                                <h4 class="font-heading font-size-2xl"><?php echo __('Churches become active partners in God\'s mission', 'doxa-website'); ?></h4>
                                <p><?php echo __('Adoption invites the local church into meaningful participation - praying, giving, and sending – so that a people group moves from unengaged to engaged, and ultimately becomes a worshipping community for Jesus.', 'doxa-website'); ?></p>
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
                    <uupgs-list
                        languageCode="<?php echo doxa_get_language_code(); ?>"
                        selectUrl="<?php echo esc_url( doxa_translation_url( 'adopt' ) ); ?>"
                        researchUrl="<?php echo esc_url( doxa_translation_url( 'research' ) ); ?>"
                        t="<?php echo esc_attr( json_encode( [
                            'select' => __('Adopt', 'doxa-website'),
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
                <div class="container stack stack--5xl">
                    <figure class="text-center font-size-5xl font-heading">
                        <blockquote class="overflow-wrap-anywhere"><?php echo __('I was a stranger and you invited me in.', 'doxa-website'); ?></blockquote>
                        <figcaption>- <?php echo __('Jesus', 'doxa-website'); ?></figcaption>
                    </figure>
                    <div><img src="<?php echo get_template_directory_uri(); ?>/assets/images/adopt-bottom-banner.jpg" alt="<?php echo __('Jesus', 'doxa-website'); ?>"></div>
                </div>
            </section>
        </div>
    </main>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>
