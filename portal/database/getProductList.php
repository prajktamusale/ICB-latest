<?php
include "../config.php";

$query = "SELECT id,name FROM product";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row['name'];
    }
    echo json_encode($products);
} else {
    echo json_encode([]);
}

$mysqli->close();
?>