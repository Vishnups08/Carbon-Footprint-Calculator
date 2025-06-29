<?php
// IBM API Configuration
// Replace these with your actual IBM API credentials

// Option 1: IBM Environmental Intelligence Suite
define('IBM_API_BASE_URL', 'https://api.ibm.com/environmental-intelligence/v1');

// Your IBM API Key (Get from IBM Cloud Console)
define('IBM_API_KEY', 'YOUR_IBM_API_KEY_HERE');

// IBM Environmental Intelligence Suite Endpoints
define('IBM_ENERGY_ENDPOINT', '/energy/emissions/calculate');
define('IBM_TRANSPORTATION_ENDPOINT', '/transportation/emissions/calculate');
define('IBM_WASTE_ENDPOINT', '/waste/emissions/calculate');

// Alternative: Carbon Interface API (if IBM not available)
define('CARBON_INTERFACE_API_KEY', 'YOUR_CARBON_INTERFACE_API_KEY');
define('CARBON_INTERFACE_BASE_URL', 'https://www.carboninterface.com/api/v1');

// Alternative: Climatiq API (if IBM not available)
define('CLIMATIQ_API_KEY', 'YOUR_CLIMATIQ_API_KEY');
define('CLIMATIQ_BASE_URL', 'https://api.climatiq.io/v1');

// API Headers for IBM
function getIBMAPIHeaders() {
    return [
        'Authorization: Bearer ' . IBM_API_KEY,
        'Content-Type: application/json',
        'Accept: application/json',
        'X-IBM-Client-Id: ' . IBM_API_KEY
    ];
}

// API Headers for Carbon Interface
function getCarbonInterfaceHeaders() {
    return [
        'Authorization: Bearer ' . CARBON_INTERFACE_API_KEY,
        'Content-Type: application/json'
    ];
}

// API Headers for Climatiq
function getClimatiqHeaders() {
    return [
        'Authorization: Bearer ' . CLIMATIQ_API_KEY,
        'Content-Type: application/json'
    ];
}

// IBM API Request function
function makeIBMAPIRequest($endpoint, $data) {
    $url = IBM_API_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, getIBMAPIHeaders());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("IBM API cURL Error: $error");
        return false;
    }
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        error_log("IBM API Error: HTTP $httpCode - $response");
        return false;
    }
}

// Carbon Interface API Request function (fallback)
function makeCarbonInterfaceRequest($endpoint, $data) {
    $url = CARBON_INTERFACE_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, getCarbonInterfaceHeaders());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 || $httpCode === 201) {
        return json_decode($response, true);
    } else {
        error_log("Carbon Interface API Error: HTTP $httpCode - $response");
        return false;
    }
}

// Climatiq API Request function (fallback)
function makeClimatiqRequest($endpoint, $data) {
    $url = CLIMATIQ_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, getClimatiqHeaders());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        error_log("Climatiq API Error: HTTP $httpCode - $response");
        return false;
    }
}
?> 