@extends('layouts.app')

@section('content')
<div class="p-8 bg-white rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-8 text-center text-indigo-700">
        📊 Doctors per Specialty
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gray-50 p-4 rounded-xl shadow">
            <h3 class="text-center text-lg font-semibold mb-2 text-indigo-600">Bar Chart</h3>
            <canvas id="barChart"></canvas>
        </div>

        <div class="bg-gray-50 p-4 rounded-xl shadow">
            <h3 class="text-center text-lg font-semibold mb-2 text-indigo-600">Pie Chart</h3>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = @json($labels);
    const values = @json($values);

    // Random colors for each specialty
    const colors = labels.map(() =>
        `hsl(${Math.random() * 360}, 70%, 55%)`
    );

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Doctors',
                data: values,
                backgroundColor: colors,
                borderColor: '#333',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Doctors per Specialty',
                    color: '#333',
                    font: { size: 18 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Percentage of Doctors per Specialty',
                    color: '#333',
                    font: { size: 18 }
                }
            }
        }
    });
</script>
@endsection
