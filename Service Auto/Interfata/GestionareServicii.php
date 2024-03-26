<?php

$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaServicii($db, $sortOrder = 'ASC') {
    $query = "SELECT * FROM servicii ORDER BY ID_SERVICIU $sortOrder";
    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333; text-align: center;'>Listă servicii</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>ID Serviciu</th>";
        $output .= "<th>Denumire Serviciu</th>";
        $output .= "<th>Pret</th>";
        $output .= "<th>Acțiuni</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["ID_SERVICIU"]}</td>";
            $output .= "<td>{$rand["DENUMIRE_SERVICIU"]}</td>";
            $output .= "<td>{$rand["PRET"]}</td>";
            $output .= "<td>";
            $output .= "<a style='color: #4CAF50; text-decoration: none;' href='GestionareMasini.php?action=edit&id={$rand["ID_SERVICIU"]}'>Editează</a>";
            $output .= " | ";
            $output .= "<a style='color: #E53935; text-decoration: none;' href='GestionareMasini.php?action=delete&id={$rand["ID_SERVICIU"]}'>Șterge</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "Nu există servicii în baza de date.";
    }

    return $output;
}
$sortOrder = 'ASC'; 
if (isset($_GET['sortOrder']) && ($_GET['sortOrder'] == 'ASC' || $_GET['sortOrder'] == 'DESC')) {
    $sortOrder = $_GET['sortOrder'];
}
echo afiseazaServicii($db, $sortOrder);

function adaugaServiciu($db, $denumireServiciu, $pret) {
    $query = "INSERT INTO servicii (DENUMIRE_SERVICIU, PRET) VALUES ('$denumireServiciu', $pret)";
    $db->query($query);
}

function stergeServiciu($db, $idServiciu) {
    $query = "DELETE FROM servicii WHERE ID_SERVICIU = $idServiciu";
    $db->query($query);
}

function modificaServiciu($db, $idServiciu, $denumireServiciu, $pret) {
    $query = "UPDATE servicii SET DENUMIRE_SERVICIU='$denumireServiciu', PRET=$pret WHERE ID_SERVICIU=$idServiciu";
    $db->query($query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "adaugaServiciu") {
    $denumireServiciu = $_POST["denumire_serviciu"];
    $pret = $_POST["pret"];

    adaugaServiciu($db, $denumireServiciu, $pret);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "stergeServiciu") {
    $idServiciu = $_POST["id_serviciu"];

    stergeServiciu($db, $idServiciu);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "modificaServiciu") {
    $idServiciu = $_POST["id_serviciu"];
    $denumireServiciu = $_POST["denumire_serviciu"];
    $pret = $_POST["pret"];

    modificaServiciu($db, $idServiciu, $denumireServiciu, $pret);
}

?>
<form method='get' action='GestionareServicii.php'>
    <label for='sortOrder'>Sortare:</label>
    <select name='sortOrder'>
        <option value='ASC' <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Crescător</option>
        <option value='DESC' <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descrescător</option>
    </select>
    <input type='submit' value='Sortează'>
</form>
<h2 style='color: #333; text-align: center;'>Adaugă serviciu</h2>
<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
    <input type='hidden' name='action' value='adaugaServiciu'>
    <label for='denumire_serviciu'>Denumire serviciu:</label>
    <input type='text' name='denumire_serviciu' placeholder='Denumire serviciu' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='pret'>Preț:</label>
    <input type='text' name='pret' placeholder='Preț' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <input type='submit' value='Adaugă serviciu' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>
</form>

<?php

$db->close();

?>
