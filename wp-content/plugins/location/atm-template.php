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
    flex: 2;
    min-width: 400px;
    min-height: 500px;
    height: 100vh;
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

#atm-search {
    width: 100%;
    font-size: 16px;
    padding: 12px 20px;
    border: 1px solid #ddd;
    margin-bottom: 12px;
    border-radius: 6px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease;
    color: black;
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
    background-color: rgb(2, 253, 15);
    border-color: #0073e6;
    transform: translateY(-2px);
}

.atm-item.active {
    background-color: #ffcccc;
    border-color: #ff0000;
    color: #900000;
    font-weight: bold;
}

@media (max-width: 768px) {
    #atm-container {
        flex-direction: column;
    }

    #atm-map-view {
        width: 100%;
        height: 300px;
    }

    #atm-list {
        width: 100%;
        height: auto;
    }
}
</style>


<div id="atm-container">
    <div id="atm-list">
        <input type="text" id="atm-search" placeholder="Search for ATM names..." title="Type in an ATM name">
        <ul id="atm-ul">
            <?php foreach ($atms as $atm): ?>
                <?php
                    $lat = floatval(get_post_meta($atm->ID, '_atm_latitude', true));
                    $lng = floatval(get_post_meta($atm->ID, '_atm_longitude', true));
                ?>
                <li class="atm-item" data-lat="<?= esc_attr($lat) ?>" data-lng="<?= esc_attr($lng) ?>" data-id="<?= $atm->ID ?>">
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
document.addEventListener('DOMContentLoaded', function () {
    const atmItems = document.querySelectorAll('.atm-item');
    const mapDiv = document.getElementById('atm-map-content');
    const searchInput = document.getElementById('atm-search');

    atmItems.forEach(function (el) {
        el.addEventListener('click', function () {
            // Remove 'active' class from all
            atmItems.forEach(item => item.classList.remove('active'));
            // Add to clicked one
            el.classList.add('active');

            // Update the map
            const lat = el.dataset.lat;
            const lng = el.dataset.lng;
            mapDiv.innerHTML = `<iframe src="https://maps.google.com/maps?q=${lat},${lng}&hl=en&z=14&amp;output=embed" loading="lazy" allowfullscreen></iframe>`;
        });
    });

    // Filter the list
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toUpperCase();
        atmItems.forEach(function (item) {
            const text = item.textContent || item.innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
