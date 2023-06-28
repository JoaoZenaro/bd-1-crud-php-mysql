<?php
$username = "user";
$password = "password";
$dbname = "unoesc";

try {
    $conn = new PDO("mysql:host=db;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("DROP TABLE IF EXISTS users");

    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL
        )";
    $conn->exec($createTableQuery);
    
    $insertDataQuery = "
        INSERT INTO users (name, email)
        VALUES
            ('John Doe', 'john@example.com'),
            ('Jane Smith', 'jane@example.com')";
    $conn->exec($insertDataQuery);
    
    $selectDataQuery = "SELECT * FROM users";
    $stmt = $conn->query($selectDataQuery);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Users:</h2>";
    echo "<ul>";
    foreach ($users as $user) {
        echo "<li>Name: " . $user['name'] . ", Email: " . $user['email'] . "</li>";
    }
    echo "</ul>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
