<?php
// IBM API Integration for Carbon Footprint Calculator
require_once '../config/api_config.php';

/**
 * Calculate energy emissions using IBM API
 * 
 * @param array $energy_data Energy consumption data
 * @return float|false Emissions in kg CO2e or false on error
 */
function calculateEnergyEmissionsIBM($energy_data) {
    $api_data = [
        'electricity_kwh' => $energy_data['electricity_kwh'],
        'natural_gas_therms' => $energy_data['natural_gas_therms'],
        'fuel_oil_gallons' => $energy_data['fuel_oil_gallons'],
        'propane_gallons' => $energy_data['propane_gallons'],
        'region' => 'IN', // India
        'calculation_method' => 'ghg_protocol'
    ];
    
    $response = makeIBMAPIRequest(IBM_ENERGY_ENDPOINT, $api_data);
    
    if ($response && isset($response['emissions'])) {
        return $response['emissions'];
    }
    
    // Fallback to local calculation if API fails
    return calculateEnergyEmissionsLocal($energy_data);
}

/**
 * Calculate transportation emissions using IBM API
 * 
 * @param array $transportation_data Transportation data
 * @return float|false Emissions in kg CO2e or false on error
 */
function calculateTransportationEmissionsIBM($transportation_data) {
    $api_data = [
        'vehicle_type' => $transportation_data['vehicle_type'],
        'fuel_type' => $transportation_data['fuel_type'],
        'distance_km' => $transportation_data['distance_miles'], // Convert to km if needed
        'region' => 'IN', // India
        'calculation_method' => 'ghg_protocol'
    ];
    
    $response = makeIBMAPIRequest(IBM_TRANSPORTATION_ENDPOINT, $api_data);
    
    if ($response && isset($response['emissions'])) {
        return $response['emissions'];
    }
    
    // Fallback to local calculation if API fails
    return calculateTransportationEmissionsLocal($transportation_data);
}

/**
 * Calculate waste emissions using IBM API
 * 
 * @param array $waste_data Waste data
 * @return float|false Emissions in kg CO2e or false on error
 */
function calculateWasteEmissionsIBM($waste_data) {
    $api_data = [
        'waste_type' => $waste_data['waste_type'],
        'amount_kg' => $waste_data['amount_kg'],
        'region' => 'IN', // India
        'calculation_method' => 'ghg_protocol'
    ];
    
    $response = makeIBMAPIRequest(IBM_WASTE_ENDPOINT, $api_data);
    
    if ($response && isset($response['emissions'])) {
        return $response['emissions'];
    }
    
    // Fallback to local calculation if API fails
    return calculateWasteEmissionsLocal($waste_data);
}

/**
 * Local fallback calculation for energy emissions
 */
function calculateEnergyEmissionsLocal($energy_data) {
    $electricity_factor = 0.92; // kg CO2e per kWh (Indian grid average)
    $natural_gas_factor = 5.3;  // kg CO2e per therm
    $fuel_oil_factor = 10.15;   // kg CO2e per gallon
    $propane_factor = 5.74;     // kg CO2e per gallon
    
    return ($energy_data['electricity_kwh'] * $electricity_factor) +
           ($energy_data['natural_gas_therms'] * $natural_gas_factor) +
           ($energy_data['fuel_oil_gallons'] * $fuel_oil_factor) +
           ($energy_data['propane_gallons'] * $propane_factor);
}

/**
 * Local fallback calculation for transportation emissions
 */
function calculateTransportationEmissionsLocal($transportation_data) {
    $emission_factors = [
        'car' => [
            'petrol' => 0.24,
            'diesel' => 0.27,
            'cng' => 0.18,
            'electric' => 0.08
        ],
        'motorcycle' => [
            'petrol' => 0.12,
            'electric' => 0.03
        ],
        'auto' => [
            'cng' => 0.15,
            'electric' => 0.04
        ],
        'bus' => [
            'diesel' => 0.12,
            'cng' => 0.09
        ],
        'train' => [
            'electric' => 0.07
        ]
    ];
    
    $distance_km = $transportation_data['distance_miles'];
    $vehicle_type = $transportation_data['vehicle_type'];
    $fuel_type = $transportation_data['fuel_type'];
    
    if (isset($emission_factors[$vehicle_type][$fuel_type])) {
        return $distance_km * $emission_factors[$vehicle_type][$fuel_type];
    }
    
    return 0;
}

/**
 * Local fallback calculation for waste emissions
 */
function calculateWasteEmissionsLocal($waste_data) {
    $emission_factors = [
        'landfill' => 0.58,
        'recycled' => 0.02,
        'composted' => 0.01,
        'incinerated' => 0.21
    ];
    
    $waste_type = $waste_data['waste_type'];
    $amount_kg = $waste_data['amount_kg'];
    
    if (isset($emission_factors[$waste_type])) {
        return $amount_kg * $emission_factors[$waste_type];
    }
    
    return 0;
}

/**
 * Get detailed emissions breakdown from IBM API
 * 
 * @param array $all_data Combined energy, transportation, and waste data
 * @return array Detailed emissions breakdown
 */
function getDetailedEmissionsBreakdown($all_data) {
    $breakdown = [
        'energy' => [
            'electricity' => 0,
            'natural_gas' => 0,
            'fuel_oil' => 0,
            'propane' => 0,
            'total' => 0
        ],
        'transportation' => [
            'vehicle_emissions' => 0,
            'fuel_production' => 0,
            'total' => 0
        ],
        'waste' => [
            'landfill_emissions' => 0,
            'transportation_emissions' => 0,
            'total' => 0
        ],
        'total_emissions' => 0
    ];
    
    // Calculate energy breakdown
    $energy_emissions = calculateEnergyEmissionsIBM($all_data['energy']);
    $breakdown['energy']['total'] = $energy_emissions;
    
    // Calculate transportation breakdown
    $transportation_emissions = calculateTransportationEmissionsIBM($all_data['transportation']);
    $breakdown['transportation']['total'] = $transportation_emissions;
    
    // Calculate waste breakdown
    $waste_emissions = calculateWasteEmissionsIBM($all_data['waste']);
    $breakdown['waste']['total'] = $waste_emissions;
    
    // Calculate total
    $breakdown['total_emissions'] = $energy_emissions + $transportation_emissions + $waste_emissions;
    
    return $breakdown;
}
?> 