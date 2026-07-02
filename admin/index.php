<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

// Stats
$productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categoriesCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$leadsCount = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();

// Leads for past 7 days
$last7Days = [];
for ($i = 6; $i >= 0; $i--) {
    $last7Days[] = date('Y-m-d', strtotime("-$i days"));
}
$leads7DaysData = array_fill_keys($last7Days, 0);

$leadsData = $pdo->query("
    SELECT DATE(created_at) as lead_date, COUNT(*) as count 
    FROM leads 
    WHERE DATE(created_at) >= date('now', '-6 days')
    GROUP BY DATE(created_at)
")->fetchAll();

foreach ($leadsData as $row) {
    if(isset($leads7DaysData[$row['lead_date']])) {
        $leads7DaysData[$row['lead_date']] = $row['count'];
    }
}

$chartLabels = json_encode(array_map(function($date) { return date('M j', strtotime($date)); }, array_keys($leads7DaysData)));
$chartData = json_encode(array_values($leads7DaysData));

// Recent Leads
$recentLeads = $pdo->query("
    SELECT l.*, p.name as product_name 
    FROM leads l 
    LEFT JOIN products p ON l.product_id = p.id 
    ORDER BY l.id DESC LIMIT 5
")->fetchAll();
$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<!-- Dashboard Cards -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6 animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Products</h3>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-gray-900"><?php echo $productsCount; ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Categories</h3>
            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-gray-900"><?php echo $categoriesCount; ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between hover:shadow-md transition col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Leads</h3>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-gray-900"><?php echo $leadsCount; ?></p>
    </div>
</div>

<!-- Graph and Table Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-slide-up">
    <!-- Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col">
        <h2 class="text-sm font-bold text-gray-900 mb-4">Leads (Last 7 Days)</h2>
        <div class="flex-1 relative w-full min-h-[250px]">
            <canvas id="leadsChart"></canvas>
        </div>
    </div>

    <!-- Recent Leads Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900">Recent Leads</h2>
            <a href="/admin/leads.php" class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1 transition">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-400 text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="px-5 py-3 font-bold">Customer</th>
                        <th class="px-5 py-3 font-bold">Product</th>
                        <th class="px-5 py-3 font-bold">Date</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-50">
                    <?php if (empty($recentLeads)): ?>
                        <tr>
                            <td colspan="3" class="px-5 py-6 text-center text-gray-500">No leads found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentLeads as $lead): ?>
                            <tr class="hover:bg-gray-50/80 transition duration-150">
                                <td class="px-5 py-3 font-bold text-gray-900"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100 truncate max-w-[100px] sm:max-w-none">
                                        <?php echo htmlspecialchars($lead['product_name']); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 font-medium whitespace-nowrap"><?php echo date('M j', strtotime($lead['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let leadsChartInstance = null;
    
    document.addEventListener('turbo:load', () => {
        const ctx = document.getElementById('leadsChart');
        if (ctx) {
            if (leadsChartInstance) {
                leadsChartInstance.destroy();
            }
            leadsChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo $chartLabels; ?>,
                    datasets: [{
                        label: 'Leads',
                        data: <?php echo $chartData; ?>,
                        borderColor: '#2563eb', // blue-600
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937', // gray-800
                            titleFont: { size: 11 },
                            bodyFont: { size: 12 },
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 10, family: "'Inter', sans-serif" }
                            },
                            grid: {
                                color: '#f3f4f6', // gray-100
                                drawBorder: false
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, family: "'Inter', sans-serif" }
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
