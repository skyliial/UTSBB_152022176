<?php
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$db = 'cuaca_db'; // Nama database
$user = 'root'; // Username database (misalnya 'root' untuk XAMPP)
$pass = ''; // Password database (biasanya kosong di XAMPP)

// Koneksi ke database menggunakan PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit();
}

$result = [];

// Query untuk suhuMax, suhuMin, dan suhuRata
$query1 = "SELECT MAX(suhu) AS suhuMax, MIN(suhu) AS suhuMin, AVG(suhu) AS suhuRata FROM tb_cuaca";
$stmt1 = $conn->prepare($query1);
$stmt1->execute();
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$result['suhuMax'] = (int)$row1['suhuMax'];
$result['suhuMin'] = (int)$row1['suhuMin'];
$result['suhuRata'] = round((float)$row1['suhuRata'], 2);

// Query untuk nilai_suhu_max_humid_max
$query2 = "SELECT id AS idx, suhu, humid, lux AS kecerahan, ts AS timestamp FROM tb_cuaca 
           WHERE suhu = :suhuMax";
$stmt2 = $conn->prepare($query2);
$stmt2->execute([':suhuMax' => $result['suhuMax']]);
$result['nilai_suhu_max_humid_max'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Query untuk month_year_max
$query3 = "SELECT DISTINCT DATE_FORMAT(ts, '%m-%Y') AS month_year 
           FROM tb_cuaca 
           WHERE suhu = :suhuMax";
$stmt3 = $conn->prepare($query3);
$stmt3->execute([':suhuMax' => $result['suhuMax']]);
$result['month_year_max'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Output hasil sebagai JSON
echo json_encode($result);

// Tutup koneksi database
$conn = null;
?>
