async function loadData() {
    const res = await fetch('../api.php?action=get_researcher_data');
    const data = await res.json();
    if (data.success) {
        document.getElementById('totalSessions').innerText = data.total_sessions;
        document.getElementById('avgAccuracy').innerText = data.avg_accuracy.toFixed(1)+'%';
        document.getElementById('avgTime').innerText = data.avg_response_time.toFixed(1)+'s';
        // desenhar gráficos com Chart.js...
    }
}
document.getElementById('refresh').addEventListener('click', loadData);
document.getElementById('exportCSV').addEventListener('click', () => window.open('../api.php?action=export_logs&format=csv'));
loadData();