<?php
if (isset($_POST['Submit'])) {
    $Name = $_POST["Name"];
    $Contact_number = $_POST["Contact_number"];
    $Community = $_POST["Community"];
    $Waste_Type = $_POST["Waste_Type"];
    $Quantity = $_POST["Quantity"];
    $Address = $_POST["Address"];

    if (empty($Name) || empty($Contact_number) || empty($Community) || empty($Waste_Type) || empty($Quantity) || empty($Address)) {
        echo "Fill all details";
        die();
    }

    // Define a mapping between addresses, database names, and table names
    $addressMapping = array(
        "delhi" => array("database" => "delhi", "table" => "employee2"),
        "hyderabad" => array("database" => "hyderabad", "table" => "employee2"),
        "vizag" => array("database" => "vizag", "table" => "employee2"),
		        "mumbai" => array("database" => "mumbai", "table" => "employee2")
        // Add more addresses and corresponding database and table names as needed
    );

    // Check if the address is in the mapping
    if (!array_key_exists(strtolower($Address), $addressMapping)) {
        echo "Invalid address";
        die();
    }

    $databaseName = $addressMapping[strtolower($Address)]['database'];
    $tableName = $addressMapping[strtolower($Address)]['table'];

    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = $databaseName;
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
    }

    // Check if Contact_number already exists
    $SELECT = "SELECT Contact_number FROM $tableName WHERE Contact_number=? LIMIT 1";
    $stmt = $conn->prepare($SELECT);

    if (!$stmt) {
        die('Error preparing SELECT statement: ' . $conn->error);
    }

    $stmt->bind_param("s", $Contact_number);
    $stmt->execute();
    $stmt->store_result();
    $rnum = $stmt->num_rows;
    $stmt->close();

    if ($rnum == 0) {
        // Contact number doesn't exist, insert new record
        $INSERT = "INSERT INTO $tableName (Name, Contact_number, Community, Waste_Type, Quantity, Address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($INSERT);

        if (!$stmt) {
            die('Error preparing INSERT statement: ' . $conn->error);
        }

        $stmt->bind_param("ssssis", $Name, $Contact_number, $Community, $Waste_Type, $Quantity, $Address);
        $stmt->execute();
        echo "New record inserted successfully";
        echo "<p><a href='wastecollection.html'>Home</a></p>";
    } else {
        // Contact number already exists
        echo "Contact number already exists";
    }

    $stmt->close();
    $conn->close();
}
?>
