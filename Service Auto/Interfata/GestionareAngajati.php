<?php

$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaAngajati($db, $sortOrder = 'ASC') {
    $query = "SELECT * FROM angajat ORDER BY CNP_ANGAJAT $sortOrder";
    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333; text-align: center;'>Listă angajați</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>CNP Angajat</th>";
        $output .= "<th>Nume</th>";
        $output .= "<th>Prenume</th>";
        $output .= "<th>Adresă</th>";
        $output .= "<th>Telefon</th>";
        $output .= "<th>Email</th>";
        $output .= "<th>Salariu</th>";
        $output .= "<th>Data Angajare</th>";
        $output .= "<th>Acțiuni</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["CNP_ANGAJAT"]}</td>";
            $output .= "<td>{$rand["NUME"]}</td>";
            $output .= "<td>{$rand["PRENUME"]}</td>";
            $output .= "<td>{$rand["ADRESA"]}</td>";
            $output .= "<td>{$rand["TELEFON"]}</td>";
            $output .= "<td>{$rand["EMAIL"]}</td>";
            $output .= "<td>{$rand["SALARIU"]}</td>";
            $output .= "<td>{$rand["DATA_ANGAJARE"]}</td>";
            $output .= "<td>";
            $output .= "<a style='color: #4CAF50; text-decoration: none;' href='GestionareAngajati.php?action=edit&id={$rand["CNP_ANGAJAT"]}'>Editează</a>";
            $output .= " | ";
            $output .= "<a style='color: #E53935; text-decoration: none;' href='GestionareAngajati.php?action=delete&id={$rand["CNP_ANGAJAT"]}'>Șterge</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "Nu există angajați în baza de date.";
    }

    return $output;
}
$sortOrder = 'ASC'; 
if (isset($_GET['sortOrder']) && ($_GET['sortOrder'] == 'ASC' || $_GET['sortOrder'] == 'DESC')) {
    $sortOrder = $_GET['sortOrder'];
}
echo afiseazaAngajati($db, $sortOrder);

function adaugaAngajat($db, $nume, $prenume, $adresa, $telefon, $email, $salariu, $dataAngajare) {
    $query = "INSERT INTO angajat (NUME, PRENUME, ADRESA, TELEFON, EMAIL, SALARIU, DATA_ANGAJARE) VALUES ('$nume', '$prenume', '$adresa', '$telefon', '$email', $salariu, '$dataAngajare')";
    $db->query($query);
}

function stergeAngajat($db, $cnpAngajat) {
    $query = "DELETE FROM angajat WHERE CNP_ANGAJAT = '$cnpAngajat'";
    $db->query($query);
}

function modificaAngajat($db, $cnpAngajat, $nume, $prenume, $adresa, $telefon, $email, $salariu, $dataAngajare) {
    $query = "UPDATE angajat SET NUME='$nume', PRENUME='$prenume', ADRESA='$adresa', TELEFON='$telefon', EMAIL='$email', SALARIU=$salariu, DATA_ANGAJARE='$dataAngajare' WHERE CNP_ANGAJAT='$cnpAngajat'";
    $db->query($query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "adaugaAngajat") {
    $nume = $_POST["nume"];
    $prenume = $_POST["prenume"];
    $adresa = $_POST["adresa"];
    $telefon = $_POST["telefon"];
    $email = $_POST["email"];
    $salariu = $_POST["salariu"];
    $dataAngajare = $_POST["data_angajare"];

    adaugaAngajat($db, $nume, $prenume, $adresa, $telefon, $email, $salariu, $dataAngajare);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "stergeAngajat") {
    $cnpAngajat = $_POST["cnp_angajat"];

    stergeAngajat($db, $cnpAngajat);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "modificaAngajat") {
    $cnpAngajat = $_POST["cnp_angajat"];
    $nume = $_POST["nume"];
    $prenume = $_POST["prenume"];
    $adresa = $_POST["adresa"];
    $telefon = $_POST["telefon"];
    $email = $_POST["email"];
    $salariu = $_POST["salariu"];
    $dataAngajare = $_POST["data_angajare"];

    modificaAngajat($db, $cnpAngajat, $nume, $prenume, $adresa, $telefon, $email, $salariu, $dataAngajare);
}

?>
<form method='get' action='GestionareAngajati.php'>
    <label for='sortOrder'>Sortare:</label>
    <select name='sortOrder'>
        <option value='ASC' <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Crescător</option>
        <option value='DESC' <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descrescător</option>
    </select>
    <input type='submit' value='Sortează'>
</form>
<h2 style='color: #333; text-align: center;'>Adaugă angajat</h2>
<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
    <input type='hidden' name='action' value='adaugaAngajat'>
    <label for='nume'>Nume:</label>
    <input type='text' name='nume' placeholder='Nume' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='prenume'>Prenume:</label>
    <input type='text' name='prenume' placeholder='Prenume' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='adresa'>Adresă:</label>
    <input type='text' name='adresa' placeholder='Adresă' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='telefon'>Telefon:</label>
    <input type='text' name='telefon' placeholder='Telefon' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='email'>Email:</label>
    <input type='text' name='email' placeholder='Email' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='salariu'>Salariu:</label>
    <input type='text' name='salariu' placeholder='Salariu' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='data_angajare'>Data Angajare:</label>
    <input type='date' name='data_angajare' placeholder='Data Angajare' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <input type='submit' value='Adaugă angajat' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>
</form>

<?php

$db->close();

?>
