<?php

function general_resources_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'layout' => 'on-sidebar-page',
        'use_documents' => false,
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
            'download_link' => get_doxa_video_url(),
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

    $document_resources_no_image = [
        'introduction_2025' => [
            'title' => esc_html__('Introduction 2025', 'doxa-website'),
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/introduction-2025-$lang_code.pptx",
        ],
        'vision_and_values' => [
            'title' => esc_html__('Vision and Values', 'doxa-website'),
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/vision-and-values-$lang_code.docx",
        ],
        'definitions' => [
            'title' => esc_html__('Definitions', 'doxa-website'),
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/definitions-$lang_code.docx",
        ],
        'investment_policy_statement' => [
            'title' => esc_html__('Doxa Endowment Investment Policy Statement', 'doxa-website'),
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/doxa-endowment-policy-$lang_code.docx",
        ],
        'initial_proposal' => [
            'title' => esc_html__('Initial Proposal', 'doxa-website'),
            'download_type' => 'file',
            'download_link' => $s3_url . "documents/initial-proposal-$lang_code.pdf",
        ],
    ];

    if ( $atts['use_documents'] ) {
        $no_image_resources = $document_resources_no_image;
    } else {
        $no_image_resources = $general_resources_no_image;
    }

    ob_start();
    ?>

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
            <div class="grid" data-width-<?php echo $layout === 'on-sidebar-page' ? 'lg' : 'lg'; ?>>
                <?php foreach ( $no_image_resources as $resource ) : ?>
                    <div class="card | resource-card | switcher | align-center rounded-md" data-width="md" padding-small>
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

        <script>
            /* Also in template-uupg-resources.php */
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

    <?php
    return ob_get_clean();
}
add_shortcode( 'general_resources', 'general_resources_shortcode' );
