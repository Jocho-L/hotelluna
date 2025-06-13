function inicializarGraficosReportes(meses, ingresos, ingresosSemanales) {
    // Si no hay datos, usa datos de ejemplo
    if (!meses || !meses.length) {
        meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
    }
    if (!ingresos || !ingresos.length) {
        ingresos = [1200, 1500, 1800, 1300, 1700, 2000];
    }
    if (!ingresosSemanales || !ingresosSemanales.length) {
        ingresosSemanales = [300, 400, 350, 450, 500, 420];
    }

    // Limpia gráficos anteriores si existen
    if (window.ingresosChartInstance) window.ingresosChartInstance.destroy();
    if (window.ocupacionChartInstance) window.ocupacionChartInstance.destroy();

    // Gráfico de Ingresos Mensuales
    const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
    window.ingresosChartInstance = new Chart(ingresosCtx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ingresos (S/.)',
                data: ingresos,
                backgroundColor: 'rgba(40, 167, 69, 0.6)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Ingresos por Mes' }
            }
        }
    });

    // Gráfico de Ingresos Semanales
    const ocupacionCtx = document.getElementById('ocupacionChart').getContext('2d');
    window.ocupacionChartInstance = new Chart(ocupacionCtx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ingresos Semanales (S/.)',
                data: ingresosSemanales,
                borderColor: 'rgba(255, 193, 7, 0.8)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Ingresos Semanales por Mes' }
            }
        }
    });
}