<div>
    <div class="w-full h-full">
        <canvas id="userChart"></canvas>

        <script>
            document.addEventListener('livewire:load', () => {
                new Chart(document.getElementById('userChart'), {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'New Users',
                            data: @json($data),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    </div>

</div>
