# API Setup Guide for Carbon Footprint Calculator

This guide will help you get the necessary API keys for accurate carbon footprint calculations.

## Option 1: IBM Environmental Intelligence Suite (Recommended)

### Step 1: Create IBM Cloud Account
1. Go to **IBM Cloud**: https://cloud.ibm.com/
2. Click "Create account" or "Sign up"
3. Fill in your details and verify your email
4. Complete the registration process

### Step 2: Access Environmental Intelligence Suite
1. Log in to IBM Cloud Console
2. Go to **Catalog** → **AI and Machine Learning**
3. Search for "Environmental Intelligence Suite"
4. Click on the service

### Step 3: Subscribe to the Service
1. Click "Create" or "Subscribe"
2. Choose a plan:
   - **Lite Plan**: Free tier (limited requests)
   - **Standard Plan**: Paid tier (more requests)
3. Select your region (choose closest to you)
4. Click "Create"

### Step 4: Get API Credentials
1. Go to your **Resource List**
2. Find "Environmental Intelligence Suite"
3. Click on the service instance
4. Go to **Service credentials** tab
5. Click "New credential"
6. Choose "Manager" role
7. Click "Add"
8. Copy the API key and base URL

### Step 5: Configure Your Application
Edit `config/api_config.php`:
```php
define('IBM_API_KEY', 'your_actual_ibm_api_key_here');
define('IBM_API_BASE_URL', 'https://api.ibm.com/environmental-intelligence/v1');
```

## Option 2: Carbon Interface API (Alternative)

### Step 1: Sign Up
1. Go to **Carbon Interface**: https://www.carboninterface.com/
2. Click "Get Started" or "Sign Up"
3. Create an account with your email

### Step 2: Get API Key
1. Log in to your Carbon Interface dashboard
2. Go to **API Keys** section
3. Copy your API key

### Step 3: Configure Your Application
Edit `config/api_config.php`:
```php
define('CARBON_INTERFACE_API_KEY', 'your_carbon_interface_api_key');
```

## Option 3: Climatiq API (Alternative)

### Step 1: Sign Up
1. Go to **Climatiq**: https://www.climatiq.io/
2. Click "Get Started"
3. Create an account

### Step 2: Get API Key
1. Log in to your Climatiq dashboard
2. Go to **API Keys**
3. Generate a new API key
4. Copy the key

### Step 3: Configure Your Application
Edit `config/api_config.php`:
```php
define('CLIMATIQ_API_KEY', 'your_climatiq_api_key');
```

## API Endpoints and Usage

### IBM Environmental Intelligence Suite
- **Base URL**: `https://api.ibm.com/environmental-intelligence/v1`
- **Energy**: `/energy/emissions/calculate`
- **Transportation**: `/transportation/emissions/calculate`
- **Waste**: `/waste/emissions/calculate`

### Carbon Interface API
- **Base URL**: `https://www.carboninterface.com/api/v1`
- **Transportation**: `/estimates`
- **Energy**: `/estimates`

### Climatiq API
- **Base URL**: `https://api.climatiq.io/v1`
- **Transportation**: `/travel/estimates`
- **Energy**: `/energy/estimates`

## Testing Your API Connection

Create a test file `test_api.php`:

```php
<?php
require_once 'config/api_config.php';

// Test IBM API
$test_data = [
    'electricity_kwh' => 250,
    'region' => 'IN'
];

$response = makeIBMAPIRequest('/energy/emissions/calculate', $test_data);
if ($response) {
    echo "IBM API Test: SUCCESS\n";
    print_r($response);
} else {
    echo "IBM API Test: FAILED\n";
}

// Test Carbon Interface API
$carbon_data = [
    'type' => 'electricity',
    'electricity_unit' => 'kwh',
    'electricity_value' => 250,
    'country' => 'in'
];

$response = makeCarbonInterfaceRequest('/estimates', $carbon_data);
if ($response) {
    echo "Carbon Interface API Test: SUCCESS\n";
    print_r($response);
} else {
    echo "Carbon Interface API Test: FAILED\n";
}
?>
```

## Troubleshooting

### Common Issues:

1. **API Key Not Working**:
   - Verify the API key is correct
   - Check if the service is activated
   - Ensure you're using the right base URL

2. **Rate Limiting**:
   - Free tiers have request limits
   - Upgrade to paid plan for more requests

3. **Authentication Errors**:
   - Check API key format
   - Verify headers are correct
   - Ensure HTTPS is used

4. **Network Issues**:
   - Check internet connection
   - Verify firewall settings
   - Test with curl command

### Testing with curl:

```bash
# Test IBM API
curl -X POST "https://api.ibm.com/environmental-intelligence/v1/energy/emissions/calculate" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"electricity_kwh": 250, "region": "IN"}'

# Test Carbon Interface
curl -X POST "https://www.carboninterface.com/api/v1/estimates" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"type": "electricity", "electricity_unit": "kwh", "electricity_value": 250, "country": "in"}'
```

## Security Best Practices

1. **Never commit API keys to version control**
2. **Use environment variables for production**
3. **Rotate API keys regularly**
4. **Monitor API usage**
5. **Implement rate limiting in your application**

## Support Resources

- **IBM Cloud Support**: https://cloud.ibm.com/docs/get-support
- **Carbon Interface Docs**: https://docs.carboninterface.com/
- **Climatiq Docs**: https://www.climatiq.io/docs

## Next Steps

1. Choose your preferred API provider
2. Follow the setup steps above
3. Update your `config/api_config.php` file
4. Test the connection
5. Deploy your application

Your carbon footprint calculator will now use real API data for accurate emissions calculations! 