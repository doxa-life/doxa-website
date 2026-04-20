# 🗺️ doxa-map — WordPress Integration

> The **built bundle** of the doxa-map web component lives in this folder. This guide shows how to embed a map on a WordPress page.
> The bundle is built from a separate map-framework repo that includes the starter template used to create this map.

![Architecture](./template-architecture.svg)

---

## 1. The Idea — Hourglass Architecture

**One template. One build. Many instances.**

- Every map "kind" is an **application profile** — one Vue file in the template's `app-profiles/` folder (e.g. `doxa-simple-map.vue`, `world-map.vue`, `dr-research-map.vue`).
- `npm run build` packs **all** application profiles into **one** bundle: `map-app.iife.js` + `map-app.css`.
- A page mounts a map with a single HTML tag. A JSON prop (`profile-config`) tells that tag **which** application profile to load and **how** to configure it.

One bundle → many instances on many sites (this WordPress theme, a staging preview, anywhere else that hosts an HTML hook).

> 📍 The bundle is built in a separate map-framework repo. Its Vite config auto-copies the built output into this folder on every build — no manual copy step.

---

## 2. Files in This Folder

| File | Role |
|---|---|
| `map-app.iife.js`            | The bundled web component — every application profile inside |
| `map-app.css`                | Shadow-DOM styles (internal to the component) |
| `map-app-slot.css`           | Wrapper sizing — the `.doxa-map-slot` class |
| `template-architecture.svg`  | The hourglass diagram at the top |
| `architecture.svg`           | Copy of the diagram (same content) |
| `map-app-integration.md`     | This file |

---

## 3. How to Embed a Map

Drop one block into any page template:

```php
<div class="doxa-map-slot rounded-md">
    <doxa-map
        id="pray-map"
        profile-config="<?php echo esc_attr( wp_json_encode( [
            'profile'    => 'doxa-simple-map',
            'instanceId' => 'pray-map',
            'dataSource' => 'pray-tools',
            'tk'         => defined( 'MAPBOX_PUBLIC_TOKEN' ) ? MAPBOX_PUBLIC_TOKEN : '',
            'tabs'       => [ [
                'id'            => 'prayer',
                'label'         => 'Prayer',
                'colorStrategy' => 'prayer',
                'legend'        => 'prayer',
                'popup'         => 'prayer',
            ] ],
        ] ) ); ?>"
    ></doxa-map>
</div>
```

Two pieces do all the work:

1. **`<div class="doxa-map-slot">`** — the wrapper that owns the **size and shape** of the map box.
2. **`<doxa-map profile-config="...">`** — the web component itself, configured entirely by the JSON prop.

---

## 4. `profile-config` — Parent Prop + Sub-Props

The JSON has a few **parent fields** and one array of **sub-props** (the `tabs`).

### Parent fields

| Field          | What It Picks |
|----------------|---|
| `profile`      | Which **application profile** to mount (which Vue file from `app-profiles/`) |
| `dataSource`   | Which data to load (`pray-tools`, `doxa-api`, `doxa-csv`…) |
| `instanceId`   | Unique id per rendered map — scopes its Pinia stores so maps don't share state |
| `tk`           | Mapbox public token (from `wp-config.php`) |
| `tabs`         | Array of sub-props — see below |

### Sub-props — one object per tab

| Field           | What It Picks |
|-----------------|---|
| `id`            | Internal tab id |
| `label`         | Button text on the tab |
| `colorStrategy` | Pin colors on the map |
| `legend`        | Which legend component renders |
| `popup`         | Which popup content renders when a pin is clicked |

**Rule:** 1 tab → no tab bar. 2+ tabs → tab bar at the top of the map.

---

## 5. The Slot Wrapper — Sizing

`map-app-slot.css` is a tiny CSS file. Its only job is to define the `.doxa-map-slot` class, which decides the **size and shape of the map box**:

- **Desktop:** 16:9 aspect ratio
- **Mobile (≤768px):** 1:2 portrait
- Always fills the parent's width

Optional corner-rounding classes: `rounded-md`, `rounded-xlg`.

Need a different size on a specific page? Override with an inline style on the wrapper:

```html
<div class="doxa-map-slot" style="aspect-ratio: 16/7;">
    <doxa-map ...></doxa-map>
</div>
```

If the map looks wrong-sized anywhere, the fix is in `map-app-slot.css` — not in the Vue app, not in the theme CSS.

---

## 6. Mapbox Token

Never in the repo. Lives in `wp-config.php`:

```php
define( 'MAPBOX_PUBLIC_TOKEN', 'pk.eyJ...' );
```

Pass through in any page template:

```php
'tk' => defined( 'MAPBOX_PUBLIC_TOKEN' ) ? MAPBOX_PUBLIC_TOKEN : '',
```

---

## 7. Adding a New Map Page

1. Make the PHP template (e.g. `page-joshua.php`)
2. Drop in the HTML block from section 3 — unique `id` + matching `instanceId`
3. Add the page slug to `doxa_map_app_scripts()` in `functions.php`:
   ```php
   $is_map_page = is_front_page() || is_page('pray') || is_page('adopt') || is_page('joshua');
   ```
4. Done. No JS or CSS changes needed.

---

## 8. Modifying the Template

To change what the map does, add a new application profile, or preview changes live, you'll need access to the separate map-framework repo that houses the source and the starter template. Ask a maintainer.
