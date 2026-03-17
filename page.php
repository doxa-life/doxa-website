<?php
/**
 * Template Name: Doxa Page
 *
 * The default template for displaying pages.
 * Displays a sidebar menu with all child pages (if they exist) and the current page content.
 */

get_header( 'top' ); ?>

<div class="page">

    <?php get_header(); ?>

    <main class="site-main">
        <div class="container page-content">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    // Get the current page ID
                    $current_page_id = get_the_ID();

                    // Determine if this is a parent or child page
                    $parent_id = wp_get_post_parent_id($current_page_id);

                    // If this is a child page, get the parent ID for the menu
                    // If this is a parent page, use its own ID
                    $menu_parent_id = $parent_id ? $parent_id : $current_page_id;

                    // Get all child pages
                    $child_pages = get_pages(array(
                        'child_of' => $menu_parent_id,
                        'parent' => $menu_parent_id,
                        'sort_column' => 'menu_order',
                        'sort_order' => 'ASC',
                    ));

                    $has_sidebar = $child_pages && count($child_pages) > 0;
                    ?>

                    <div class="<?php echo $has_sidebar ? 'with-sidebar' : ''; ?>">
                        <?php if ($has_sidebar) : ?>
                            <aside class="sidebar">
                                <nav class="stack" aria-label="Child pages navigation">
                                    <?php
                                    // Get the parent page title for the sidebar header
                                    $parent_page = get_post($menu_parent_id);

                                    global $wp;
                                    $current_link = str_replace( doxa_get_language_code() . '/', '', $wp->request );
                                    $parent_slug = $parent_page->post_name;
                                    ?>


                                    <div>
                                        <ul class="stack | max-width-xs" role="list">
                                            <li>
                                                <a class="font-size-lg <?php echo $current_link === $parent_slug ? 'current-link' : ''; ?>" href="<?php echo esc_url(get_permalink($menu_parent_id)); ?>">
                                                    <?php echo esc_html($parent_page->post_title); ?>
                                                </a>
                                            </li>

                                            <?php foreach ($child_pages as $child) : ?>
                                                <li>
                                                    <a class="<?php echo $current_link === $parent_slug . '/' . $child->post_name ? 'current-link' : ''; ?>" href="<?php echo esc_url(get_permalink($child->ID)); ?>">
                                                        <?php echo esc_html($child->post_title); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </nav>
                            </aside>
                        <?php endif; ?>

                        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="page-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="page-body">
                                <?php the_content(); ?>
                            </div>

                            <?php
                            // If this is the parent page and has no content, show child pages grid
                            if (!$parent_id && empty(trim(strip_tags(get_the_content())))) :
                                if ($child_pages) :
                            ?>
                                <div class="grid">
                                    <?php foreach ($child_pages as $child) : ?>
                                        <div class="card" data-variant="secondary">
                                            <?php if (has_post_thumbnail($child->ID)) : ?>
                                                <div class="child-thumbnail">
                                                    <a href="<?php echo esc_url(get_permalink($child->ID)); ?>">
                                                        <?php echo get_the_post_thumbnail($child->ID, 'medium'); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <div class="stack">
                                                <h3>
                                                    <a class="color-white" href="<?php echo esc_url(get_permalink($child->ID)); ?>">
                                                        <?php echo esc_html($child->post_title); ?>
                                                    </a>
                                                </h3>

                                                <?php if ($child->post_excerpt) : ?>
                                                    <div class="child-excerpt">
                                                        <?php echo wp_kses_post($child->post_excerpt); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php
                                endif;
                            endif;
                            ?>
                        </article>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php get_footer(); ?>

</div>

<?php get_footer( 'bottom' ); ?>

