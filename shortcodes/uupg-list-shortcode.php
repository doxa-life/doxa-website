<?php

function uupg_list_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'select_url' => defined( 'DOXA_PRAYER_TOOLS_URL' ) ? DOXA_PRAYER_TOOLS_URL : 'https://pray.doxa.life',
        'select_text' => __('Select', 'doxa-website'),
        'per_page' => 6,
        'more_per_page' => 12,
        'initial_search_term' => '',
    ), $atts );

    $translations = [
        'select' => $atts['select_text'],
        'full_profile' => __('Full Profile', 'doxa-website'),
        'prayer_coverage' => __('Intercessors', 'doxa-website'),
        'loading' => __('Loading results...', 'doxa-website'),
        'load_more' => __('Load More', 'doxa-website'),
        'total' => __('Total', 'doxa-website'),
        'search' => __('Search names, country/continent, religions...', 'doxa-website'),
        'see_all' => __('See All', 'doxa-website'),
    ];

    $translations = json_encode( $translations );

    ob_start();
    ?>

    <uupgs-list
        languageCode="<?php echo doxa_get_language_code(); ?>"
        selectUrl="<?php echo esc_attr( $atts['select_url'] ); ?>"
        researchUrl="<?php echo esc_url( doxa_translation_url( 'research' ) ); ?>"
        perPage="<?php echo esc_attr( $atts['per_page'] ); ?>"
        morePerPage="<?php echo esc_attr( $atts['more_per_page'] ); ?>"
        initialSearchTerm="<?php echo esc_attr( $atts['initial_search_term'] ); ?>"
        useSelectCard
        hideSeeAllLink
        randomizeList
        t="<?php echo esc_attr( $translations ); ?>"
    ></uupgs-list>

    <?php
    return ob_get_clean();
}
add_shortcode('uupg_list', 'uupg_list_shortcode');