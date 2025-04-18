<?php
include "../config.php";

$query = "SELECT id, name FROM product";
$result = $mysqli->query($query);

if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(['error' => 'Failed to fetch product list.']);
}

$mysqli->close();
?>