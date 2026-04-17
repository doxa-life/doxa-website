<?php
/**
 * Partial: Shadow DOM Slot
 *
 * Renders a <doxa-map> custom element — the registered Web Component whose
 * Shadow DOM hosts and isolates the Vue micro-frontend map application.
 *
 * WHY <doxa-map> IS ALWAYS THE TAG:
 *   entry.js calls customElements.define('doxa-map', DoxaMapElement).
 *   Only that exact tag name is registered with the browser. Any other tag
 *   is treated as an unknown element and Vue never mounts into it.
 *   Differentiation between map instances is done via the profile-config JSON
 *   attribute — not by changing the tag name.
 *
 * WHY NO <script> INSIDE THE ELEMENT:
 *   Web Component best practice (web.dev/articles/custom-elements-best-practices)
 *   says element children are light-DOM slot content, not bootstrap logic.
 *   Script loading belongs in wp_enqueue_script() — see functions.php where
 *   doxa_map_scripts() enqueues map-app.iife.js on templates that need it.
 *
 * HOW SIZING WORKS:
 *   The host element (<doxa-map>) is a block-level box sized by .shadow-dom-slot
 *   in _shadow-dom-slot.scss. aspect-ratio drives height by default; explicit
 *   height_desktop / height_mobile args override it via CSS custom properties.
 *   overflow:hidden on the host clips the Shadow DOM canvas to the host's
 *   border-radius — no styles need to cross the shadow boundary.
 *
 * HOW ROUNDED EDGES WORK:
 *   radius arg maps to the theme's existing utility classes (.rounded-md,
 *   .rounded-xlg) which use the same --border-radius-* tokens as sibling
 *   cards and images. The slot is visually indistinguishable from them.
 *
 * USAGE (in any page template):
 *
 *   <?php get_template_part( 'partials/shadow-dom-slot', null, [
 *
 *       // ── Map identity (required) ────────────────────────────────────────
 *       'profile'        => 'doxa-simple-map',  // matches src/app-profiles/*.vue filename
 *       'tk'             => 'pk.eyJ...',         // Mapbox public token
 *
 *       // ── Instance identity (recommended) ───────────────────────────────
 *       'instance_id'    => 'pray-map',          // scopes window events; prevents cross-talk
 *                                                //   when multiple <doxa-map> on same page
 *
 *       // ── Optional map config ────────────────────────────────────────────
 *       'data_source'    => 'pray-tools',        // default: 'doxa-csv'
 *       'color_set'      => 'default',           // color theme key
 *       'tabs'           => [],                  // tab config array for multi-tab profiles
 *
 *       // ── Slot sizing ────────────────────────────────────────────────────
 *       'radius'         => 'md',                // 'none' | 'md' | 'xlg'
 *       'aspect_ratio'   => '16/7',              // CSS aspect-ratio (default sizing mode)
 *       'height_desktop' => '',                  // e.g. '480px' — overrides aspect-ratio
 *       'height_mobile'  => '',                  // e.g. '320px' — overrides aspect-ratio
 *
 *   ] ); ?>
 */

// ── Required: map identity ───────────────────────────────────────────────────
$profile     = $args['profile'] ?? 'doxa-simple-map';
$tk          = $args['tk']      ?? '';

// ── Optional: instance + data config ─────────────────────────────────────────
$instance_id = $args['instance_id'] ?? ( 'doxa-map-' . sanitize_title( $profile ) );
$data_source = $args['data_source'] ?? null;
$color_set   = $args['color_set']   ?? null;
$tabs        = $args['tabs']        ?? null;

// ── Slot sizing ───────────────────────────────────────────────────────────────
$radius         = $args['radius']         ?? 'md';
$aspect_ratio   = $args['aspect_ratio']   ?? '16/7';
$height_desktop = $args['height_desktop'] ?? '';
$height_mobile  = $args['height_mobile']  ?? '';

// ── Build profile-config JSON ─────────────────────────────────────────────────
// ProfileLoader.vue reads this single attribute — it is the entire prop contract.
// null values are omitted so the micro-frontend can apply its own defaults.
$profile_config = array_filter( [
    'profile'    => $profile,
    'tk'         => $tk,
    'instanceId' => $instance_id,
    'dataSource' => $data_source,
    'colorSet'   => $color_set,
    'tabs'       => $tabs,
], fn( $v ) => $v !== null && $v !== '' );

// ── Radius class ──────────────────────────────────────────────────────────────
// Theme ships only .rounded-md (2.5rem) and .rounded-xlg (3.5rem).
$radius_map = [
    'none' => '',
    'md'   => 'rounded-md',
    'xlg'  => 'rounded-xlg',
];
$radius_class = $radius_map[ $radius ] ?? 'rounded-md';

// ── Inline CSS custom properties (sizing) ─────────────────────────────────────
$inline_style = '--sdslot-aspect-ratio:' . esc_attr( $aspect_ratio ) . ';';
if ( $height_desktop ) {
    $inline_style .= '--sdslot-height-desktop:' . esc_attr( $height_desktop ) . ';';
}
if ( $height_mobile ) {
    $inline_style .= '--sdslot-height-mobile:' . esc_attr( $height_mobile ) . ';';
}
?>

<doxa-map
    id="<?php echo esc_attr( $instance_id ); ?>"
    class="shadow-dom-slot <?php echo esc_attr( $radius_class ); ?>"
    style="<?php echo $inline_style; ?>"
    profile-config="<?php echo esc_attr( wp_json_encode( $profile_config ) ); ?>"
></doxa-map>
