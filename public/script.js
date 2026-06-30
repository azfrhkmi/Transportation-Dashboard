// Global chart instances
let mainChartInstance = null;
let doughnutChartInstance = null;

// Default colors for doughnut chart
const doughnutColors = [
    '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ef4444', '#64748b'
];

document.addEventListener('DOMContentLoaded', () => {
    // Menu Toggle Logic (Mobile)
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }

    // Theme Toggle Logic
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    
    // Check local storage or default to dark
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            
            // Re-render charts with new colors
            if (window.dashboardData) {
                initCharts(window.dashboardData);
            }
        });
    }

    function updateThemeIcon(theme) {
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.className = 'ph ph-moon text-xl';
            } else {
                themeIcon.className = 'ph ph-sun text-xl';
            }
        }
    }

    // Initialize Chart Defaults based on theme
    const getChartColor = () => document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#475569';
    Chart.defaults.color = getChartColor();
    Chart.defaults.font.family = "'Inter', sans-serif";

    // If we have dynamic data injected from Laravel
    if (window.dashboardData) {
        initCharts(window.dashboardData);
    }
});

function initCharts(data) {
    const getChartColor = () => document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#475569';
    const getGridColor = () => document.documentElement.getAttribute('data-theme') === 'dark' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
    Chart.defaults.color = getChartColor();

    // 1. Initialize Trend Line Chart
    const ctxMain = document.getElementById('mainChart');
    if (ctxMain) {
        if (mainChartInstance) mainChartInstance.destroy();
        mainChartInstance = new Chart(ctxMain.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.trendLabels,
                datasets: [{
                    label: 'Total Aircraft Movements',
                    data: data.trendData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: getGridColor() },
                        beginAtZero: true
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Add filter listener
        const airportFilter = document.getElementById('airportFilter');
        if (airportFilter && data.airportTrendData) {
            // Remove old listener to avoid duplicates
            const newFilter = airportFilter.cloneNode(true);
            airportFilter.parentNode.replaceChild(newFilter, airportFilter);
            
            newFilter.addEventListener('change', (e) => {
                const selected = e.target.value;
                if (selected === 'ALL') {
                    mainChartInstance.data.datasets[0].data = data.trendData;
                    mainChartInstance.data.datasets[0].label = 'Total Aircraft Movements';
                } else {
                    mainChartInstance.data.datasets[0].data = data.airportTrendData[selected] || [];
                    mainChartInstance.data.datasets[0].label = selected + ' Movements';
                }
                mainChartInstance.update();
            });
        }
    }

    // 2. Initialize Doughnut Chart
    const ctxDoughnut = document.getElementById('doughnutChart');
    if (ctxDoughnut) {
        if (doughnutChartInstance) doughnutChartInstance.destroy();
        
        // Generate enough colors if there are more labels than default colors
        let colors = [...doughnutColors];
        while (colors.length < data.doughnutLabels.length) {
            colors.push('#' + Math.floor(Math.random()*16777215).toString(16));
        }

        doughnutChartInstance = new Chart(ctxDoughnut.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: data.doughnutLabels,
                datasets: [{
                    data: data.doughnutData,
                    backgroundColor: colors.slice(0, data.doughnutLabels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    }
                }
            }
        });
    }
}
