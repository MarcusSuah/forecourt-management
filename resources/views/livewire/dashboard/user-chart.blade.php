<div class="w-full h-full px-4 py-6">
    <canvas id="userLoginsChart" height="100"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('userLoginsChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'User Logins',
                        data: @json($data),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9CA3AF'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9CA3AF'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#9CA3AF'
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>
