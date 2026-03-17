<?php

function general_resources_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'layout' => 'on-sidebar-page',
    ), $atts );

    if ( $atts['layout'] === 'on-sidebar-page' ) {
        $layout = 'on-sidebar-page';
    } else {
        $layout = 'on-page';
    }

    $lang_code = doxa_get_language_code();
    $s3_url = 'https://s3.doxa.life/';
    $image_url = get_template_directory_uri() . '/assets/images/';

    $general_resources = [
        'doxa_playbook' => [
            'title' => esc_html__('DOXA Playbook', 'doxa-website'),
            'image_url' => $image_url . 'playbook.png',
            'style' => 'width: 55%;',
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/doxa-playbook-$lang_code.pdf",
        ],
        'doxa_playbook_slides' => [
            'title' => esc_html__('DOXA Playbook Slides', 'doxa-website'),
            'image_url' => $image_url . 'doxa-slides.png',
            'style' => 'width: 80%; padding-top: 10%; padding-bottom: 10%;',
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/doxa-playbook-slides-$lang_code.pdf",
        ],
        'doxa_promo_video' => [
            'title' => esc_html__('DOXA Promo Video', 'doxa-website'),
            'image_url' => $image_url . 'video.png',
            'style' => 'width: 80%; padding-top: 10%; padding-bottom: 10%;',
            'download_type' => 'link',
            'download_link' => 'https://vimeo.com/1143355099/39f8c1f131?fl=pl&fe=vl',
        ],
    ];

    $general_resources_no_image = [
        'champion_tips' => [
            'title' => esc_html__('Champion Tips', 'doxa-website'),
            'download_type' => 'link',
            'download_link' => doxa_translation_url( 'resources/tips-for-prayer-champions/' ),
        ],
        'discussion_guide' => [
            'title' => esc_html__('Discussion Guide', 'doxa-website'),
            'download_type' => 'link',
            'download_link' => doxa_translation_url( 'resources/small-group-discussion-guide/' ),
        ],
        'talking_points' => [
            'title' => esc_html__('DOXA Campaign Talking Points', 'doxa-website'),
            'download_type' => 'link',
            'download_link' => doxa_translation_url( 'resources/talking-points/' ),
        ],
        'email_templates' => [
            'title' => esc_html__('Email Templates', 'doxa-website'),
            'download_type' => 'link',
            'download_link' => doxa_translation_url( 'resources/email-templates/' ),
        ],
    ];

    ob_start();
    ?>

        <div class="container stack stack--3xl">
            <div class="stack stack--2xl">
                <div class="grid" data-width-<?php echo $layout === 'on-sidebar-page' ? 'sm' : 'md'; ?>>
                    <?php foreach ( $general_resources as $resource ) : ?>
                        <div class="card | resource-card | stack stack--xs | align-center rounded-md" padding-small>
                            <div class="resource-card__image" style="<?php echo isset( $resource['style'] ) ? esc_attr( $resource['style'] ) : ''; ?>"><img src="<?php echo esc_attr( $resource['image_url'] ); ?>" alt="<?php echo esc_attr( $resource['title'] ); ?>"></div>
                            <h3 class="h4 text-center font-heading mb-auto"><?php echo esc_html( $resource['title'] ); ?></h3>
                            <div class="switcher | text-center gap-md" data-width="xs">
                                <a target="_blank" href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact <?php echo $resource['download_type'] === 'file' ? 'outline' : ''; ?>">
                                    <?php echo esc_html__('View', 'doxa-website'); ?>
                                </a>

                                <?php if ( $resource['download_type'] === 'file' ) : ?>

                                    <a download target="_blank" href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact">
                                        <?php echo esc_html__('Download', 'doxa-website'); ?>
                                    </a>

                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="grid" data-width-<?php echo $layout === 'on-sidebar-page' ? 'md' : 'lg'; ?>>
                    <?php foreach ( $general_resources_no_image as $resource ) : ?>
                        <div class="card | resource-card | repel | align-center rounded-md" padding-small>
                            <h3 class="h5 font-weight-medium"><?php echo esc_html( $resource['title'] ); ?></h3>
                            <div class="switcher gap-md | text-center" data-width="xs">
                                <a target="_blank" href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact <?php echo $resource['download_type'] === 'file' ? 'outline' : ''; ?>">
                                    <?php echo esc_html__('View', 'doxa-website'); ?>
                                </a>

                                <?php if ( $resource['download_type'] === 'file' ) : ?>

                                    <a download target="_blank" href="<?php echo esc_url( $resource['download_link'] ); ?>" class="button extra-compact">
                                        <?php echo esc_html__('Download', 'doxa-website'); ?>
                                    </a>

                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    <?php
    return ob_get_clean();
}
add_shortcode( 'general_resources', 'general_resources_shortcode' );
