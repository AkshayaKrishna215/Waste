<?php
$servername = "localhost";
$username = "root";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedAddress = $_POST["address"];
    $matchedDatabase = getMatchedDatabase($submittedAddress);

    if ($matchedDatabase) {
        $conn = new mysqli($servername, $username, $password, $matchedDatabase);

        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        $query = "SELECT * FROM employee2 WHERE Address = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $submittedAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Query Failed: " . $conn->error);
        }

        echo "<table>";
        echo "<tr><th>Name</th><th>Contact Number</th><th>Community</th><th>Waste Type</th><th>Quantity</th><th>Address</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['Name'] . "</td><td>" . $row['Contact_number'] . "</td><td>" . $row['Community'] . "</td><td>" . $row['Waste_type'] . "</td><td>" . $row['Quantity'] . "</td><td>" . $row['Address'] . "</td></tr>";
        }

        echo "</table>";

        $conn->close();
    } else {
        echo "No matching database found for the submitted address.";
    }
}

function getMatchedDatabase($address) {
    // You can define your logic here to match the address with the database name.
    // For example, you might check if the address contains the city names (hyderabad, vizag, delhi, mumbai).
    if (stripos($address, 'hyderabad') !== false) {
        return "hyderabad";
    } elseif (stripos($address, 'vizag') !== false) {
        return "vizag";
    } elseif (stripos($address, 'delhi') !== false) {
        return "delhi";
    } elseif (stripos($address, 'mumbai') !== false) {
        return "mumbai";
    }

    // If no match is found, return false.
    return false;
}
?>
