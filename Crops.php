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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $data = [
                    'crop_name' => sanitizeInput($_POST['crop_name']),
                    'crop_type' => sanitizeInput($_POST['crop_type']),
                    'planting_date' => $_POST['planting_date'],
                    'expected_harvest_date' => $_POST['expected_harvest_date'],
                    'planted_area' => floatval($_POST['planted_area']),
                    'planting_cost' => floatval($_POST['planting_cost']),
                    'status' => sanitizeInput($_POST['status']),
                    'notes' => sanitizeInput($_POST['notes'])
                ];
                
                $result = $cropManager->addCrop($user['id'], $data);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
                
            case 'edit':
                $cropId = intval($_POST['crop_id']);
                $data = [
                    'crop_name' => sanitizeInput($_POST['crop_name']),
                    'crop_type' => sanitizeInput($_POST['crop_type']),
                    'planting_date' => $_POST['planting_date'],
                    'expected_harvest_date' => $_POST['expected_harvest_date'],
                    'planted_area' => floatval($_POST['planted_area']),
                    'planting_cost' => floatval($_POST['planting_cost']),
                    'status' => sanitizeInput($_POST['status']),
                    'notes' => sanitizeInput($_POST['notes'])
                ];
                
                $result = $cropManager->updateCrop($cropId, $user['id'], $data);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
                
            case 'delete':
                $cropId = intval($_POST['crop_id']);
                $result = $cropManager->deleteCrop($cropId, $user['id']);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
        }
    }
}

// Get all crops for the user
$crops = $cropManager->getCrops($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crops Management - Farm Produce Tracker</title>
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
                        <a href="crops.php" class="nav-link active">Crops</a>
                    </li>
                    <li class="nav-item">
                        <a href="livestock.php" class="nav-link">Livestock</a>
                    </li>
                    <li class="nav-item">
                        <a href="sales.php" class="nav-link">Sales</a>
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
                <h1><i class="fas fa-seedling"></i> Crops Management</h1>
                <p>Manage your crop planting, growth, and harvest records</p>
            </div>

            <?php if (isset($message)): ?>
                <div style="background-color: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; 
                    color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>; 
                    padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Add Crop Form -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Add New Crop</h3>
                </div>
                <form method="POST" action="crops.php" style="padding: 20px;">
                    <input type="hidden" name="action" value="add">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label for="crop_name">Crop Name</label>
                            <input type="text" id="crop_name" name="crop_name" required>
                        </div>
                        <div class="form-group">
                            <label for="crop_type">Crop Type</label>
                            <select id="crop_type" name="crop_type" required>
                                <option value="">Select Type</option>
                                <option value="maize">Maize</option>
                                <option value="mahangu">Mahangu</option>
                                <option value="wheat">Wheat</option>
                                <option value="vegetables">Vegetables</option>
                                <option value="fruits">Fruits</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="planting_date">Planting Date</label>
                            <input type="date" id="planting_date" name="planting_date" required>
                        </div>
                        <div class="form-group">
                            <label for="expected_harvest_date">Expected Harvest Date</label>
                            <input type="date" id="expected_harvest_date" name="expected_harvest_date">
                        </div>
                        <div class="form-group">
                            <label for="planted_area">Planted Area (hectares)</label>
                            <input type="number" id="planted_area" name="planted_area" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="planting_cost">Planting Cost (NAD)</label>
                            <input type="number" id="planting_cost" name="planting_cost" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="planted">Planted</option>
                                <option value="growing">Growing</option>
                                <option value="ready_for_harvest">Ready for Harvest</option>
                                <option value="harvested">Harvested</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-form" style="margin-top: 20px;">Add Crop</button>
                </form>
            </div>

            <!-- Crops List -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Your Crops (<?php echo count($crops); ?>)</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Crop Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Area (ha)</th>
                            <th>Cost</th>
                            <th>Planted Date</th>
                            <th>Expected Harvest</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($crops)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px; color: #666;">
                                    No crops recorded yet. Add your first crop above.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($crops as $crop): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($crop['crop_name']); ?></td>
                                    <td><?php echo ucfirst($crop['crop_type']); ?></td>
                                    <td>
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; 
                                            background-color: <?php 
                                                echo $crop['status'] === 'harvested' ? '#d4edda' : 
                                                    ($crop['status'] === 'growing' ? '#fff3cd' : 
                                                    ($crop['status'] === 'ready_for_harvest' ? '#cce5ff' : '#f8d7da')); 
                                            ?>; 
                                            color: <?php 
                                                echo $crop['status'] === 'harvested' ? '#155724' : 
                                                    ($crop['status'] === 'growing' ? '#856404' : 
                                                    ($crop['status'] === 'ready_for_harvest' ? '#004085' : '#721c24')); 
                                            }};">
                                            <?php echo ucfirst(str_replace('_', ' ', $crop['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $crop['planted_area']; ?></td>
                                    <td><?php echo $crop['planting_cost'] ? number_format($crop['planting_cost'], 2) . ' NAD' : '-'; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($crop['planting_date'])); ?></td>
                                    <td><?php echo $crop['expected_harvest_date'] ? date('M d, Y', strtotime($crop['expected_harvest_date'])) : '-'; ?></td>
                                    <td>
                                        <button class="btn-small" onclick="editCrop(<?php echo $crop['id']; ?>)">Edit</button>
                                        <button class="btn-small btn-danger" onclick="deleteCrop(<?php echo $crop['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Edit Crop Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 15px; max-width: 600px; width: 90%; max-height: 90%; overflow-y: auto;">
            <h3>Edit Crop</h3>
            <form method="POST" action="crops.php" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="crop_id" id="edit_crop_id">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="edit_crop_name">Crop Name</label>
                        <input type="text" id="edit_crop_name" name="crop_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_crop_type">Crop Type</label>
                        <select id="edit_crop_type" name="crop_type" required>
                            <option value="maize">Maize</option>
                            <option value="mahangu">Mahangu</option>
                            <option value="wheat">Wheat</option>
                            <option value="vegetables">Vegetables</option>
                            <option value="fruits">Fruits</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_planting_date">Planting Date</label>
                        <input type="date" id="edit_planting_date" name="planting_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_expected_harvest_date">Expected Harvest Date</label>
                        <input type="date" id="edit_expected_harvest_date" name="expected_harvest_date">
                    </div>
                    <div class="form-group">
                        <label for="edit_planted_area">Planted Area (hectares)</label>
                        <input type="number" id="edit_planted_area" name="planted_area" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_planting_cost">Planting Cost (NAD)</label>
                        <input type="number" id="edit_planting_cost" name="planting_cost" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" name="status" required>
                            <option value="planted">Planted</option>
                            <option value="growing">Growing</option>
                            <option value="ready_for_harvest">Ready for Harvest</option>
                            <option value="harvested">Harvested</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="edit_notes">Notes</label>
                        <textarea id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-form">Update Crop</button>
                    <button type="button" onclick="closeEditModal()" class="btn-form" style="background-color: #6c757d;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 15px; max-width: 400px; width: 90%;">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete this crop? This action cannot be undone.</p>
            <form method="POST" action="crops.php" id="deleteForm">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="crop_id" id="delete_crop_id">
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-form btn-danger">Delete</button>
                    <button type="button" onclick="closeDeleteModal()" class="btn-form" style="background-color: #6c757d;">Cancel</button>
                </div>
            </form>
        </div>
    </div>

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
        // Crop data for editing
        const crops = <?php echo json_encode($crops); ?>;
        
        function editCrop(cropId) {
            const crop = crops.find(c => c.id == cropId);
            if (crop) {
                document.getElementById('edit_crop_id').value = crop.id;
                document.getElementById('edit_crop_name').value = crop.crop_name;
                document.getElementById('edit_crop_type').value = crop.crop_type;
                document.getElementById('edit_planting_date').value = crop.planting_date;
                document.getElementById('edit_expected_harvest_date').value = crop.expected_harvest_date || '';
                document.getElementById('edit_planted_area').value = crop.planted_area;
                document.getElementById('edit_planting_cost').value = crop.planting_cost || '';
                document.getElementById('edit_status').value = crop.status;
                document.getElementById('edit_notes').value = crop.notes || '';
                document.getElementById('editModal').style.display = 'block';
            }
        }
        
        function deleteCrop(cropId) {
            document.getElementById('delete_crop_id').value = cropId;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>
