<?php
require '../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');

$dotenv->load();
$apiKey = $_ENV['API_KEY'];



require_once './views/header.php' ?>

<div class="container">
    <div class="left-part">
        <div class="top-part">
            <div class="title">
                <h1>Découvrez Votre <p class="text-blue">Météo</p></h1>
            </div>
        </div>
        <div class="bottom-part">
            <label id="input-search">
                <input placeholder="Recherche par lieux" id="input-search">
            </label>

            <div class="search">
                <button class="button-search">Recherche</button>

                <button id="locationButton" onclick="askForLocation()">Autoriser l'accès à la localisation</button>

            </div>
        </div>

    </div>
    <div class="right-part">
        <div id="map">
        </div>

    </div>
</div>


<script>

    var apiKey = "<?php echo $apiKey; ?>"; // Transférez la valeur PHP à JavaScript
    // Fonction pour demander authorisation de localisation
    function askForLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
        }
    }

    // Fonction pour afficher la carte avec la localisation
    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        document.cookie = 'latitude=' + latitude;
        document.cookie = 'longitude=' + longitude;

        console.log(latitude)
        console.log(longitude)

        var map = tt.map({
            key: apiKey,
            container: 'map',
            center: [longitude, latitude],
            zoom: 12
        });

        var marker = new tt.Marker().setLngLat([longitude, latitude]).addTo(map);

        // Masquer le bouton une fois que l'autorisation a été accordée
        var locationButton = document.getElementById('locationButton');
        if (locationButton) {
            locationButton.style.display = 'none';
        }
    }


    // Fonction pour gérer les erreurs de localisation
    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("L'utilisateur a refusé la demande d'autorisation de localisation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Les informations de localisation ne sont pas disponibles.");
                break;
            case error.TIMEOUT:
                alert("La demande d'autorisation de localisation a expiré.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Une erreur inconnue s'est produite.");
                break;
        }
    }

    // Fonction pour obtenir la valeur d'un cookie par son nom
    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) {
            return parts.pop().split(";").shift();
        }
    }

    // Au chargement de la page, vérifiez si les cookies de latitude et de longitude existent
    var storedLatitude = getCookie('latitude');
    var storedLongitude = getCookie('longitude');

    if (storedLatitude && storedLongitude) {
        var map = tt.map({
            key: apiKey,
            container: 'map',
            center: [parseFloat(storedLongitude), parseFloat(storedLatitude)],
            zoom: 12
        });

        var marker = new tt.Marker().setLngLat([parseFloat(storedLongitude), parseFloat(storedLatitude)]).addTo(map);

        // Masquer le bouton
        var locationButton = document.getElementById('locationButton');
        if (locationButton) {
            locationButton.style.display = 'none';
        }
    }
</script>
<?php require './views/footer.php' ?>