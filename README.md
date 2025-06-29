# Carbon Footprint Calculator

**🏆 IBM Hackathon Project** - A comprehensive web application that helps users calculate and track their carbon emissions using IBM's GHG (Greenhouse Gas) APIs.

## About This Project

This project was developed as part of the **IBM Hackathon**, focusing on leveraging IBM's Environmental Intelligence Suite and GHG calculation APIs to create an accurate and user-friendly carbon footprint calculator. The application demonstrates the integration of IBM's cloud services with modern web technologies to address environmental sustainability challenges.

### Hackathon Goals
- **Innovation**: Leverage IBM's cutting-edge environmental APIs
- **Sustainability**: Help users understand and reduce their carbon footprint
- **Technology**: Showcase IBM Cloud integration capabilities
- **Impact**: Provide actionable insights for environmental consciousness

## Features

- **User Management**: Registration, login, and profile management
- **Energy Consumption Tracking**: Monitor electricity, LPG, kerosene usage
- **Transportation Analysis**: Calculate emissions from various transport modes
- **Waste Impact Measurement**: Understand waste disposal emissions
- **IBM API Integration**: Accurate emissions calculations using IBM's GHG APIs
- **Dashboard & Analytics**: Visual charts and historical data tracking
- **Eco Tips**: Personalized recommendations for reducing carbon footprint

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **APIs**: IBM Environmental Intelligence Suite & GHG Calculation APIs
- **Server**: Apache (XAMPP)
- **IBM Cloud Services**: Environmental Intelligence Suite, Watson APIs

## Installation & Setup

### 1. Prerequisites

- XAMPP or similar local server environment
- PHP 7.4 or higher
- MySQL 5.7 or higher
- cURL extension enabled in PHP

### 2. Database Setup

1. Start your XAMPP server (Apache & MySQL)
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `carbon_calculator`
4. Import the database schema:
   ```sql
   -- Run the SQL file: database/carbon_calculator.sql
   ```

### 3. IBM API Setup

1. **Register for IBM API Access**:
   - Visit IBM's API marketplace
   - Register for GHG calculation services
   - Obtain your API key and endpoints

2. **Configure API Credentials**:
   - Copy `env.example` to `.env`
   - Update the IBM API configuration:
   ```env
   IBM_API_BASE_URL=https://api.ibm.com/ghg/v1
   IBM_API_KEY=your_actual_ibm_api_key_here
   ```

3. **Update API Configuration**:
   - Edit `config/api_config.php`
   - Replace `YOUR_IBM_API_KEY_HERE` with your actual API key
   - Update endpoints if needed based on IBM's documentation

### 4. Project Setup

1. **Clone/Download** the project to your XAMPP htdocs folder
2. **Set Permissions** (if on Linux/Mac):
   ```bash
   chmod 755 -R /path/to/project
   ```
3. **Configure Database**:
   - Edit `config/db_config.php` with your database credentials
4. **Test Installation**:
   - Visit `http://localhost/your-project-folder`

## IBM API Integration

### API Endpoints Used

The application integrates with IBM's GHG calculation APIs for accurate emissions data:

- **Energy Emissions**: `/energy/calculate`
- **Transportation Emissions**: `/transportation/calculate`
- **Waste Emissions**: `/waste/calculate`

### API Request Format

```json
{
  "electricity_kwh": 250,
  "natural_gas_therms": 1,
  "fuel_oil_gallons": 0,
  "propane_gallons": 0,
  "region": "IN",
  "calculation_method": "ghg_protocol"
}
```

### Fallback Mechanism

If IBM API calls fail, the application automatically falls back to local calculation methods using industry-standard emission factors.

## Project Structure

```
├── api/
│   └── ibm_api.php          # IBM API integration
├── assets/
│   ├── css/                 # Stylesheets
│   └── js/
│       └── charts.js        # Chart visualizations
├── config/
│   ├── api_config.php       # API configuration
│   └── db_config.php        # Database configuration
├── database/
│   └── carbon_calculator.sql # Database schema
├── includes/
│   ├── functions.php        # Helper functions
│   ├── header.php           # Common header
│   └── footer.php           # Common footer
├── energy_form.php          # Energy consumption form
├── transportation_form.php  # Transportation form
├── waste_form.php           # Waste generation form
├── results.php              # Results display
├── dashboard.php            # User dashboard
├── register.php             # User registration
├── login.php                # User login
└── index.php                # Homepage
```

## Usage

### For Users

1. **Register/Login**: Create an account or sign in
2. **Enter Energy Data**: Provide electricity, LPG, and kerosene usage
3. **Enter Transportation Data**: Specify vehicle type, fuel, and distance
4. **Enter Waste Data**: Describe waste disposal method and amount
5. **View Results**: See detailed emissions breakdown and recommendations

### For Developers

1. **API Integration**: The IBM API calls are handled in `api/ibm_api.php`
2. **Database Operations**: User data and calculations are stored in MySQL
3. **Frontend**: Bootstrap-based responsive design with Chart.js visualizations

## Configuration Options

### Environment Variables

Create a `.env` file in the root directory:

```env
# IBM API Configuration
IBM_API_BASE_URL=https://api.ibm.com/ghg/v1
IBM_API_KEY=your_api_key_here

# Database Configuration
DB_SERVER=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_NAME=carbon_calculator

# Application Settings
APP_ENV=development
APP_DEBUG=true
```

### Customization

- **Emission Factors**: Update local calculation factors in `api/ibm_api.php`
- **UI Styling**: Modify CSS in `assets/css/`
- **Charts**: Customize visualizations in `assets/js/charts.js`

## Troubleshooting

### Common Issues

1. **API Connection Failed**:
   - Check your IBM API key in `config/api_config.php`
   - Verify API endpoints are correct
   - Ensure cURL is enabled in PHP

2. **Database Connection Error**:
   - Verify MySQL is running
   - Check database credentials in `config/db_config.php`
   - Ensure database `carbon_calculator` exists

3. **Permission Errors**:
   - Set proper file permissions (755 for directories, 644 for files)
   - Ensure web server can write to session directory

### Debug Mode

Enable debug mode by setting `APP_DEBUG=true` in your environment configuration.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## IBM Hackathon Context

This project was developed during the **IBM Hackathon** with the following objectives:

### 🎯 Project Vision
- **Environmental Impact**: Create tools that help individuals understand their carbon footprint
- **IBM Technology**: Demonstrate the power of IBM's Environmental Intelligence Suite
- **Innovation**: Showcase modern web development with AI-powered APIs
- **Accessibility**: Make carbon footprint calculation accessible to everyone

### 🏆 Hackathon Achievements
- **IBM API Integration**: Successfully integrated IBM's Environmental Intelligence Suite
- **User Experience**: Created an intuitive, step-by-step carbon footprint calculator
- **Data Visualization**: Implemented interactive charts and analytics
- **Scalability**: Built with fallback mechanisms for reliability

### 🔧 Technical Highlights
- **Real-time Calculations**: Using IBM's GHG calculation APIs
- **Responsive Design**: Works on all devices
- **Data Persistence**: User history and progress tracking
- **Educational Content**: Eco tips and sustainability recommendations

## License

This project is licensed under the MIT License.

## Support

### IBM Hackathon Resources
- **IBM Cloud Documentation**: https://cloud.ibm.com/docs
- **IBM Environmental Intelligence Suite**: https://www.ibm.com/products/environmental-intelligence-suite
- **IBM Developer Community**: https://developer.ibm.com/

### API Integration Support
- **IBM API Documentation**: https://cloud.ibm.com/docs/environmental-intelligence-suite
- **Carbon Interface Docs**: https://docs.carboninterface.com/
- **Climatiq Docs**: https://www.climatiq.io/docs

### Application Support
- **Troubleshooting Guide**: Check the troubleshooting section above
- **Error Logs**: Review PHP error logs for debugging
- **GitHub Issues**: Report bugs and feature requests
- **Development Team**: Contact the hackathon team for support

### Hackathon Team Contact
For questions about this IBM Hackathon project:
- **Project Repository**: This GitHub repository
- **Documentation**: See `API_SETUP_GUIDE.md` for detailed setup instructions
- **Demo**: Contact the development team for live demonstrations 