(function () {
  var config = window.prayerMapConfig || {};
  var mapboxToken = config.mapboxToken || '';
  var prayBaseUrl = config.prayBaseUrl || 'https://pray.doxa.life';
  var researchUrl = config.researchUrl || '/research';
  var languageCode = config.languageCode || 'en';
  var t = config.t || {};
  var apiUrl = prayBaseUrl + '/api/people-groups/list?fields=slug,name,latitude,longitude,people_committed,population,image_url,country_code,primary_language&lang=' + languageCode;

  var COLOR_NO_PRAYER = '#e57373';
  var COLOR_HAS_PRAYER = '#4caf50';

  var allFeatures = [];

  var container = document.getElementById('prayer-map');
  if (!container || !mapboxToken) return;

  mapboxgl.accessToken = mapboxToken;

  var map = new mapboxgl.Map({
    container: 'prayer-map',
    style: 'mapbox://styles/mapbox/light-v11',
    projection: 'mercator',
    center: [20, 10],
    zoom: 1.5,
    minZoom: 1,
    maxZoom: 12,
  });

  map.addControl(new mapboxgl.NavigationControl(), 'top-right');
  map.scrollZoom.disable();

  container.addEventListener('click', function () {
    map.scrollZoom.enable();
  });

  container.addEventListener('mouseleave', function () {
    map.scrollZoom.disable();
  });

  // Build legend
  var legend = document.createElement('div');
  legend.className = 'prayer-map-legend';
  legend.innerHTML =
    '<div class="prayer-map-legend__item">' +
      '<span class="prayer-map-legend__dot" style="background:' + COLOR_NO_PRAYER + '"></span>' +
      '<span>' + (t.no_prayer || 'No prayer coverage') + '</span>' +
    '</div>' +
    '<div class="prayer-map-legend__item">' +
      '<span class="prayer-map-legend__dot" style="background:' + COLOR_HAS_PRAYER + '"></span>' +
      '<span>' + (t.has_prayer || 'Has prayer coverage') + '</span>' +
    '</div>';
  container.appendChild(legend);

  // Build search
  var searchWrap = document.createElement('div');
  searchWrap.className = 'prayer-map-search';
  searchWrap.innerHTML =
    '<input class="prayer-map-search__input" type="search" placeholder="' + (t.search_placeholder || 'Search people groups or locations') + '" autocomplete="off">' +
    '<div class="prayer-map-search__results"></div>';
  container.appendChild(searchWrap);

  var searchInput = searchWrap.querySelector('.prayer-map-search__input');
  var searchResults = searchWrap.querySelector('.prayer-map-search__results');
  var debounceTimer = null;
  var geocodeController = null;

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function closeSearch() {
    searchResults.innerHTML = '';
    searchResults.style.display = 'none';
  }

  function showResults(items) {
    if (items.length === 0) {
      closeSearch();
      return;
    }
    searchResults.innerHTML = items.map(function (item) {
      return '<button class="prayer-map-search__item" data-type="' + item.type + '" data-index="' + (item.index != null ? item.index : '') + '" data-lng="' + (item.lng || '') + '" data-lat="' + (item.lat || '') + '">' +
        '<span class="prayer-map-search__item-name">' + escapeHtml(item.name) + '</span>' +
        (item.sub ? '<span class="prayer-map-search__item-sub">' + escapeHtml(item.sub) + '</span>' : '') +
      '</button>';
    }).join('');
    searchResults.style.display = 'block';
  }

  searchResults.addEventListener('click', function (e) {
    var btn = e.target.closest('.prayer-map-search__item');
    if (!btn) return;

    var type = btn.getAttribute('data-type');
    if (type === 'people') {
      var idx = parseInt(btn.getAttribute('data-index'), 10);
      var feature = allFeatures[idx];
      if (feature) {
        var coords = feature.geometry.coordinates;
        map.flyTo({ center: coords, zoom: 8 });
        highlightFeature(feature);
      }
    } else {
      var lng = parseFloat(btn.getAttribute('data-lng'));
      var lat = parseFloat(btn.getAttribute('data-lat'));
      if (!isNaN(lng) && !isNaN(lat)) {
        map.flyTo({ center: [lng, lat], zoom: 5 });
      }
    }

    searchInput.value = '';
    closeSearch();
  });

  searchInput.addEventListener('input', function () {
    var query = searchInput.value.trim();
    clearTimeout(debounceTimer);
    if (geocodeController) {
      geocodeController.abort();
      geocodeController = null;
    }

    if (query.length < 2) {
      closeSearch();
      return;
    }

    debounceTimer = setTimeout(function () {
      var lower = query.toLowerCase();
      var peopleResults = [];
      for (var i = 0; i < allFeatures.length && peopleResults.length < 5; i++) {
        var name = allFeatures[i].properties.name;
        if (name && name.toLowerCase().indexOf(lower) !== -1) {
          peopleResults.push({
            type: 'people',
            name: allFeatures[i].properties.name,
            sub: allFeatures[i].properties.country || '',
            index: i,
          });
        }
      }

      if (peopleResults.length >= 2) {
        showResults(peopleResults);
        return;
      }

      geocodeController = new AbortController();
      var geocodeUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' +
        encodeURIComponent(query) + '.json?access_token=' + mapboxToken + '&limit=3';

      fetch(geocodeUrl, { signal: geocodeController.signal })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          var locationResults = (data.features || []).map(function (f) {
            return {
              type: 'location',
              name: f.place_name,
              lng: f.center[0],
              lat: f.center[1],
            };
          });
          showResults(peopleResults.concat(locationResults));
        })
        .catch(function (err) {
          if (err.name !== 'AbortError') {
            showResults(peopleResults);
          }
        });
    }, 300);
  });

  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      searchInput.value = '';
      closeSearch();
      searchInput.blur();
    }
  });

  document.addEventListener('click', function (e) {
    if (!searchWrap.contains(e.target)) {
      closeSearch();
    }
  });

  // Build modal overlay (hidden by default)
  var overlay = document.createElement('div');
  overlay.className = 'prayer-map-overlay';
  overlay.innerHTML =
    '<div class="prayer-map-modal" role="dialog">' +
      '<button class="prayer-map-modal__close" aria-label="' + (t.close || 'Close') + '">&times;</button>' +
      '<img class="prayer-map-modal__image" src="" alt="">' +
      '<div class="prayer-map-modal__body stack stack--md">' +
        '<h3 class="prayer-map-modal__name"></h3>' +
        '<div class="prayer-map-modal__details"></div>' +
        '<div class="prayer-map-modal__actions">' +
          '<a id="prayer-map-modal__btn-pray" class="button" href="#" target="_blank">' + (t.pray_for_them || 'Pray for them') + '</a>' +
          '<a id="prayer-map-modal__btn-info" class="button outline" href="#" target="_blank">' + (t.info || 'Info') + '</a>' +
        '</div>' +
      '</div>' +
    '</div>';
  document.body.appendChild(overlay);

  var modalImage = overlay.querySelector('.prayer-map-modal__image');
  var modalName = overlay.querySelector('.prayer-map-modal__name');
  var modalDetails = overlay.querySelector('.prayer-map-modal__details');
  var btnPray = overlay.querySelector('#prayer-map-modal__btn-pray');
  var btnInfo = overlay.querySelector('#prayer-map-modal__btn-info');

  function closeModal() {
    overlay.classList.remove('is-visible');
  }

  overlay.querySelector('.prayer-map-modal__close').addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });

  function formatNumber(n) {
    if (n == null) return t.unknown || 'Unknown';
    return Number(n).toLocaleString();
  }

  var highlightedSlug = null;

  function highlightFeature(feature) {
    highlightedSlug = feature.properties.slug;
    map.setPaintProperty('people-groups-dots', 'circle-color', [
      'case',
      ['==', ['get', 'slug'], highlightedSlug],
      '#ff9800',
      ['==', ['get', 'hasPrayer'], 1],
      COLOR_HAS_PRAYER,
      COLOR_NO_PRAYER,
    ]);
    map.setPaintProperty('people-groups-dots', 'circle-radius', [
      'case',
      ['==', ['get', 'slug'], highlightedSlug],
      12,
      ['interpolate', ['linear'], ['zoom'], 1, 3, 5, 5, 10, 8],
    ]);
  }

  function clearHighlight() {
    if (!highlightedSlug) return;
    highlightedSlug = null;
    map.setPaintProperty('people-groups-dots', 'circle-color', [
      'case',
      ['==', ['get', 'hasPrayer'], 1],
      COLOR_HAS_PRAYER,
      COLOR_NO_PRAYER,
    ]);
    map.setPaintProperty('people-groups-dots', 'circle-radius', [
      'interpolate', ['linear'], ['zoom'],
      1, 3, 5, 5, 10, 8,
    ]);
  }

  map.on('click', function (e) {
    if (highlightedSlug) {
      var features = map.queryRenderedFeatures(e.point, { layers: ['people-groups-dots'] });
      if (!features.length || features[0].properties.slug !== highlightedSlug) {
        clearHighlight();
      }
    }
  });

  function openModal(props) {
    var fallbackImage = prayBaseUrl + '/images/default-people-group.jpg';
    modalImage.src = props.picture_url || fallbackImage;
    modalImage.alt = props.name;
    modalName.textContent = props.name;
    modalDetails.innerHTML =
      '<span><strong>' + (t.language || 'Language') + ':</strong> ' + (props.language || t.unknown || 'Unknown') + '</span>' +
      '<span><strong>' + (t.country || 'Country') + ':</strong> ' + (props.country || t.unknown || 'Unknown') + '</span>' +
      '<span><strong>' + (t.population || 'Population') + ':</strong> ' + formatNumber(props.population) + '</span>' +
      '<span><strong>' + (t.prayer_coverage || 'Prayer Coverage') + ':</strong> ' + (props.people_committed || 0) + '/144</span>';
    btnPray.href = prayBaseUrl + '/' + props.slug + '?source=doxalife';
    btnInfo.href = researchUrl.replace(/\/+$/, '') + '/' + props.slug;
    overlay.classList.add('is-visible');
  }

  map.on('load', function () {
    fetch(apiUrl)
      .then(function (res) { return res.json(); })
      .then(function (data) {
        var posts = data.posts || [];

        var features = [];
        for (var i = 0; i < posts.length; i++) {
          var p = posts[i];
          var lat = parseFloat(p.latitude);
          var lng = parseFloat(p.longitude);
          if (isNaN(lat) || isNaN(lng)) continue;

          features.push({
            type: 'Feature',
            geometry: {
              type: 'Point',
              coordinates: [lng, lat],
            },
            properties: {
              slug: p.slug,
              name: p.name,
              people_committed: p.people_committed,
              population: p.population,
              language: p.primary_language ? p.primary_language.label : null,
              country: p.country_code ? p.country_code.label : null,
              picture_url: p.image_url,
              hasPrayer: p.people_committed > 0 ? 1 : 0,
            },
          });
        }

        allFeatures = features;

        addSourceAndLayer({
          type: 'FeatureCollection',
          features: features,
        });
      })
      .catch(function (err) {
        console.error('Prayer map: failed to load people groups', err);
      });
  });

  function addSourceAndLayer(geojson) {
    if (map.getSource('people-groups')) return;

    map.addSource('people-groups', {
      type: 'geojson',
      data: geojson,
    });

    map.addLayer({
      id: 'people-groups-dots',
      type: 'circle',
      source: 'people-groups',
      paint: {
        'circle-radius': [
          'interpolate', ['linear'], ['zoom'],
          1, 3,
          5, 5,
          10, 8,
        ],
        'circle-color': [
          'case',
          ['==', ['get', 'hasPrayer'], 1],
          COLOR_HAS_PRAYER,
          COLOR_NO_PRAYER,
        ],
        'circle-opacity': 0.85,
        'circle-stroke-width': 1,
        'circle-stroke-color': '#ffffff',
        'circle-stroke-opacity': 0.5,
      },
    });

    map.on('click', 'people-groups-dots', function (e) {
      if (e.features && e.features.length > 0) {
        openModal(e.features[0].properties);
      }
    });

    map.on('mouseenter', 'people-groups-dots', function () {
      map.getCanvas().style.cursor = 'pointer';
    });

    map.on('mouseleave', 'people-groups-dots', function () {
      map.getCanvas().style.cursor = '';
    });
  }
})();
