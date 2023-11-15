<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
</head>
<body>
    <h1>Edit Produk</h1>

    <?php
    require './../config/db.php';

    // Variabel untuk menyimpan data produk yang akan diedit
    $product = [];

    // Menangani pengiriman formulir untuk memperbarui produk
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            // Tetapkan ID produk yang akan diubah
            $selectedProductId = mysqli_real_escape_string($db_connect, $_POST['id']);

            // Mengambil data produk yang akan diedit
            $selectedProductQuery = "SELECT * FROM products WHERE id = $selectedProductId";
            $selectedProductResult = mysqli_query($db_connect, $selectedProductQuery);

            if (!$selectedProductResult) {
                die("Error fetching selected product: " . mysqli_error($db_connect));
            }

            // Mengambil data produk yang akan diedit
            $product = mysqli_fetch_assoc($selectedProductResult);

            // Menangani pengunggahan file
            $newImage = $_FILES['new_image']['name'];
            $imageTmpName = $_FILES['new_image']['tmp_name'];
            $imageSize = $_FILES['new_image']['size'];

            // Memeriksa apakah file diunggah
            if (!empty($newImage)) {
                // Mendapatkan Extension file
                $imageExtension = pathinfo($newImage, PATHINFO_EXTENSION);
                
                // Menghasilkan nama unik untuk file agar tidak terjadi tumpang tindih antar file
                $newImageName = uniqid('product_image_') . '.' . $imageExtension;

                // Mengupdate field gambar dengan nama file yang baru
                $newImage = $newImageName;

                // Membuat direktori "uploads" jika belum ada
                $uploadPath = 'uploads/';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Mengunggah file ke direktori "uploads"
                $destinationPath = $uploadPath . $newImageName;
                move_uploaded_file($imageTmpName, $destinationPath);
            }

            // Melakukan Query untuk update
            $newName = mysqli_real_escape_string($db_connect, $_POST['new_name']);
            $newPrice = mysqli_real_escape_string($db_connect, $_POST['new_price']);
            
            $updateQuery = "UPDATE products SET name = '$newName', price = '$newPrice', image = '$newImage', updated_at = CURRENT_TIMESTAMP WHERE id = $selectedProductId";

            $updateResult = mysqli_query($db_connect, $updateQuery);

            if ($updateResult) {
                echo "Produk berhasil diperbarui!";
                // Refresh the $product array with the updated information
                $product['name'] = $newName;
                $product['price'] = $newPrice;
                $product['image'] = $newImage;
            } else {
                die("Error updating product: " . mysqli_error($db_connect));
            }
        }
    }

    // Mengambil semua produk untuk formulir
    $productsQuery = "SELECT * FROM products";
    $productsResult = mysqli_query($db_connect, $productsQuery);

    if (!$productsResult) {
        die("Error fetching products: " . mysqli_error($db_connect));
    }
    ?>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="id">Pilih Produk:</label>
        <select name="id">
            <?php while ($row = mysqli_fetch_assoc($productsResult)) : ?>
                <option value="<?= $row['id']; ?>" <?= ($product && $product['id'] == $row['id']) ? 'selected' : ''; ?>><?= $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="new_name">Nama Baru:</label>
        <input type="text" name="new_name" value="<?= isset($product['name']) ? $product['name'] : ''; ?>"><br>

        <label for="new_price">Harga Baru:</label>
        <input type="text" name="new_price" value="<?= isset($product['price']) ? $product['price'] : ''; ?>"><br>

        <label for="new_image">Gambar Baru:</label>
        <input type="file" name="new_image"><br>

        <input type="submit" name="submit" value="Perbarui">
    </form>
</body>
</html>
