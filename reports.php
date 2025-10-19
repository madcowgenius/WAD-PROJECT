<?php
require_once 'config.php';

// Check if user is logged in
$auth = new UserAuth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$cropManager = new CropManager();
$livestockManager = new LivestockManager();
$salesManager = new SalesManager();

// Get all data for reports
$crops = $cropManager->getCrops($user['id']);
$livestock = $livestockManager->getLivestock($user['id']);
$sales = $salesManager->getSales($user['id']);
$salesSummary = $salesManager->getSalesSummary($user['id']);

// Calculate statistics
$totalCrops = count($crops);
$totalLivestock = array_sum(array_column($livestock, 'quantity'));
$totalSales = $salesSummary['total_sales'] ?? 0;
$totalRevenue = $salesSummary['total_revenue'] ?? 0;
$paidAmount = $salesSummary['paid_amount'] ?? 0;
$pendingAmount = $salesSummary['pending_amount'] ?? 0;
$averageSale = $salesSummary['average_sale'] ?? 0;

// Crop statistics
$cropStats = [];
foreach ($crops as $crop) {
    $type = $crop['crop_type'];
    if (!isset($cropStats[$type])) {
        $cropStats[$type] = ['count' => 0, 'area' => 0, 'cost' => 0];
    }
    $cropStats[$type]['count']++;
    $cropStats[$type]['area'] += $crop['planted_area'];
    $cropStats[$type]['cost'] += $crop['planting_cost'];
}

// Livestock statistics
$livestockStats = [];
foreach ($livestock as $animal) {
    $type = $animal['animal_type'];
    if (!isset($livestockStats[$type])) {
        $livestockStats[$type] = ['count' => 0, 'quantity' => 0, 'value' => 0];
    }
    $livestockStats[$type]['count']++;
    $livestockStats[$type]['quantity'] += $animal['quantity'];
    $livestockStats[$type]['value'] += $animal['purchase_price'];
}

// Monthly sales data
$monthlySales = [];
foreach ($sales as $sale) {
    $month = date('Y-m', strtotime($sale['sale_date']));
    if (!isset($monthlySales[$month])) {
        $monthlySales[$month] = ['count' => 0, 'amount' => 0];
    }
    $monthlySales[$month]['count']++;
    $monthlySales[$month]['amount'] += $sale['total_amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Farm Produce Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <i class="fas fa-seedling"></i>
                    <span>Farm Tracker</span>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="crops.php" class="nav-link">Crops</a>
                    </li>
                    <li class="nav-item">
                        <a href="livestock.php" class="nav-link">Livestock</a>
                    </li>
                    <li class="nav-item">
                        <a href="sales.php" class="nav-link">Sales</a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link active">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">Logout</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1><i class="fas fa-chart-bar"></i> Reports & Analytics</h1>
                <p>Comprehensive analysis of your farm's performance and profitability</p>
            </div>

            <!-- Summary Statistics -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-seedling"></i>
                    <h3><?php echo $totalCrops; ?></h3>
                    <p>Total Crops</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-cow"></i>
                    <h3><?php echo $totalLivestock; ?></h3>
                    <p>Total Livestock</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-chart-line"></i>
                    <h3><?php echo $totalSales; ?></h3>
                    <p>Total Sales</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <h3><?php echo number_format($totalRevenue, 2); ?> NAD</h3>
                    <p>Total Revenue</p>
                </div>
            </div>

            <!-- Financial Overview -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Financial Overview</h3>
                </div>
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div style="background: #d4edda; padding: 20px; border-radius: 10px; text-align: center;">
                            <h4 style="color: #155724; margin-bottom: 10px;">Paid Amount</h4>
                            <p style="font-size: 1.5rem; font-weight: bold; color: #155724;">
                                <?php echo number_format($paidAmount, 2); ?> NAD
                            </p>
                        </div>
                        <div style="background: #fff3cd; padding: 20px; border-radius: 10px; text-align: center;">
                            <h4 style="color: #856404; margin-bottom: 10px;">Pending Amount</h4>
                            <p style="font-size: 1.5rem; font-weight: bold; color: #856404;">
                                <?php echo number_format($pendingAmount, 2); ?> NAD
                            </p>
                        </div>
                        <div style="background: #cce5ff; padding: 20px; border-radius: 10px; text-align: center;">
                            <h4 style="color: #004085; margin-bottom: 10px;">Average Sale</h4>
                            <p style="font-size: 1.5rem; font-weight: bold; color: #004085;">
                                <?php echo number_format($averageSale, 2); ?> NAD
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crop Analysis -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Crop Analysis</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Crop Type</th>
                            <th>Number of Fields</th>
                            <th>Total Area (ha)</th>
                            <th>Total Cost (NAD)</th>
                            <th>Average Cost per ha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($cropStats)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                                    No crop data available for analysis.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cropStats as $type => $stats): ?>
                                <tr>
                                    <td><?php echo ucfirst($type); ?></td>
                                    <td><?php echo $stats['count']; ?></td>
                                    <td><?php echo number_format($stats['area'], 2); ?></td>
                                    <td><?php echo number_format($stats['cost'], 2); ?></td>
                                    <td><?php echo $stats['area'] > 0 ? number_format($stats['cost'] / $stats['area'], 2) : '0.00'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Livestock Analysis -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Livestock Analysis</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Animal Type</th>
                            <th>Number of Groups</th>
                            <th>Total Quantity</th>
                            <th>Total Value (NAD)</th>
                            <th>Average Value per Animal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($livestockStats)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                                    No livestock data available for analysis.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($livestockStats as $type => $stats): ?>
                                <tr>
                                    <td><?php echo ucfirst($type); ?></td>
                                    <td><?php echo $stats['count']; ?></td>
                                    <td><?php echo $stats['quantity']; ?></td>
                                    <td><?php echo number_format($stats['value'], 2); ?></td>
                                    <td><?php echo $stats['quantity'] > 0 ? number_format($stats['value'] / $stats['quantity'], 2) : '0.00'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Monthly Sales Trend -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Monthly Sales Trend</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Number of Sales</th>
                            <th>Total Amount (NAD)</th>
                            <th>Average per Sale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($monthlySales)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px; color: #666;">
                                    No sales data available for trend analysis.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            ksort($monthlySales);
                            foreach ($monthlySales as $month => $data): 
                            ?>
                                <tr>
                                    <td><?php echo date('F Y', strtotime($month . '-01')); ?></td>
                                    <td><?php echo $data['count']; ?></td>
                                    <td><?php echo number_format($data['amount'], 2); ?></td>
                                    <td><?php echo $data['count'] > 0 ? number_format($data['amount'] / $data['count'], 2) : '0.00'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Recommendations -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Recommendations</h3>
                </div>
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <?php if ($totalCrops > 0): ?>
                            <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; border-left: 4px solid #4a7c59;">
                                <h4 style="color: #2c5530; margin-bottom: 10px;">
                                    <i class="fas fa-lightbulb"></i> Crop Management
                                </h4>
                                <p style="color: #666; margin-bottom: 10px;">
                                    You have <?php echo $totalCrops; ?> crops in your inventory. Consider diversifying your crop types to reduce risk and maximize seasonal opportunities.
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($pendingAmount > 0): ?>
                            <div style="background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 4px solid #ffc107;">
                                <h4 style="color: #856404; margin-bottom: 10px;">
                                    <i class="fas fa-exclamation-triangle"></i> Payment Follow-up
                                </h4>
                                <p style="color: #666; margin-bottom: 10px;">
                                    You have <?php echo number_format($pendingAmount, 2); ?> NAD in pending payments. Consider following up with buyers to improve cash flow.
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($totalLivestock > 0): ?>
                            <div style="background: #d4edda; padding: 20px; border-radius: 10px; border-left: 4px solid #28a745;">
                                <h4 style="color: #155724; margin-bottom: 10px;">
                                    <i class="fas fa-heart"></i> Livestock Health
                                </h4>
                                <p style="color: #666; margin-bottom: 10px;">
                                    Monitor your <?php echo $totalLivestock; ?> livestock regularly. Regular health checks can prevent losses and improve productivity.
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($totalSales > 0): ?>
                            <div style="background: #f8d7da; padding: 20px; border-radius: 10px; border-left: 4px solid #dc3545;">
                                <h4 style="color: #721c24; margin-bottom: 10px;">
                                    <i class="fas fa-chart-line"></i> Sales Growth
                                </h4>
                                <p style="color: #666; margin-bottom: 10px;">
                                    You've made <?php echo $totalSales; ?> sales totaling <?php echo number_format($totalRevenue, 2); ?> NAD. Consider expanding your buyer network to increase sales volume.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Farm Tracker</h3>
                    <p>Empowering Namibian farmers with digital farm management solutions.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="crops.php">Crops</a></li>
                        <li><a href="livestock.php">Livestock</a></li>
                        <li><a href="sales.php">Sales</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: info@farmtracker.na</p>
                    <p>Phone: +264 XX XXX XXXX</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Farm Produce Tracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
