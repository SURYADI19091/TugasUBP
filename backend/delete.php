<?php
require './../config/db.php';

//pegirimanan data yang ingin di hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $selectedProductId = mysqli_real_escape_string($db_connect, $_POST['selected_product']);

        // Melakukan query penghapusan 
        $deleteQuery = "DELETE FROM products WHERE id = $selectedProductId";
        $deleteResult = mysqli_query($db_connect, $deleteQuery);

        if ($deleteResult) {
            echo "Produk berhasil dihapus!";
        } else {
            die("penghapusan gagal di karena terjadi eror" . mysqli_error($db_connect));
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk</title>
</head>
<body>
    <h1>Hapus Produk</h1>

    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama produk</th>
                <th>Harga</th>
                <th>Gambar produk</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($product = mysqli_fetch_assoc($productsResult)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['price']; ?></td>
                    <td>
                        <img src="uploads/<?= $product['image']; ?>" width="100" alt="<?= $product['name']; ?>">
                    </td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="selected_product" value="<?= $product['id']; ?>">
                            <input type="submit" name="delete" value="Hapus">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
