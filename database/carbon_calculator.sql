-- Create database
CREATE DATABASE IF NOT EXISTS carbon_calculator;

-- Use the database
USE carbon_calculator;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create energy_consumption table
CREATE TABLE IF NOT EXISTS energy_consumption (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    electricity_kwh FLOAT NOT NULL,
    natural_gas_therms FLOAT NOT NULL,
    fuel_oil_gallons FLOAT NOT NULL,
    propane_gallons FLOAT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create transportation table
CREATE TABLE IF NOT EXISTS transportation (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    fuel_type VARCHAR(50) NOT NULL,
    distance_miles FLOAT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create waste table
CREATE TABLE IF NOT EXISTS waste (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    waste_type VARCHAR(50) NOT NULL,
    amount_kg FLOAT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create carbon_footprint table to store API results
CREATE TABLE IF NOT EXISTS carbon_footprint (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    energy_emissions FLOAT DEFAULT 0,
    transportation_emissions FLOAT DEFAULT 0,
    waste_emissions FLOAT DEFAULT 0,
    total_emissions FLOAT DEFAULT 0,
    calculation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create indexes for better performance
CREATE INDEX idx_energy_user ON energy_consumption(user_id);
CREATE INDEX idx_transportation_user ON transportation(user_id);
CREATE INDEX idx_waste_user ON waste(user_id);
CREATE INDEX idx_carbon_user ON carbon_footprint(user_id);
CREATE INDEX idx_carbon_date ON carbon_footprint(calculation_date);

-- Optional: Insert sample eco tips
CREATE TABLE IF NOT EXISTS eco_tips (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(50) NOT NULL,
    tip_title VARCHAR(100) NOT NULL,
    tip_content TEXT NOT NULL
);

INSERT INTO eco_tips (category, tip_title, tip_content) VALUES
('energy', 'Switch to LED Bulbs', 'LED bulbs use up to 80% less energy than traditional incandescent bulbs and last 25 times longer.'),
('energy', 'Unplug Electronics', 'Even when turned off, many electronics use standby power. Unplug them or use a power strip.'),
('energy', 'Adjust Your Thermostat', 'Lower your thermostat by 1-2 degrees in winter and raise it by 1-2 degrees in summer to save energy.'),
('transportation', 'Maintain Your Vehicle', 'Regular maintenance ensures your vehicle runs efficiently. Check tire pressure monthly.'),
('transportation', 'Combine Errands', 'Plan your trips to handle multiple errands at once to reduce total miles driven.'),
('transportation', 'Consider Carpooling', 'Sharing rides with others reduces emissions and can save on fuel and parking costs.'),
('waste', 'Start Composting', 'Composting food scraps can reduce methane emissions from landfills and create nutrient-rich soil.'),
('waste', 'Reduce Single-Use Plastics', 'Bring reusable bags, water bottles, and containers to minimize waste.'),
('waste', 'Buy in Bulk', 'Purchasing items in bulk reduces packaging waste and often saves money.');

-- Optional: Create a table for emission factors (for educational purposes)
CREATE TABLE IF NOT EXISTS emission_factors (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(50) NOT NULL,
    source VARCHAR(100) NOT NULL,
    factor_value FLOAT NOT NULL,
    unit VARCHAR(50) NOT NULL,
    description TEXT
);

INSERT INTO emission_factors (category, source, factor_value, unit, description) VALUES
('energy', 'electricity_us_avg', 0.417, 'kg CO2e/kWh', 'Average US electricity grid emissions factor'),
('energy', 'natural_gas', 5.3, 'kg CO2e/therm', 'Natural gas combustion emissions factor'),
('energy', 'fuel_oil', 10.21, 'kg CO2e/gallon', 'Residential fuel oil combustion emissions factor'),
('energy', 'propane', 5.76, 'kg CO2e/gallon', 'Propane combustion emissions factor'),
('transportation', 'gasoline_car', 0.404, 'kg CO2e/mile', 'Average gasoline car emissions per mile'),
('transportation', 'diesel_car', 0.429, 'kg CO2e/mile', 'Average diesel car emissions per mile'),
('transportation', 'hybrid_car', 0.284, 'kg CO2e/mile', 'Average hybrid car emissions per mile'),
('transportation', 'electric_car', 0.114, 'kg CO2e/mile', 'Average electric car emissions per mile (US grid)'),
('waste', 'landfill', 0.58, 'kg CO2e/kg waste', 'Emissions from waste sent to landfill'),
('waste', 'recycled', 0.02, 'kg CO2e/kg waste', 'Emissions from recycled waste'),
('waste', 'composted', 0.01, 'kg CO2e/kg waste', 'Emissions from composted waste'),
('waste', 'incinerated', 0.21, 'kg CO2e/kg waste', 'Emissions from incinerated waste');
