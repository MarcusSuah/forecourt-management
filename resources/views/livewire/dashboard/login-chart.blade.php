<div>
    <!-- resources/views/livewire/dashboard/login-chart.blade.php -->
    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logins (Last 7 Days)</h3>
        <canvas id="loginChart"></canvas>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const ctx = document.getElementById("loginChart").getContext("2d");
                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: "Logins",
                            data: @json($data),
                            fill: false,
                            borderColor: "#3B82F6",
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
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
