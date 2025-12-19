<?php

    $key=$_SESSION['user']['key'] ?? '';
    define('API_KEY', $key);
    require_once 'config.php';
function callAPI($method, $apikey, $url, $data = false)
{
    $curl = curl_init();
    $httpheader = ['DOLAPIKEY: ' . $apikey];

    switch (strtoupper($method)) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, true);
            $httpheader[] = "Content-Type: application/json";
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;

        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            $httpheader[] = "Content-Type: application/json";
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;

        case "GET":
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            break;

        default:
            throw new Exception("Méthode HTTP non supportée : $method");
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (curl_errno($curl)) {
        echo "Erreur cURL : " . curl_error($curl);
    }

    curl_close($curl);
    
    // Debug: Affiche les erreurs 403, 401, 500
    if ($httpCode >= 400) {
        error_log("API Error $httpCode: " . $url);
        error_log("Response: " . $response);
    }
    
    return $response;
}
function articleDetails($id) {
    $json = callAPI("GET", API_KEY, baseURL . 'products/' . $id);

    $details = json_decode($json);

    $description = $details->{"description"};

    return $description;
}
function articleName($id) {
    $json = callAPI("GET", API_KEY, baseURL . 'products/' . $id);

    $details = json_decode($json);

    $nom = $details->{"label"};
    return $nom;
}
function articlePrice($id) {
    $json = callAPI("GET", API_KEY, baseURL . 'products/' . $id);

    $details = json_decode($json);

    $prix = $details->{"price"};
    return $prix;
}
function articleRef($id) {
    $json = callAPI("GET", API_KEY, baseURL . 'products/' . $id);

    $details = json_decode($json);

    $ref = $details->{"ref"};
    return $ref;
}
function cleanJson($json) {
    // Supprimer les clés contenant les séquences \u0000 (propriétés privées/protégées)
    // Ces clés sont formatées comme: "\u0000NomClasse\u0000propriété" ou "\u0000*\u0000propriété"
    $json = preg_replace('/"\\\u0000[^"]*\\\u0000[^"]*":\s*[^,}]*,?/u', '', $json);
    
    // Nettoyer les virgules superflues laissées par la suppression
    $json = preg_replace('/,\s*([}\]])/u', '$1', $json);
    $json = preg_replace('/,\s*,/u', ',', $json);
    
    return $json;
}

function articleImage($id) {

    $url = baseURL . 'documents?modulepart=product&id=' . $id;
    $json = callAPI("GET", API_KEY, $url);
    $json = cleanJson($json);
    $details = json_decode($json, true); // mode tableau associatif
    $first = $details[0];
    $image_url = SITE_ROOT . 'document.php?hashp=' . $first['share'];
    return $image_url;
}



function getListProducts() {
    $json = callAPI("GET", API_KEY, baseURL . 'products?sortfield=t.ref&sortorder=ASC&limit=100&ids_only=true');

    $details = json_decode($json);
    
    

    return $details;
}

function commander($method, $apikey, $url, $data = false)
{
    $curl = curl_init();
    $httpheader = ['DOLAPIKEY: ' . $apikey];

    switch (strtoupper($method)) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, true);
            $httpheader[] = "Content-Type: application/json";
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;

        case "GET":
            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
            break;

        default:
            throw new Exception("Méthode HTTP non supportée : $method");
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // 👈 ici tu récupères le code HTTP
    curl_close($curl);

    return [
        'body' => $response,
        'http_code' => $httpCode
    ];
}
function order() {
    $socid = $_SESSION['user']['socid'] ?? null;
    if (empty($socid)) {
        throw new Exception("L'ID du client (socid) est manquant dans la session.");
    }
    $orderData = [
        "socid" => $_SESSION['user']['socid'],
        "date" => date("Y-m-d"),
        "lines" => []
    ];

    // Sécuriser l'accès au panier (évite les warnings si les clés manquent)
    $panierIds = $_SESSION['panier']['id'] ?? [];
    foreach ($panierIds as $productId) {
        // Récupère les détails du produit via l'API
        $price = articlePrice($productId);
        $name = articleName($productId);
        // Utilise une valeur par défaut (1) si la quantité n'existe pas pour cet ID
        $qty = isset($_SESSION['panier']['qte'][$productId]) ? $_SESSION['panier']['qte'][$productId] : 1; // À adapter si tu stockes la quantité différemment

        $orderData['lines'][] = [
            "fk_product" => (int)$productId,
            "qty" => (int)$qty,
            "subprice" => (float)$price,
            "desc" => $name
        ];
    }

    $url = baseURL . 'orders/';
    $response = callAPI("POST", API_KEY, $url, $orderData);
    return $response;
}
function getInfos($token) {
    $url = baseURL . 'users/info';
    $headers = [
        "DOLAPIKEY: " . $token,
    ];
    $json = callAPI("GET", $token, $url, $headers);
    $details = json_decode($json);
    // L'API retourne l'ID de l'utilisateur dans le champ "id", pas "socid"
    return $details->socid ?? null;
}
function getPaymantUrl($i){
    $response = callAPI("GET", API_KEY, baseURL . 'orders/' . $i);
    $details=json_decode($response);
    $url=$details->{'ref'};
    return $url;
}
function getOrder($orderId) {
    $response = callAPI("GET", API_KEY, baseURL . 'orders/' . $orderId);
    $details = json_decode($response, true);
    
    // Vérifier que la commande appartient à la société de l'utilisateur
    if (isset($details['socid']) && $details['socid'] != $_SESSION['user']['socid']) {
        throw new Exception("Accès refusé : cette commande n'appartient pas à votre société.");
    }
    
    return $details;
}
function searchProduct($query){
    $url = baseURL . 'products';
    $data = [
        'search' => $query,
        'sortfield' => 't.ref',
        'sortorder' => 'ASC',
        'limit' => 100,
        // Return full product objects so server-side rendering can use their fields
    ];
    $json = callAPI("GET", API_KEY, $url, $data);
    $details = json_decode($json);
    // If API didn't apply the search (returned full list), filter results here as fallback
    if (is_array($details) && !empty($query)) {
        $filtered = [];
        $q = mb_strtolower($query, 'UTF-8');
        foreach ($details as $p) {
            
            // If API returned scalar IDs, keep as-is
            if (!is_object($p) && !is_array($p)) {
                // we can't filter scalars cheaply here, so include them all
                $filtered[] = $p;
                continue;
            }

            // normalize fields
            $name = '';
            if (is_object($p)) {
                $name = $p->label ?? $p->name ?? '';
                $desc = $p->description ?? $p->desc ?? '';
                $ref = $p->ref ?? '';
            } else {
                $name = $p['label'] ?? $p['name'] ?? '';
                $desc = $p['description'] ?? $p['desc'] ?? '';
                $ref = $p['ref'] ?? '';
            }

            $haystack = mb_strtolower($name . ' ' . $desc . ' ' . $ref, 'UTF-8');
            if ($q === '' || mb_stripos($haystack, $q, 0, 'UTF-8') !== false) {
                $filtered[] = $p;
            }
        }
        return $filtered;

    }
    return $details;
}
?>
