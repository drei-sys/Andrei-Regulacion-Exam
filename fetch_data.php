<?php
$host = 'localhost';
$dbName = 'youtube_db';
$user = 'root';
$pass = '';

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from MySQL
    $stmt = $conn->query("SELECT * FROM youtube_channels");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
