<?php
$first_atm = $atms[0];
$first_lat = get_post_meta($first_atm->ID, '_atm_latitude', true);
$first_lng = get_post_meta($first_atm->ID, '_atm_longitude', true);
$first_iframe = "<iframe src=\"https://maps.google.com/maps?q={$first_lat},{$first_lng}&hl=en&z=14&amp;output=embed\" loading=\"lazy\" allowfullscreen></iframe>";
?>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #atm-container {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        flex-wrap: wrap;
    }

    #atm-list {
        flex: 1 1 500px;
        max-width: 600px;
        height: 100vh;
        overflow-y: auto;
        padding: 1rem;
        background-color: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    #atm-map-view {
        flex: 0 0 400px;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        border: 1px solid #ddd;
        background-color: white;
    }

    #atm-map-content iframe {
        width: 100%;
        height: 100%;
        border: none;
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
        user-select: none;
    }

    .atm-item:hover {
        background-color: #e6f2ff;
        border-color: #0073e6;
        transform: translateY(-2px);
    }

    /* Red highlight for the last clicked item */
    .atm-item.active {
        background-color: #28a745;
        border-color: #28a745;
        color:rgb(255, 255, 255);
        font-weight: bold;
    }

    .nearest-atm {
        background-color: #dff0d8 !important;
        border: 2px solid #28a745 !important;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        #atm-container {
            flex-direction: column;
        }

        #atm-map-view {
            width: 100%;
        }

        #atm-list {
            width: 100%;
            height: auto;
        }
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
    const R = 6371.0088;
    const toRad = angle => angle * Math.PI / 180;

    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const lat1Rad = toRad(lat1);
    const lat2Rad = toRad(lat2);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1Rad) * Math.cos(lat2Rad) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

document.addEventListener('DOMContentLoaded', function () {
    const atmItems = document.querySelectorAll('.atm-item');
    const findBtn = document.getElementById('find-nearest');
    const mapDiv = document.getElementById('atm-map-content');

    atmItems.forEach(function (el) {
        el.addEventListener('click', function () {
            // Remove 'active' class from all items first
            atmItems.forEach(item => item.classList.remove('active'));

            // Add 'active' class to the clicked item
            el.classList.add('active');

            // Change the map iframe
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
                    alert(`✅ Got your location!\nLat: ${userLat}\nLng: ${userLng}`);

                    let nearest = null;
                    let nearestDist = Infinity;

                    atmItems.forEach(el => {
                        const lat = parseFloat(el.dataset.lat);
                        const lng = parseFloat(el.dataset.lng);
                        const dist = haversine(userLat, userLng, lat, lng);

                        alert(`📡 Checking "${el.textContent.trim()}"\nDistance: ${dist.toFixed(8)} km`);
                        el.classList.remove("nearest-atm");

                        if (dist < nearestDist) {
                            nearestDist = dist;
                            nearest = el;
                        }
                    });

                    if (nearest) {
                        const lat = nearest.dataset.lat;
                        const lng = nearest.dataset.lng;

                        mapDiv.innerHTML = `<iframe src="https://maps.google.com/maps?q=${lat},${lng}&hl=en&z=14&amp;output=embed" loading="lazy" allowfullscreen></iframe>`;

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
    }
});
</script>
