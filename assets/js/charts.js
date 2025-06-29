// Function to create emissions breakdown chart
function createEmissionsChart(elementId, energyEmissions, transportEmissions, wasteEmissions) {
    const ctx = document.getElementById(elementId).getContext('2d');
    
    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Energy', 'Transportation', 'Waste'],
            datasets: [{
                data: [energyEmissions, transportEmissions, wasteEmissions],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Carbon Emissions Breakdown'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value.toFixed(2)} kg CO2e (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    return chart;
}

// Function to create historical emissions chart
function createHistoricalChart(elementId, dates, emissions) {
    const ctx = document.getElementById(elementId).getContext('2d');
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Total Emissions (kg CO2e)',
                data: emissions,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'kg CO2e'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Historical Carbon Emissions'
                }
            }
        }
    });
    
    return chart;
}

// Function to create comparison chart
function createComparisonChart(elementId, userEmissions, avgEmissions) {
    const ctx = document.getElementById(elementId).getContext('2d');
    
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Your Emissions', 'Average Emissions'],
            datasets: [{
                label: 'Carbon Emissions (kg CO2e)',
                data: [userEmissions, avgEmissions],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'kg CO2e'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Emissions Comparison'
                }
            }
        }
    });
    
    return chart;
} 