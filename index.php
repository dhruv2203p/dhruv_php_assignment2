<?php
include 'dbinit.php'; // Include the database initialization file

// Establish a new connection to perform CRUD operations
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$editMode = false;
$editData = [];
$message = "";  // Variable to hold success/error messages

// Handling form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert new perfume
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $fragranceType = $_POST['fragranceType'];
        $brand = $_POST['brand'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $addedBy = 'YourName'; // Replace with your name

        // Input validation
        if (!empty($name) && !empty($description) && !empty($fragranceType) && !empty($brand) && is_numeric($quantity) && is_numeric($price)) {
            $stmt = $conn->prepare("INSERT INTO perfumes (PerfumeName, PerfumeDescription, FragranceType, Brand, QuantityAvailable, Price, ProductAddedBy) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssids", $name, $description, $fragranceType, $brand, $quantity, $price, $addedBy);
            $stmt->execute();
            $stmt->close();
    
        } else {
            $message = "Please fill all fields correctly.";
        }
    }

    // Update perfume
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $fragranceType = $_POST['fragranceType'];
        $brand = $_POST['brand'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        if (is_numeric($id) && !empty($name) && !empty($description) && !empty($fragranceType) && !empty($brand) && is_numeric($quantity) && is_numeric($price)) {
            $stmt = $conn->prepare("UPDATE perfumes SET PerfumeName=?, PerfumeDescription=?, FragranceType=?, Brand=?, QuantityAvailable=?, Price=? WHERE PerfumeID=?");
            $stmt->bind_param("ssssidi", $name, $description, $fragranceType, $brand, $quantity, $price, $id);
            $stmt->execute();
            $stmt->close();
    
        } else {
            $message = "Please fill all fields correctly.";
        }
    }

    // Delete perfume
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        if (is_numeric($id)) {
            $stmt = $conn->prepare("DELETE FROM perfumes WHERE PerfumeID=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        
        } else {
            $message = "Invalid ID.";
        }
    }
}

// Handle edit button click (populate the form with existing data)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editMode = true;

    $stmt = $conn->prepare("SELECT * FROM perfumes WHERE PerfumeID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close();
}

// Fetch perfumes for display
$result = $conn->query("SELECT * FROM perfumes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal - Perfumes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Admin Portal - Perfumes</h2>
        <div class="row">
            <!-- Left Side: Add / Update Form -->
            <div class="col-md-4">
                <?php if ($message): ?>
                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <h3><?php echo $editMode ? 'Update Perfume' : 'Add New Perfume'; ?></h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $editMode ? $editData['PerfumeID'] : ''; ?>">
                    <div class="form-group">
                        <label for="name">Perfume Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $editMode ? $editData['PerfumeName'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Perfume Description:</label>
                        <textarea class="form-control" name="description" id="description" required><?php echo $editMode ? $editData['PerfumeDescription'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fragranceType">Fragrance Type:</label>
                        <input type="text" class="form-control" name="fragranceType" id="fragranceType" value="<?php echo $editMode ? $editData['FragranceType'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand:</label>
                        <input type="text" class="form-control" name="brand" id="brand" value="<?php echo $editMode ? $editData['Brand'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity Available:</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" value="<?php echo $editMode ? $editData['QuantityAvailable'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" value="<?php echo $editMode ? $editData['Price'] : ''; ?>" required>
                    </div>
                    <button type="submit" name="<?php echo $editMode ? 'update' : 'add'; ?>" class="btn btn-<?php echo $editMode ? 'warning' : 'primary'; ?>">
                        <?php echo $editMode ? 'Update Perfume' : 'Add Perfume'; ?>
                    </button>
                </form>
            </div>

            <!-- Right Side: Display Perfumes -->
            <div class="col-md-8">
                <h3>Available Perfumes</h3>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Fragrance Type</th>
                            <th>Brand</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Added By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['PerfumeID']; ?></td>
                                <td><?php echo $row['PerfumeName']; ?></td>
                                <td><?php echo $row['PerfumeDescription']; ?></td>
                                <td><?php echo $row['FragranceType']; ?></td>
                                <td><?php echo $row['Brand']; ?></td>
                                <td><?php echo $row['QuantityAvailable']; ?></td>
                                <td><?php echo $row['Price']; ?></td>
                                <td><?php echo $row['ProductAddedBy']; ?></td>
                                <td><?php echo $row['CreatedAt']; ?></td>
                                <td>
                                    <a href="index.php?edit=<?php echo $row['PerfumeID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form method="POST" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?php echo $row['PerfumeID']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
