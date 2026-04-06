<?php
 /**
 * Gospel Ambition Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/shortcodes/uupg-list-shortcode.php';
require_once get_template_directory() . '/shortcodes/general-resources-shortcode.php';

add_filter( 'oembed_response_data', function ( $data ) {
    unset( $data['author_name'] );
    unset( $data['author_url'] );
    return $data;
} );

function coming_soon_redirect() {
    $url = $_SERVER['REQUEST_URI'];
    if ( !is_admin() && !str_contains($url, 'admin') && !is_page( 'coming-soon' ) && !is_user_logged_in() ) {
        wp_redirect( home_url( '/coming-soon' ) );
        exit;
    }
}
//add_action( 'template_redirect', 'coming_soon_redirect' );

/**
 * Theme Setup
 */
function gospel_ambition_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Add custom image sizes
    add_image_size('hero-image', 1200, 600, true);
    add_image_size('post-thumbnail', 400, 250, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'gospel-ambition'),
        'secondary' => esc_html__('Secondary Menu', 'gospel-ambition'),
        'footer' => esc_html__('Footer Menu', 'gospel-ambition'),
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'gospel_ambition_setup');

/**
 * Enqueue scripts and styles
 */
function gospel_ambition_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('bebas-neue-font', get_template_directory_uri() . '/assets/fonts/BebasNeue/stylesheet.css', array(), filemtime(get_template_directory() . '/assets/fonts/BebasNeue/stylesheet.css'));
    wp_enqueue_style('brandon-grotesque-font', get_template_directory_uri() . '/assets/fonts/Brandon_Grotesque/stylesheet.css', array(), filemtime(get_template_directory() . '/assets/fonts/Brandon_Grotesque/stylesheet.css'));
    wp_enqueue_style('poppins-font', get_template_directory_uri() . '/assets/fonts/Poppins/stylesheet.css', array(), filemtime(get_template_directory() . '/assets/fonts/Poppins/stylesheet.css'));
    wp_enqueue_style('oswald-font', 'https://fonts.googleapis.com/css2?family=Oswald:wght@400&display=swap', array(), null);
    wp_enqueue_style('gospel-ambition-style', get_template_directory_uri() . '/assets/dist/style.css', array(), filemtime(get_template_directory() . '/assets/dist/style.css'));

    // Enqueue Google Fonts
    wp_enqueue_style('gospel-ambition-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), null);

    // Enqueue theme JavaScript
    wp_enqueue_script('gospel-ambition-script', get_template_directory_uri() . '/js/theme.js', array(), filemtime(get_template_directory() . '/js/theme.js'), true);

    // unenqueue jquery
    wp_dequeue_script('jquery');
    wp_enqueue_script('uupgs-script', get_template_directory_uri() . '/assets/dist/main2.js', array(), filemtime(get_template_directory() . '/assets/dist/main2.js'), true);
    $pray_base_url = defined( 'DOXA_PRAYER_TOOLS_URL' ) ? DOXA_PRAYER_TOOLS_URL : 'https://pray.doxa.life';
    wp_localize_script('uupgs-script', 'uupgsData', array(
        'images_url' => trailingslashit( get_template_directory_uri() ) . 'assets/images',
        'icons_url' => trailingslashit( get_template_directory_uri() ) . 'assets/icons',
        'prayBaseUrl' => $pray_base_url,
        'translations' => [
            'click_twice' => __( 'Click again to interact with map', 'doxa-website' ),
        ],
    ));

}
add_action('wp_enqueue_scripts', 'gospel_ambition_scripts');

/**
 * Enqueue Prayer Map scripts and styles on the Pray page
 */
function doxa_map_scripts() {
    if ( ! is_page( 'pray' ) ) {
        return;
    }

    wp_enqueue_script( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js', array(), '2.15.0', true );
    wp_enqueue_style( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css', array(), '2.15.0' );

    wp_enqueue_style( 'prayer-map', get_template_directory_uri() . '/css/prayer-map.css', array( 'mapbox-gl' ), filemtime( get_template_directory() . '/css/prayer-map.css' ) );
    wp_enqueue_script( 'prayer-map', get_template_directory_uri() . '/js/prayer-map.js', array( 'mapbox-gl' ), filemtime( get_template_directory() . '/js/prayer-map.js' ), true );

    $pray_base_url = defined( 'DOXA_PRAYER_TOOLS_URL' ) ? DOXA_PRAYER_TOOLS_URL : 'https://pray.doxa.life';

    $language_code = doxa_get_language_code();
    wp_add_inline_script( 'prayer-map', 'window.prayerMapConfig = ' . wp_json_encode( array(
        'mapboxToken'  => defined( 'MAPBOX_PUBLIC_TOKEN' ) ? MAPBOX_PUBLIC_TOKEN : '',
        'prayBaseUrl'  => $pray_base_url . ( $language_code !== 'en' ? '/' . $language_code : '' ),
        'researchUrl'  => doxa_translation_url( 'research' ),
        'languageCode' => $language_code,
        't' => array(
            'no_prayer'        => __( 'No prayer coverage', 'doxa-website' ),
            'has_prayer'       => __( 'Has prayer coverage', 'doxa-website' ),
            'pray_for_them'    => __( 'Pray for them', 'doxa-website' ),
            'info'             => __( 'Info', 'doxa-website' ),
            'language'         => __( 'Language', 'doxa-website' ),
            'country'          => __( 'Country', 'doxa-website' ),
            'population'       => __( 'Population', 'doxa-website' ),
            'prayer_coverage'  => __( 'Prayer Coverage', 'doxa-website' ),
            'unknown'          => __( 'Unknown', 'doxa-website' ),
            'close'            => __( 'Close', 'doxa-website' ),
            'search_placeholder' => __( 'Search people groups or locations', 'doxa-website' ),
        ),
    ) ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'doxa_map_scripts' );

/**
 * Register widget areas
 */
function gospel_ambition_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'gospel-ambition'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'gospel-ambition'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widgets', 'gospel-ambition'),
        'id'            => 'footer-widgets',
        'description'   => esc_html__('Add widgets to the footer area.', 'gospel-ambition'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'gospel_ambition_widgets_init');

/**
 * Custom excerpt length
 */
function gospel_ambition_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'gospel_ambition_excerpt_length');

/**
 * Custom excerpt more text
 */
function gospel_ambition_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'gospel_ambition_excerpt_more');



/**
 * Custom pagination
 */
function gospel_ambition_pagination() {
    global $wp_query;

    $big = 999999999;

    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '&laquo; Previous',
        'next_text' => 'Next &raquo;',
        'type' => 'list',
        'end_size' => 3,
        'mid_size' => 3
    ));
}

/**
 * Remove unnecessary WordPress features for performance
 */
function gospel_ambition_cleanup() {
    // Remove WordPress emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove WordPress generator meta tag
    remove_action('wp_head', 'wp_generator');

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Remove Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove feed links
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
}
add_action('init', 'gospel_ambition_cleanup');

/**
 * Disable comments entirely
 */
function gospel_ambition_disable_comments() {
    // Disable comments for all post types
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'gospel_ambition_disable_comments');

// Close comments on all posts
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
function gospel_ambition_remove_comments_page() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'gospel_ambition_remove_comments_page');

// Remove comments links from admin bar
function gospel_ambition_remove_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'gospel_ambition_remove_comments_admin_bar');

/**
 * Add Custom Page Order Meta Box
 */
function add_page_order_meta_box() {
    add_meta_box(
        'page-order',
        'Page Order',
        'page_order_meta_box_callback',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_order_meta_box');

/**
 * Page Order Meta Box Callback
 */
function page_order_meta_box_callback($post) {
    wp_nonce_field('save_page_order', 'page_order_nonce');
    $order = $post->menu_order;

    // Use 10 as default for new pages
    if ($order == 0 && $post->post_status == 'auto-draft') {
        $order = 10;
    }
    ?>
    <p>
        <label for="page_order" style="font-weight: bold;">Order:</label><br>
        <input type="number" id="page_order" name="page_order" value="<?php echo esc_attr($order); ?>" min="0" style="width: 100%;" />
    </p>
    <p style="font-size: 12px; color: #666;">
        Lower numbers appear first in the sidebar menu. Default is 10. Pages with the same order are sorted alphabetically.
    </p>
    <?php
}

/**
 * Save Page Order Meta Box Data
 */
function save_page_order($post_id) {
    // Only run for page post type
    if (get_post_type($post_id) !== 'page') {
        return;
    }

    // Verify nonce
    if (!isset($_POST['page_order_nonce']) || !wp_verify_nonce($_POST['page_order_nonce'], 'save_page_order')) {
        return;
    }

    // Skip autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    // Save the order
    if (isset($_POST['page_order'])) {
        $order = intval($_POST['page_order']);

        // Prevent infinite loop by removing the action before updating
        remove_action('save_post', 'save_page_order');

        // Update menu_order
        wp_update_post(array(
            'ID' => $post_id,
            'menu_order' => $order
        ));

        // Re-add the action after updating
        add_action('save_post', 'save_page_order');
    }
}
add_action('save_post', 'save_page_order');

/**
 * Set default menu_order for new pages
 */
function set_default_page_order($post_id, $post, $update) {
    // Only for new pages (not updates)
    if ($update || $post->post_type !== 'page') {
        return;
    }

    // If menu_order is 0 (default), set it to 10
    if ($post->menu_order == 0) {
        remove_action('wp_insert_post', 'set_default_page_order', 10);

        wp_update_post(array(
            'ID' => $post_id,
            'menu_order' => 10
        ));

        add_action('wp_insert_post', 'set_default_page_order', 10, 3);
    }
}
add_action('wp_insert_post', 'set_default_page_order', 10, 3);

/**
 * Add Custom CSS Meta Box for Pages
 */
function add_page_custom_css_meta_box() {
    add_meta_box(
        'page-custom-css',
        'Custom CSS',
        'page_custom_css_meta_box_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_page_custom_css_meta_box');

/**
 * Custom CSS Meta Box Callback
 */
function page_custom_css_meta_box_callback($post) {
    wp_nonce_field('save_page_custom_css', 'page_custom_css_nonce');
    $custom_css = get_post_meta($post->ID, '_page_custom_css', true);
    ?>
    <p>
        <label for="page_custom_css" style="font-weight: bold;">Add custom CSS for this page:</label>
    </p>
    <textarea
        id="page_custom_css"
        name="page_custom_css"
        rows="10"
        style="width: 100%; font-family: 'Courier New', monospace; font-size: 12px;"
        placeholder="/* Enter your custom CSS here */&#10;.my-custom-class {&#10;    color: #bd1218;&#10;    font-size: 18px;&#10;}"
    ><?php echo esc_textarea($custom_css); ?></textarea>
    <p style="margin-top: 10px; font-size: 12px; color: #666;">
        <strong>Note:</strong> This CSS will only apply to this specific page. Don't include &lt;style&gt; tags.
    </p>
    <?php
}

/**
 * Save Custom CSS Meta Box Data
 */
function save_page_custom_css($post_id) {
    // Only runJan 17, 2026 for page post type
    if (get_post_type($post_id) !== 'page') {
        return;
    }

    // Verify nonce
    if (!isset($_POST['page_custom_css_nonce']) || !wp_verify_nonce($_POST['page_custom_css_nonce'], 'save_page_custom_css')) {
        return;
    }

    // Skip autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    // Save the custom CSS
    if (isset($_POST['page_custom_css'])) {
        $custom_css = wp_strip_all_tags($_POST['page_custom_css']);
        update_post_meta($post_id, '_page_custom_css', $custom_css);
    } else {
        delete_post_meta($post_id, '_page_custom_css');
    }
}
add_action('save_post', 'save_page_custom_css');

/**
 * Output Custom CSS in Page Head
 */
function output_page_custom_css() {
    // Only on single pages
    if (!is_page()) {
        return;
    }

    global $post;
    $custom_css = get_post_meta($post->ID, '_page_custom_css', true);

    if (!empty($custom_css)) {
        echo '<style type="text/css" id="page-custom-css-' . $post->ID . '">' . "\n";
        echo '/* Custom CSS for page: ' . get_the_title($post->ID) . ' */' . "\n";
        echo $custom_css . "\n";
        echo '</style>' . "\n";
    }
}
add_action('wp_head', 'output_page_custom_css');

function custom_uupgs_rewrite_rules() {
    $post_id = doxa_get_page_id_by_slug( 'research' );
    $translation_ids = doxa_language_relationships( $post_id );
    if ( empty( $translation_ids ) ) { return; }
    foreach ( $translation_ids as $lang_code => $translation_id ) {
        $post = get_post( $translation_id, OBJECT );
        if ( $lang_code === 'en' ) {
            add_rewrite_rule(
                '^' . $post->post_name . '/search/([^/]+)/?$',
                'index.php?page_id=' . $post->ID . '&uupg_search=$matches[1]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $post->post_name . '/([^/]+)/?$',
                'index.php?page_id=' . $post->ID . '&uupg_slug=$matches[1]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $post->post_name . '/search/([^/]+)/(?<uupg_subpage>[^/]+)/?$',
                'index.php?page_id=' . $post->ID . '&uupg_search=$matches[1]&uupg_subpage=$matches[2]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $post->post_name . '/([^/]+)/(?<uupg_subpage>[^/]+)/?$',
                'index.php?page_id=' . $post->ID . '&uupg_slug=$matches[1]&uupg_subpage=$matches[2]&lang=' . $lang_code,
                'top'
            );
        } else {
            add_rewrite_rule(
                '^' . $lang_code . '/' . $post->post_name . '/search/([^/]+)/?$',
                'index.php?page_id=' . $translation_id . '&uupg_search=$matches[1]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $lang_code . '/' . $post->post_name . '/([^/]+)/?$',
                'index.php?page_id=' . $translation_id . '&uupg_slug=$matches[1]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $lang_code . '/' . $post->post_name . '/search/([^/]+)/(?<uupg_subpage>[^/]+)/?$',
                'index.php?page_id=' . $translation_id . '&uupg_search=$matches[1]&uupg_subpage=$matches[2]&lang=' . $lang_code,
                'top'
            );
            add_rewrite_rule(
                '^' . $lang_code . '/' . $post->post_name . '/([^/]+)/(?<uupg_subpage>[^/]+)/?$',
                'index.php?page_id=' . $translation_id . '&uupg_slug=$matches[1]&uupg_subpage=$matches[2]&lang=' . $lang_code,
                'top'
            );
        }
    }
}
add_action('init', 'custom_uupgs_rewrite_rules');

function custom_adoption_form_rewrite_rules() {
    $post_id = doxa_get_page_id_by_slug( 'adopt' );
    $translation_ids = doxa_language_relationships( $post_id );
    if ( empty( $translation_ids ) ) { return; }
    foreach ( $translation_ids as $lang_code => $translation_id ) {
        $post = get_post( $translation_id, OBJECT );
        if ( $lang_code === 'en' ) {
            add_rewrite_rule(
                '^' . $post->post_name . '/([^/]+)/?$',
                'index.php?page_id=' . $post->ID . '&uupg_slug=$matches[1]&lang=' . $lang_code,
                'top'
            );
        } else {
            add_rewrite_rule(
                '^' . $lang_code . '/' . $post->post_name . '/([^/]+)/?$',
                'index.php?page_id=' . $translation_id . '&uupg_slug=$matches[1]&lang=' . $lang_code,
                'top'
            );
        }
    }
}
add_action('init', 'custom_adoption_form_rewrite_rules');



/**
 * Flush permalinks when the Research page or any of its translations are created or updated.
 */
function doxa_flush_research_permalinks( $post_id, $post, $update ) {
    // Ignore autosaves and revisions.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Only care about pages.
    if ( get_post_type( $post_id ) !== 'page' ) {
        return;
    }

    // Get the main Research page ID (in default language).
    $research_page_id = doxa_get_page_id_by_slug( 'research' );
    if ( empty( $research_page_id ) ) {
        return;
    }

    // Find the translation group for the saved page.
    $relationships = doxa_language_relationships( $post_id );
    if ( ! is_array( $relationships ) ) {
        return;
    }

    // If the Research page is part of this translation group, flush rewrites.
    $related_ids = array_map( 'intval', array_values( $relationships ) );
    if ( in_array( (int) $research_page_id, $related_ids, true ) ) {
        flush_rewrite_rules( false );
    }
}
add_action('save_post', 'doxa_flush_research_permalinks', 10, 3);

// Register the query variable
function custom_uupgs_query_vars($vars) {
    $vars[] = 'uupg_slug';
    $vars[] = 'uupg_search';
    $vars[] = 'uupg_subpage';
    return $vars;
}
add_filter('query_vars', 'custom_uupgs_query_vars');

function custom_uupgs_template($template) {
    $uupg_slug = get_query_var('uupg_slug');
    $uupg_subpage = get_query_var('uupg_subpage');

    // if the url is {slug}/resources, return the template-uupg-resources.php template
    if ($uupg_slug && doxa_is_page('research') && $uupg_subpage === 'resources' ) {
        $custom_template = locate_template('template-uupg-resources.php');
        if ($custom_template) {
            return $custom_template;
        }
    }

    if ($uupg_slug && doxa_is_page('research')) {
        $custom_template = locate_template('template-uupg-detail.php');
        if ($custom_template) {
            return $custom_template;
        }
    }

    return $template;
}
add_filter('template_include', 'custom_uupgs_template');

function custom_adoption_form_template($template) {
    $uupg_slug = get_query_var('uupg_slug');

    if ($uupg_slug && doxa_is_page('adopt')) {
        $custom_template = locate_template('template-adoption-form.php');
        if ($custom_template) {
            return $custom_template;
        }
    }

    return $template;
}
add_filter('template_include', 'custom_adoption_form_template');

/**
 * Prevent Polylang from redirecting custom UUPG routes to a different canonical URL.
 *
 * For example, keep `/es/{translated-research-slug}/{uupg_slug}` instead of redirecting
 * to the default language, so our custom templates continue to work.
 */
function doxa_bypass_polylang_canonical_for_uupg( $redirect_url, $language ) {
    // Only care about page requests.
    if ( ! is_page() ) {
        return $redirect_url;
    }

    $uupg_slug   = get_query_var( 'uupg_slug' );
    $uupg_search = get_query_var( 'uupg_search' );

    // Only affect our special UUPG routes (detail or search), not the plain page.
    if ( empty( $uupg_slug ) && empty( $uupg_search ) ) {
        return $redirect_url;
    }

    // Our rewrites always resolve to the base pages `research` or `adopt`.
    if ( doxa_is_page( 'research' ) || doxa_is_page( 'adopt' ) ) {
        // Returning false tells Polylang not to redirect this request.
        return false;
    }

    return $redirect_url;
}
add_filter( 'pll_check_canonical_url', 'doxa_bypass_polylang_canonical_for_uupg', 10, 2 );

function get_uupg_by_slug( $slug ) {
    $lang_code = doxa_get_language_code();
    $base_url = defined( 'DOXA_PRAYER_TOOLS_URL' ) ? DOXA_PRAYER_TOOLS_URL : 'https://pray.doxa.life';
    $api_url = $base_url . '/api/people-groups/detail/' . urlencode($slug) . '?lang=' . $lang_code;

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return [
            'api_url' => $api_url,
            'response' => $response,
        ];
    }
    $data = json_decode($response['body'], true);
    return $data;
}

function doxa_is_page( $slug ) {
    $post_id = doxa_get_page_id_by_slug( $slug );
    $lang_code = doxa_get_language_code();
    $translation_ids = doxa_language_relationships( $post_id );

    $translation_id = $translation_ids[$lang_code] ?? $post_id;
    $translation_post = get_post( $translation_id, OBJECT );

    return is_page( $translation_post->post_name );
}

function doxa_get_countries() {
    $countries = [
        [
            "value" => "VAT",
            "label" => "Vatican City"
        ],
        [
            "value" => "MAC",
            "label" => "Macao"
        ],
        [
            "value" => "HKG",
            "label" => "Hong Kong"
        ],
        [
            "value" => "KEN",
            "label" => "Kenya"
        ],
        [
            "value" => "CAN",
            "label" => "Canada"
        ],
        [
            "value" => "DEU",
            "label" => "Germany"
        ],
        [
            "value" => "USA",
            "label" => "the United States"
        ],
        [
            "value" => "SPM",
            "label" => "Saint Pierre and Miquelon"
        ],
        [
            "value" => "ESH",
            "label" => "Western Sahara"
        ],
        [
            "value" => "FLK",
            "label" => "Falkland Islands (Islas Malvinas)"
        ],
        [
            "value" => "ZAF",
            "label" => "South Africa"
        ],
        [
            "value" => "GIN",
            "label" => "Guinea"
        ],
        [
            "value" => "ESP",
            "label" => "Spain"
        ],
        [
            "value" => "MMR",
            "label" => "Myanmar"
        ],
        [
            "value" => "BGD",
            "label" => "Bangladesh"
        ],
        [
            "value" => "MOZ",
            "label" => "Mozambique"
        ],
        [
            "value" => "MUS",
            "label" => "Mauritius"
        ],
        [
            "value" => "IND",
            "label" => "India"
        ],
        [
            "value" => "PSE",
            "label" => "Palestine"
        ],
        [
            "value" => "PAK",
            "label" => "Pakistan"
        ],
        [
            "value" => "GRC",
            "label" => "Greece"
        ],
        [
            "value" => "NPL",
            "label" => "Nepal"
        ],
        [
            "value" => "LKA",
            "label" => "Sri Lanka"
        ],
        [
            "value" => "FRA",
            "label" => "France"
        ],
        [
            "value" => "BRA",
            "label" => "Brazil"
        ],
        [
            "value" => "HUN",
            "label" => "Hungary"
        ],
        [
            "value" => "UGA",
            "label" => "Uganda"
        ],
        [
            "value" => "KOR",
            "label" => "South Korea"
        ],
        [
            "value" => "GBR",
            "label" => "the United Kingdom"
        ],
        [
            "value" => "LAO",
            "label" => "Laos"
        ],
        [
            "value" => "ERI",
            "label" => "Eritrea"
        ],
        [
            "value" => "EGY",
            "label" => "Egypt"
        ],
        [
            "value" => "MRT",
            "label" => "Mauritania"
        ],
        [
            "value" => "ISL",
            "label" => "Iceland"
        ],
        [
            "value" => "KAZ",
            "label" => "Kazakhstan"
        ],
        [
            "value" => "MAR",
            "label" => "Morocco"
        ],
        [
            "value" => "ETH",
            "label" => "Ethiopia"
        ],
        [
            "value" => "IRQ",
            "label" => "Iraq"
        ],
        [
            "value" => "OMN",
            "label" => "Oman"
        ],
        [
            "value" => "ITA",
            "label" => "Italy"
        ],
        [
            "value" => "JOR",
            "label" => "Jordan"
        ],
        [
            "value" => "MKD",
            "label" => "North Macedonia"
        ],
        [
            "value" => "TUR",
            "label" => "Turkiye"
        ],
        [
            "value" => "LBN",
            "label" => "Lebanon"
        ],
        [
            "value" => "JPN",
            "label" => "Japan"
        ],
        [
            "value" => "BHR",
            "label" => "Bahrain"
        ],
        [
            "value" => "AFG",
            "label" => "Afghanistan"
        ],
        [
            "value" => "SAU",
            "label" => "Saudi Arabia"
        ],
        [
            "value" => "IRN",
            "label" => "Iran"
        ],
        [
            "value" => "KGZ",
            "label" => "Kyrgyzstan"
        ],
        [
            "value" => "TJK",
            "label" => "Tajikistan"
        ],
        [
            "value" => "RUS",
            "label" => "the Russian Federation"
        ],
        [
            "value" => "MDG",
            "label" => "Madagascar"
        ],
        [
            "value" => "SSD",
            "label" => "South Sudan"
        ],
        [
            "value" => "THA",
            "label" => "Thailand"
        ],
        [
            "value" => "UZB",
            "label" => "Uzbekistan"
        ],
        [
            "value" => "ARM",
            "label" => "Armenia"
        ],
        [
            "value" => "MYS",
            "label" => "Malaysia"
        ],
        [
            "value" => "GEO",
            "label" => "Georgia"
        ],
        [
            "value" => "SEN",
            "label" => "Senegal"
        ],
        [
            "value" => "BTN",
            "label" => "Bhutan"
        ],
        [
            "value" => "ZMB",
            "label" => "Zambia"
        ],
        [
            "value" => "CYP",
            "label" => "Cyprus"
        ],
        [
            "value" => "ISR",
            "label" => "Israel"
        ],
        [
            "value" => "GMB",
            "label" => "The Gambia"
        ],
        [
            "value" => "PHL",
            "label" => "the Philippines"
        ],
        [
            "value" => "TTO",
            "label" => "Trinidad and Tobago"
        ],
        [
            "value" => "GLP",
            "label" => "Guadeloupe"
        ],
        [
            "value" => "TKM",
            "label" => "Turkmenistan"
        ],
        [
            "value" => "MTQ",
            "label" => "Martinique"
        ],
        [
            "value" => "MWI",
            "label" => "Malawi"
        ],
        [
            "value" => "CHN",
            "label" => "China"
        ],
        [
            "value" => "REU",
            "label" => "Reunion"
        ],
        [
            "value" => "NLD",
            "label" => "the Netherlands"
        ],
        [
            "value" => "SUR",
            "label" => "Suriname"
        ],
        [
            "value" => "ATG",
            "label" => "Antigua and Barbuda"
        ],
        [
            "value" => "BWA",
            "label" => "Botswana"
        ],
        [
            "value" => "DZA",
            "label" => "Algeria"
        ],
        [
            "value" => "YEM",
            "label" => "Yemen"
        ],
        [
            "value" => "HRV",
            "label" => "Croatia"
        ],
        [
            "value" => "GNB",
            "label" => "Guinea-Bissau"
        ],
        [
            "value" => "AZE",
            "label" => "Azerbaijan"
        ],
        [
            "value" => "QAT",
            "label" => "Qatar"
        ],
        [
            "value" => "BEL",
            "label" => "Belgium"
        ],
        [
            "value" => "ARE",
            "label" => "the United Arab Emirates"
        ],
        [
            "value" => "DJI",
            "label" => "Djibouti"
        ],
        [
            "value" => "GTM",
            "label" => "Guatemala"
        ],
        [
            "value" => "CHL",
            "label" => "Chile"
        ],
        [
            "value" => "GUY",
            "label" => "Guyana"
        ],
        [
            "value" => "GRD",
            "label" => "Grenada"
        ],
        [
            "value" => "ARG",
            "label" => "Argentina"
        ],
        [
            "value" => "VEN",
            "label" => "Venezuela"
        ],
        [
            "value" => "TCD",
            "label" => "Chad"
        ],
        [
            "value" => "LBY",
            "label" => "Libya"
        ],
        [
            "value" => "SYC",
            "label" => "Seychelles"
        ],
        [
            "value" => "TZA",
            "label" => "Tanzania"
        ],
        [
            "value" => "RWA",
            "label" => "Rwanda"
        ],
        [
            "value" => "BDI",
            "label" => "Burundi"
        ],
        [
            "value" => "DNK",
            "label" => "Denmark"
        ],
        [
            "value" => "ZWE",
            "label" => "Zimbabwe"
        ],
        [
            "value" => "NIC",
            "label" => "Nicaragua"
        ],
        [
            "value" => "GUF",
            "label" => "French Guiana"
        ],
        [
            "value" => "JAM",
            "label" => "Jamaica"
        ],
        [
            "value" => "PAN",
            "label" => "Panama"
        ],
        [
            "value" => "IDN",
            "label" => "Indonesia"
        ],
        [
            "value" => "KWT",
            "label" => "Kuwait"
        ],
        [
            "value" => "ROU",
            "label" => "Romania"
        ],
        [
            "value" => "BGR",
            "label" => "Bulgaria"
        ],
        [
            "value" => "BIH",
            "label" => "Bosnia and Herzegovina"
        ],
        [
            "value" => "SDN",
            "label" => "Sudan"
        ],
        [
            "value" => "FIN",
            "label" => "Finland"
        ],
        [
            "value" => "UKR",
            "label" => "Ukraine"
        ],
        [
            "value" => "VCT",
            "label" => "Saint Vincent and the Grenadines"
        ],
        [
            "value" => "BLZ",
            "label" => "Belize"
        ],
        [
            "value" => "LCA",
            "label" => "Saint Lucia"
        ],
        [
            "value" => "BRB",
            "label" => "Barbados"
        ],
        [
            "value" => "SOM",
            "label" => "Somalia"
        ],
        [
            "value" => "SVN",
            "label" => "Slovenia"
        ],
        [
            "value" => "COL",
            "label" => "Colombia"
        ],
        [
            "value" => "PER",
            "label" => "Peru"
        ],
        [
            "value" => "ECU",
            "label" => "Ecuador"
        ],
        [
            "value" => "TWN",
            "label" => "Taiwan"
        ],
        [
            "value" => "MLT",
            "label" => "Malta"
        ],
        [
            "value" => "SWE",
            "label" => "Sweden"
        ],
        [
            "value" => "AUT",
            "label" => "Austria"
        ],
        [
            "value" => "DMA",
            "label" => "Dominica"
        ],
        [
            "value" => "VNM",
            "label" => "Vietnam"
        ],
        [
            "value" => "MNG",
            "label" => "Mongolia"
        ],
        [
            "value" => "MDA",
            "label" => "Moldova"
        ],
        [
            "value" => "SGP",
            "label" => "Singapore"
        ],
        [
            "value" => "POL",
            "label" => "Poland"
        ],
        [
            "value" => "LVA",
            "label" => "Latvia"
        ],
        [
            "value" => "LTU",
            "label" => "Lithuania"
        ],
        [
            "value" => "NOR",
            "label" => "Norway"
        ],
        [
            "value" => "CZE",
            "label" => "the Czech Republic"
        ],
        [
            "value" => "PRY",
            "label" => "Paraguay"
        ],
        [
            "value" => "BOL",
            "label" => "Bolivia"
        ],
        [
            "value" => "CHE",
            "label" => "Switzerland"
        ],
        [
            "value" => "PRT",
            "label" => "Portugal"
        ],
        [
            "value" => "CUB",
            "label" => "Cuba"
        ],
        [
            "value" => "BHS",
            "label" => "The Bahamas"
        ],
        [
            "value" => "HTI",
            "label" => "Haiti"
        ],
        [
            "value" => "CUW",
            "label" => "Curaçao"
        ],
        [
            "value" => "DOM",
            "label" => "Dominican Republic"
        ],
        [
            "value" => "BLR",
            "label" => "Belarus"
        ],
        [
            "value" => "CRI",
            "label" => "Costa Rica"
        ],
        [
            "value" => "BRN",
            "label" => "Brunei"
        ],
        [
            "value" => "HND",
            "label" => "Honduras"
        ],
        [
            "value" => "MAF",
            "label" => "Saint Martin"
        ],
        [
            "value" => "IRL",
            "label" => "Ireland"
        ],
        [
            "value" => "BES",
            "label" => "Bonaire, Sint Eustatius, and Saba"
        ],
        [
            "value" => "MEX",
            "label" => "Mexico"
        ],
        [
            "value" => "KOS",
            "label" => "Kosovo"
        ],
        [
            "value" => "AUS",
            "label" => "Australia"
        ],
        [
            "value" => "PNG",
            "label" => "Papua New Guinea"
        ],
        [
            "value" => "ASM",
            "label" => "American Samoa"
        ],
        [
            "value" => "NGA",
            "label" => "Nigeria"
        ],
        [
            "value" => "BEN",
            "label" => "Benin"
        ],
        [
            "value" => "MLI",
            "label" => "Mali"
        ],
        [
            "value" => "AGO",
            "label" => "Angola"
        ],
        [
            "value" => "COD",
            "label" => "the Democratic Republic of the Congo"
        ],
        [
            "value" => "SLE",
            "label" => "Sierra Leone"
        ],
        [
            "value" => "TGO",
            "label" => "Togo"
        ],
        [
            "value" => "CMR",
            "label" => "Cameroon"
        ],
        [
            "value" => "SYR",
            "label" => "Syria"
        ],
        [
            "value" => "NER",
            "label" => "Niger"
        ],
        [
            "value" => "COG",
            "label" => "the Republic of the Congo"
        ],
        [
            "value" => "KHM",
            "label" => "Cambodia"
        ],
        [
            "value" => "GHA",
            "label" => "Ghana"
        ],
        [
            "value" => "MYT",
            "label" => "Mayotte"
        ],
        [
            "value" => "CAF",
            "label" => "Central African Republic"
        ],
        [
            "value" => "NFK",
            "label" => "Norfolk Island"
        ],
        [
            "value" => "MNP",
            "label" => "the Northern Mariana Islands"
        ],
        [
            "value" => "TLS",
            "label" => "Timor-Leste"
        ],
        [
            "value" => "GUM",
            "label" => "Guam"
        ],
        [
            "value" => "ALB",
            "label" => "Albania"
        ],
        [
            "value" => "NZL",
            "label" => "New Zealand"
        ],
        [
            "value" => "NCL",
            "label" => "New Caledonia"
        ],
        [
            "value" => "VUT",
            "label" => "Vanuatu"
        ],
        [
            "value" => "LBR",
            "label" => "Liberia"
        ],
        [
            "value" => "CIV",
            "label" => "Côte d’Ivoire"
        ],
        [
            "value" => "CXR",
            "label" => "Christmas Island"
        ],
        [
            "value" => "TUN",
            "label" => "Tunisia"
        ],
        [
            "value" => "BFA",
            "label" => "Burkina Faso"
        ],
        [
            "value" => "SLB",
            "label" => "the Solomon Islands"
        ],
        [
            "value" => "NAM",
            "label" => "Namibia"
        ],
        [
            "value" => "NRU",
            "label" => "Nauru"
        ],
        [
            "value" => "PYF",
            "label" => "French Polynesia"
        ],
        [
            "value" => "TCA",
            "label" => "the Turks and Caicos Islands"
        ],
        [
            "value" => "MSR",
            "label" => "Montserrat"
        ],
        [
            "value" => "ABW",
            "label" => "Aruba"
        ],
        [
            "value" => "SMR",
            "label" => "San Marino"
        ],
        [
            "value" => "LIE",
            "label" => "Liechtenstein"
        ],
        [
            "value" => "PRK",
            "label" => "North Korea"
        ],
        [
            "value" => "MNE",
            "label" => "Montenegro"
        ],
        [
            "value" => "GAB",
            "label" => "Gabon"
        ],
        [
            "value" => "GNQ",
            "label" => "Equatorial Guinea"
        ],
        [
            "value" => "COK",
            "label" => "Cook Islands"
        ],
        [
            "value" => "LSO",
            "label" => "Lesotho"
        ],
        [
            "value" => "CPV",
            "label" => "Cabo Verde"
        ],
        [
            "value" => "FJI",
            "label" => "Fiji"
        ],
        [
            "value" => "STP",
            "label" => "Sao Tome and Principe"
        ],
        [
            "value" => "SWZ",
            "label" => "Eswatini"
        ],
        [
            "value" => "TUV",
            "label" => "Tuvalu"
        ],
        [
            "value" => "PLW",
            "label" => "Palau"
        ],
        [
            "value" => "FSM",
            "label" => "the Federated States of Micronesia"
        ],
        [
            "value" => "BLM",
            "label" => "Saint Barthelemy"
        ],
        [
            "value" => "WSM",
            "label" => "Samoa"
        ],
        [
            "value" => "SXM",
            "label" => "Sint Maarten"
        ],
        [
            "value" => "WLF",
            "label" => "Wallis and Futuna"
        ],
        [
            "value" => "SLV",
            "label" => "El Salvador"
        ],
        [
            "value" => "EST",
            "label" => "Estonia"
        ],
        [
            "value" => "MHL",
            "label" => "the Marshall Islands"
        ],
        [
            "value" => "SVK",
            "label" => "Slovakia"
        ],
        [
            "value" => "VGB",
            "label" => "the British Virgin Islands"
        ],
        [
            "value" => "CYM",
            "label" => "Cayman Islands"
        ],
        [
            "value" => "PRI",
            "label" => "Puerto Rico"
        ],
        [
            "value" => "SRB",
            "label" => "Serbia"
        ],
        [
            "value" => "GIB",
            "label" => "Gibraltar"
        ],
        [
            "value" => "MCO",
            "label" => "Monaco"
        ],
        [
            "value" => "LUX",
            "label" => "Luxembourg"
        ],
        [
            "value" => "VIR",
            "label" => "the Virgin Islands"
        ],
        [
            "value" => "GGY",
            "label" => "Guernsey"
        ],
        [
            "value" => "JEY",
            "label" => "Jersey"
        ],
        [
            "value" => "COM",
            "label" => "Comoros"
        ],
        [
            "value" => "MDV",
            "label" => "the Maldives"
        ],
        [
            "value" => "KIR",
            "label" => "Kiribati"
        ],
        [
            "value" => "NIU",
            "label" => "Niue"
        ],
        [
            "value" => "CCK",
            "label" => "Cocos (Keeling) Islands"
        ],
        [
            "value" => "AIA",
            "label" => "Anguilla"
        ],
        [
            "value" => "SJM",
            "label" => "Svalbard"
        ],
        [
            "value" => "SHN",
            "label" => "Saint Helena, Ascension, and Tristan da Cunha"
        ],
        [
            "value" => "TKL",
            "label" => "Tokelau"
        ],
        [
            "value" => "BMU",
            "label" => "Bermuda"
        ],
        [
            "value" => "FRO",
            "label" => "Faroe Islands"
        ],
        [
            "value" => "TON",
            "label" => "Tonga"
        ],
        [
            "value" => "KNA",
            "label" => "Saint Kitts and Nevis"
        ],
        [
            "value" => "GRL",
            "label" => "Greenland"
        ],
        [
            "value" => "AND",
            "label" => "Andorra"
        ],
        [
            "value" => "IMN",
            "label" => "Isle of Man"
        ],
        [
            "value" => "PCN",
            "label" => "the Pitcairn Islands"
        ],
        [
            "value" => "URY",
            "label" => "Uruguay"
        ]
    ];

    usort($countries, function($a, $b) {
        return strcmp($a['label'], $b['label']);
    });

    return $countries;
}

/**
 * Load api functions
 */
require_once get_template_directory() . '/functions/contact-rest-api.php';
require_once get_template_directory() . '/functions/adopt-rest-api.php';

function doxa_load_theme_textdomain() {
    load_theme_textdomain('doxa-website', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'doxa_load_theme_textdomain');


function doxa_translation_url( $slug, $lang_code = null ) {
    $post_id = doxa_get_page_id_by_slug( $slug );

    if ( empty( $lang_code ) ) {
        $lang_code = doxa_get_language_code();
    }

    $translation_page = get_translation_page( $post_id, $lang_code );

    return get_permalink( $translation_page->ID );
}
function get_translation_page( $post_id, $lang_code = null ) {
    $list = doxa_language_relationships( $post_id );

    $trans_id = $list[$lang_code] ?? $post_id;

    $post = get_post( $trans_id, OBJECT );

    return $post;
}
function doxa_get_page_id_by_slug( $slug ) {
    $post = get_page_by_path( $slug, OBJECT, 'page' );
    if ( empty( $post ) ) {
        return null;
    }
    return $post->ID;
}

function doxa_language_relationships( $post_id ) {
    global $wpdb, $table_prefix;

    $like_text = '%' . $wpdb->esc_like( 'i:' . $post_id . ';' ) . '%';
    $list = $wpdb->get_var( $wpdb->prepare(
        "
			SELECT description
			FROM {$table_prefix}term_taxonomy tr
            WHERE tr.description LIKE %s AND tr.taxonomy = 'post_translations';
		",
        $like_text
    ) );
    return maybe_unserialize( $list );
}

function doxa_get_language_code() {
    return function_exists( 'pll_current_language' ) ? pll_current_language() : substr( get_locale(), 0, 2 );
}

add_filter( 'pll_the_language_link', 'doxa_remove_langs_link', 10, 3 );
function doxa_remove_langs_link( $url, $slug, $locale ) {
    $languages_to_remove = get_option( 'doxa_languages_to_remove' );
    if ( !$languages_to_remove ) {
        return $url;
    }
    $languages_to_remove = explode( ',', $languages_to_remove );
    if ( in_array( $locale, $languages_to_remove ) ) {
        return null;
    }
    return $url;
}

function doxa_get_video_urls() {
    return [
        'fr' => 'https://player.vimeo.com/video/1174779547?h=8c00c1c764&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479',
        'en' => 'https://player.vimeo.com/video/1143355099?h=39f8c1f131&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479',
    ];
}

function doxa_get_video_url( $lang_code = null ) {
    if ( empty( $lang_code ) ) {
        $lang_code = doxa_get_language_code();
    }

    $video_urls = doxa_get_video_urls();

    return $video_urls[$lang_code] ?? $video_urls['en'];
}

function doxa_has_video_translation( $lang_code = null ) {
    if ( empty( $lang_code ) ) {
        $lang_code = doxa_get_language_code();
    }

    $video_urls = doxa_get_video_urls();

    return isset( $video_urls[$lang_code] );
}

function doxa_resources_translations_manifest() {
    $available_languages = [
        'doxa-playbook'          => ['en'],
        'doxa-playbook-slides'   => ['en'],
        'introduction-2025'      => ['en'],
        'vision-and-values'      => ['en'],
        'definitions'            => ['en'],
        'doxa-endowment-policy'  => ['en'],
        'initial-proposal'       => ['en', 'fr', 'es'],
    ];

    return $available_languages;
}

function doxa_get_s3_lang_code( $document_key ) {
    $available_languages = doxa_resources_translations_manifest();

    $lang_code = doxa_get_language_code();
    $langs = $available_languages[$document_key] ?? ['en'];

    return in_array($lang_code, $langs, true) ? $lang_code : 'en';
}

function doxa_has_document_translation( $document_key ) {
    $lang_code = doxa_get_language_code();
    $available_languages = doxa_resources_translations_manifest();
    $langs = $available_languages[$document_key] ?? ['en'];

    $in_array = in_array($lang_code, $langs, true);
    return $in_array;
}
