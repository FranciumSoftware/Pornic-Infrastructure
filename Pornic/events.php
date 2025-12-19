<?php
// events.php
require_once __DIR__ . '/vendor/autoload.php';

// Remplace par ton token d'accès valide
$accessToken = 'ton_token_d_acces';

// Configuration du client HelloAsso
$config = \HelloAsso\Configuration::getDefaultConfiguration()
    ->setAccessToken($accessToken);

// Initialise l'API
$apiInstance = new \HelloAsso\Api\OrganizationsApi(new GuzzleHttp\Client(), $config);

try {
    // Récupère la liste des organisations
    $organizations = $apiInstance->getOrganizations();

    if (empty($organizations)) {
        die("Aucune organisation trouvée.");
    }

    $orgId = $organizations[0]->getId();

    // Initialise l'API des événements
    $eventsApi = new \HelloAsso\Api\EventsApi(new GuzzleHttp\Client(), $config);

    // Récupère la liste des événements
    $events = $eventsApi->getEvents($orgId);

    if (empty($events->getData())) {
        echo "Aucun événement trouvé pour cette organisation.";
    } else {
        echo "<h1>Liste des événements</h1>";
        echo "<ul>";
        foreach ($events->getData() as $event) {
            echo "<li>" . htmlspecialchars($event->getName()) . "</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo 'Exception when calling API: ', $e->getMessage(), PHP_EOL;
}
?>
