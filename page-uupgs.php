<?php
/**
 * Template Name: UUPGS
 *
 * Custom template for the UUPGS page - completely independent of posts/archive templates
 */

get_header( 'top' ); ?>

<?php $search = get_query_var( 'uupg_search' ); ?>

<div class="page">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="container page-content uupgs-page stack stack--3xl">
            <h1 class="text-center highlight" data-highlight-last><?php echo __('Find a UUPG', 'doxa-website'); ?></h1>
            <uupgs-list
                languageCode="<?php echo doxa_get_language_code(); ?>"
                researchUrl="<?php echo esc_url( doxa_translation_url( 'research' ) ); ?>"
                t="<?php echo esc_attr( json_encode( get_uupg_list_translations() )); ?>"
                initialSearchTerm="<?php echo esc_attr( $search ); ?>"
                hideSeeAllLink
            ></uupgs-list>
        </div>
    </main>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>
