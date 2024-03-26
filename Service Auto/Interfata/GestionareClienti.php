<?php
$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaClienti($db, $sortOrder = 'ASC') {
    $query = "SELECT * FROM client ORDER BY CNP_CLIENT $sortOrder";
    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333;'>Listă clienți</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>CNP</th>";
        $output .= "<th>Nume</th>";
        $output .= "<th>Prenume</th>";
        $output .= "<th>Adresă</th>";
        $output .= "<th>Telefon</th>";
        $output .= "<th>Email</th>";
        $output .= "<th>Acțiuni</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["CNP_CLIENT"]}</td>";
            $output .= "<td>{$rand["NUME"]}</td>";
            $output .= "<td>{$rand["PRENUME"]}</td>";
            $output .= "<td>{$rand["ADRESA"]}</td>";
            $output .= "<td>{$rand["TELEFON"]}</td>";
            $output .= "<td>{$rand["EMAIL"]}</td>";
            $output .= "<td>";
            $output .= "<a style='color: #4CAF50; text-decoration: none;' href='GestionareClienti.php?action=edit&id={$rand["CNP_CLIENT"]}'>Editează</a>";
            $output .= " | ";
            $output .= "<a style='color: #E53935; text-decoration: none;' href='GestionareClienti.php?action=delete&id={$rand["CNP_CLIENT"]}' onclick='return confirm(\"Sunteți sigur că doriți să ștergeți acest client?\")'>Șterge</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "<p style='color: #E53935;'>Nu există clienți în baza de date.</p>";
    }

    return $output;
}

$sortOrder = 'ASC'; 
if (isset($_GET['sortOrder']) && ($_GET['sortOrder'] == 'ASC' || $_GET['sortOrder'] == 'DESC')) {
    $sortOrder = $_GET['sortOrder'];
}

echo afiseazaClienti($db, $sortOrder);

function adaugaClient($db, $cnpClient, $nume, $prenume, $adresa, $telefon, $email) {
    $query = "INSERT INTO client (CNP_CLIENT, NUME, PRENUME, ADRESA, TELEFON, EMAIL) VALUES ('$cnpClient', '$nume', '$prenume', '$adresa', '$telefon', '$email')";
    $db->query($query);
}

function stergeClient($db, $idClient) {
    $query = "DELETE FROM client WHERE CNP_CLIENT = '$idClient'";
    $db->query($query);
}

function getDetaliiClient($db, $cnpClient) {
    $query = "SELECT * FROM client WHERE CNP_CLIENT = '$cnpClient'";
    $result = $db->query($query);

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "adaugaClient") {
    $cnpClient = $_POST["cnp_client"];
    $nume = $_POST["nume"];
    $prenume = $_POST["prenume"];
    $adresa = $_POST["adresa"];
    $telefon = $_POST["telefon"];
    $email = $_POST["email"];

    adaugaClient($db, $cnpClient, $nume, $prenume, $adresa, $telefon, $email);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["action"]) && $_GET["action"] == "delete") {
    if (isset($_GET["id"])) {
        $idClient = $_GET["id"];
        stergeClient($db, $idClient);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] == "edit") {
    if (isset($_GET["id"])) {
        $cnpClient = $_GET["id"];
        $clientDetails = getDetaliiClient($db, $cnpClient);

        if ($clientDetails) {
            echo "<h2 style='color: #333; text-align: center;'>Editează client</h2>";
            echo "<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
            echo "<input type='hidden' name='action' value='modificaClient'>";
            echo "<input type='hidden' name='cnp_client' value='{$clientDetails["CNP_CLIENT"]}'>";
            echo "<label for='nume'>Nume:</label>";
            echo "<input type='text' name='nume' placeholder='Nume' required style='width: 100%; padding: 8px; margin-bottom: 10px;' value='{$clientDetails["NUME"]}'>";
            echo "<label for='prenume'>Prenume:</label>";
            echo "<input type='text' name='prenume' placeholder='Prenume' required style='width: 100%; padding: 8px; margin-bottom: 10px;' value='{$clientDetails["PRENUME"]}'>";
            echo "<label for='adresa'>Adresă:</label>";
            echo "<input type='text' name='adresa' placeholder='Adresă' required style='width: 100%; padding: 8px; margin-bottom: 10px;' value='{$clientDetails["ADRESA"]}'>";
            echo "<label for='telefon'>Telefon:</label>";
            echo "<input type='text' name='telefon' placeholder='Telefon' required style='width: 100%; padding: 8px; margin-bottom: 10px;' value='{$clientDetails["TELEFON"]}'>";
            echo "<label for='email'>Email:</label>";
            echo "<input type='text' name='email' placeholder='Email' required style='width: 100%; padding: 8px; margin-bottom: 10px;' value='{$clientDetails["EMAIL"]}'>";
            echo "<input type='submit' value='Salvează modificările' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>";
            echo "</form>";
        } else {
            echo "<p style='color: #E53935; text-align: center;'>Nu s-a găsit clientul specificat pentru editare.</p>";
        }
    }
}
?>
<form method='get' action='GestionareClienti.php'>
    <label for='sortOrder'>Sortare:</label>
    <select name='sortOrder'>
        <option value='ASC' <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Crescător</option>
        <option value='DESC' <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descrescător</option>
    </select>
    <input type='submit' value='Sortează'>
</form>
<h2 style='color: #333; text-align: center;'>Adaugă client</h2>
<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
    <input type='hidden' name='action' value='adaugaClient'>
    <label for='cnp_client' style='color: #333;'>CNP:</label>
    <input type='number' name='cnp_client' placeholder='CNP' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='nume' style='color: #333;'>Nume:</label>
    <input type='text' name='nume' placeholder='Nume' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='prenume' style='color: #333;'>Prenume:</label>
    <input type='text' name='prenume' placeholder='Prenume' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='adresa' style='color: #333;'>Adresă:</label>
    <input type='text' name='adresa' placeholder='Adresă' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='telefon' style='color: #333;'>Telefon:</label>
    <input type='text' name='telefon' placeholder='Telefon' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='email' style='color: #333;'>Email:</label>
    <input type='text' name='email' placeholder='Email' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <input type='submit' value='Adaugă client' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>
</form>
<?php
$db->close();
?>
