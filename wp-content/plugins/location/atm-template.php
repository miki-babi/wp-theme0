<?php
$first_iframe = "<iframe src=\"https://maps.google.com/maps?q={$first_lat},{$first_lng}&hl=en&z=14&amp;output=embed\" loading=\"lazy\" allowfullscreen></iframe>";
?>

<style>
    #atm-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        padding: 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #atm-list {
        flex: 1 1 100%;
        max-width: 100%;
    }

    @media (min-width: 768px) {
        #atm-list {
            flex: 1 1 40%;
            max-width: 40%;
        }

        #atm-map-view {
            flex: 1 1 55%;
            max-width: 55%;
        }
    }

    #find-nearest {
        display: inline-block;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        background-color: #0073e6;
        color: #fff;
        font-size: 1rem;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #find-nearest:hover {
        background-color: #005bb5;
    }

    .atm-item {
        list-style: none;
        cursor: pointer;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        margin-bottom: 0.5rem;
        border-radius: 0.5rem;
        background-color: #f9f9f9;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .atm-item:hover {
        background-color: #e6f2ff;
        border-color: #0073e6;
        transform: translateY(-2px);
    }

    .nearest-atm {
        background-color: #dff0d8 !important;
        border: 2px solid #28a745 !important;
        font-weight: bold;
    }

    #atm-map-content iframe {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        height: 300px;
    }

    #atm-map-view {
        flex: 1 1 100%;
        max-width: 100%;
    }
</style>

<div id="atm-container">
    <div id="atm-list">
        <button id="find-nearest">Find Nearest ATM</button>
        <ul>
            <?php foreach ($atms as $atm): ?>
                <?php
                    $lat = floatval(get_post_meta($atm->ID, '_atm_latitude', true));
                    $lng = floatval(get_post_meta($atm->ID, '_atm_longitude', true));
                ?>
                <li data-lat="<?= esc_attr($lat) ?>" data-lng="<?= esc_attr($lng) ?>" data-id="<?= $atm->ID ?>" class="atm-item">
                    <?= esc_html($atm->post_title) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="atm-map-view">
        <div id="atm-map-content"><?= $first_iframe ?></div>
    </div>
</div>

<script>
function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371.0088; // Earth's radius in kilometers
    const toRad = angle => angle * Math.PI / 180;

    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const lat1Rad = toRad(lat1);
    const lat2Rad = toRad(lat2);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1Rad) * Math.cos(lat2Rad) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // distance in KM
}

document.addEventListener('DOMContentLoaded', function () {
    const atmItems = document.querySelectorAll('.atm-item');
    const findBtn = document.getElementById('find-nearest');
    const mapDiv = document.getElementById('atm-map-content');

    atmItems.forEach(function (el) {
        el.addEventListener('click', function () {
            const lat = el.dataset.lat;
            const lng = el.dataset.lng;
            if (mapDiv) {
                mapDiv.innerHTML = `<iframe src="https://maps.google.com/maps?q=${lat},${lng}&hl=en&z=14&amp;output=embed" loading="lazy" allowfullscreen></iframe>`;
            }
        });
    });

    if (findBtn) {
        findBtn.addEventListener('click', function () {
            alert("🔍 Step 1: Starting to find nearest ATM...");

            if (navigator.geolocation) {
                alert("📍 Step 2: Asking for your location...");

                navigator.geolocation.getCurrentPosition(function (position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    alert(`✅ Step 3: Got your location!\nLatitude: ${userLat}\nLongitude: ${userLng}`);

                    let nearest = null;
                    let nearestDist = Infinity;

                    atmItems.forEach(el => {
                        const lat = parseFloat(el.dataset.lat);
                        const lng = parseFloat(el.dataset.lng);
                        const dist = haversine(userLat, userLng, lat, lng);

                        alert(`📡 Checking ATM "${el.textContent.trim()}"\nLat: ${lat}, Lng: ${lng}\nDistance: ${dist.toFixed(8)} km`);

                        el.classList.remove("nearest-atm");

                        if (dist < nearestDist) {
                            nearestDist = dist;
                            nearest = el;
                        }
                    });

                    if (nearest) {
                        const lat = nearest.dataset.lat;
                        const lng = nearest.dataset.lng;

                        if (mapDiv) {
                            mapDiv.innerHTML = `<iframe src="https://maps.google.com/maps?q=${lat},${lng}&hl=en&z=14&amp;output=embed" loading="lazy" allowfullscreen></iframe>`;
                        }

                        nearest.classList.add("nearest-atm");
                        if (!nearest.innerHTML.includes("km")) {
                            nearest.innerHTML += ` <span style="font-size: 0.85rem; color: #666;">(${nearestDist.toFixed(10)} km)</span>`;
                        }

                        nearest.scrollIntoView({ behavior: 'smooth' });

                        alert(`🎯 Nearest ATM is "${nearest.textContent.trim()}"\nDistance: ${nearestDist.toFixed(10)} km`);
                    } else {
                        alert('❌ No nearby ATM found.');
                    }

                }, function (error) {
                    alert('⚠️ Geolocation error: ' + error.message);
                });
            } else {
                alert('🚫 Geolocation is not supported by your browser');
            }
        });
    } else {
        alert("❗ ERROR: 'Find Nearest ATM' button not found");
    }
});
</script>
