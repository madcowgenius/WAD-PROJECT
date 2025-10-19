<?php
require_once 'config.php';

// Check if user is logged in
$auth = new UserAuth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
$salesManager = new SalesManager();
$cropManager = new CropManager();
$livestockManager = new LivestockManager();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $data = [
                    'sale_type' => sanitizeInput($_POST['sale_type']),
                    'item_id' => intval($_POST['item_id']),
                    'buyer_name' => sanitizeInput($_POST['buyer_name']),
                    'buyer_contact' => sanitizeInput($_POST['buyer_contact']),
                    'sale_date' => $_POST['sale_date'],
                    'quantity' => floatval($_POST['quantity']),
                    'unit_price' => floatval($_POST['unit_price']),
                    'total_amount' => floatval($_POST['quantity']) * floatval($_POST['unit_price']),
                    'payment_method' => sanitizeInput($_POST['payment_method']),
                    'payment_status' => sanitizeInput($_POST['payment_status']),
                    'notes' => sanitizeInput($_POST['notes'])
                ];
                
                $result = $salesManager->addSale($user['id'], $data);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
        }
    }
}

// Get all sales for the user
$sales = $salesManager->getSales($user['id']);
$crops = $cropManager->getCrops($user['id']);
$livestock = $livestockManager->getLivestock($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management - Farm Produce Tracker</title>
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
                        <a href="sales.php" class="nav-link active">Sales</a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link">Reports</a>
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
                <h1><i class="fas fa-chart-line"></i> Sales Management</h1>
                <p>Record and track your sales transactions with buyers</p>
            </div>

            <?php if (isset($message)): ?>
                <div style="background-color: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; 
                    color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>; 
                    padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Add Sale Form -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Record New Sale</h3>
                </div>
                <form method="POST" action="sales.php" style="padding: 20px;">
                    <input type="hidden" name="action" value="add">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="sale_type">Sale Type</label>
                            <select id="sale_type" name="sale_type" required onchange="updateItemOptions()">
                                <option value="">Select Type</option>
                                <option value="crop">Crop</option>
                                <option value="livestock">Livestock</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="item_id">Item</label>
                            <select id="item_id" name="item_id" required>
                                <option value="">Select Item</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="buyer_name">Buyer Name</label>
                            <input type="text" id="buyer_name" name="buyer_name" required placeholder="e.g., Windhoek Fresh Market">
                        </div>
                        <div class="form-group">
                            <label for="buyer_contact">Buyer Contact</label>
                            <input type="text" id="buyer_contact" name="buyer_contact" placeholder="Phone number or email">
                        </div>
                        <div class="form-group">
                            <label for="sale_date">Sale Date</label>
                            <input type="date" id="sale_date" name="sale_date" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" step="0.01" min="0" required onchange="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="unit_price">Unit Price (NAD)</label>
                            <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" required onchange="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Total Amount (NAD)</label>
                            <input type="number" id="total_amount" name="total_amount" step="0.01" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_status">Payment Status</label>
                            <select id="payment_status" name="payment_status" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Additional information about the sale"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-form" style="margin-top: 20px;">Record Sale</button>
                </form>
            </div>

            <!-- Sales List -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Your Sales (<?php echo count($sales); ?>)</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Item Type</th>
                            <th>Item ID</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Amount</th>
                            <th>Sale Date</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                                    No sales recorded yet. Record your first sale above.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sale['buyer_name']); ?></td>
                                    <td><?php echo ucfirst($sale['sale_type']); ?></td>
                                    <td><?php echo $sale['item_id']; ?></td>
                                    <td><?php echo $sale['quantity']; ?></td>
                                    <td><?php echo number_format($sale['unit_price'], 2); ?> NAD</td>
                                    <td><?php echo number_format($sale['total_amount'], 2); ?> NAD</td>
                                    <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; 
                                            background-color: <?php 
                                                echo $sale['payment_status'] === 'paid' ? '#d4edda' : 
                                                    ($sale['payment_status'] === 'pending' ? '#fff3cd' : '#f8d7da'); 
                                            }}; 
                                            color: <?php 
                                                echo $sale['payment_status'] === 'paid' ? '#155724' : 
                                                    ($sale['payment_status'] === 'pending' ? '#856404' : '#721c24'); 
                                            }};">
                                            <?php echo ucfirst($sale['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $sale['payment_method'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
    <script>
        // Data for dropdowns
        const crops = <?php echo json_encode($crops); ?>;
        const livestock = <?php echo json_encode($livestock); ?>;
        
        function updateItemOptions() {
            const saleType = document.getElementById('sale_type').value;
            const itemSelect = document.getElementById('item_id');
            
            // Clear existing options
            itemSelect.innerHTML = '<option value="">Select Item</option>';
            
            if (saleType === 'crop') {
                crops.forEach(crop => {
                    const option = document.createElement('option');
                    option.value = crop.id;
                    option.textContent = crop.crop_name + ' (' + crop.crop_type + ')';
                    itemSelect.appendChild(option);
                });
            } else if (saleType === 'livestock') {
                livestock.forEach(animal => {
                    const option = document.createElement('option');
                    option.value = animal.id;
                    option.textContent = animal.animal_type + ' (' + animal.breed + ') - Qty: ' + animal.quantity;
                    itemSelect.appendChild(option);
                });
            }
        }
        
        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const total = quantity * unitPrice;
            document.getElementById('total_amount').value = total.toFixed(2);
        }
        
        // Set today's date as default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('sale_date').value = today;
        });
    </script>
</body>
</html>
