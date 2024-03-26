<?php

$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaPiese($db, $sortOrder = 'ASC') {
    $query = "SELECT * FROM piese ORDER BY ID_PIESA $sortOrder";
    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333; text-align: center;'>Listă piese</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>ID Piesa</th>";
        $output .= "<th>Denumire Piesa</th>";
        $output .= "<th>Pret</th>";
        $output .= "<th>Acțiuni</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["ID_PIESA"]}</td>";
            $output .= "<td>{$rand["DENUMIRE_PIESA"]}</td>";
            $output .= "<td>{$rand["PRET"]}</td>";
            $output .= "<td>";
            $output .= "<a style='color: #4CAF50; text-decoration: none;' href='GestionarePiese.php?action=edit&id={$rand["ID_PIESA"]}'>Editează</a>";
            $output .= " | ";
            $output .= "<a style='color: #E53935; text-decoration: none;' href='GestionarePiese.php?action=delete&id={$rand["ID_PIESA"]}'>Șterge</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "Nu există piese în baza de date.";
    }

    return $output;
}
$sortOrder = 'ASC'; 
if (isset($_GET['sortOrder']) && ($_GET['sortOrder'] == 'ASC' || $_GET['sortOrder'] == 'DESC')) {
    $sortOrder = $_GET['sortOrder'];
}
echo afiseazaPiese($db, $sortOrder);

function adaugaPiesa($db, $denumirePiesa, $pret) {
    $query = "INSERT INTO piese (DENUMIRE_PIESA, PRET) VALUES ('$denumirePiesa', $pret)";
    $db->query($query);
}

function stergePiesa($db, $idPiesa) {
    $query = "DELETE FROM piese WHERE ID_PIESA = $idPiesa";
    $db->query($query);
}

function modificaPiesa($db, $idPiesa, $denumirePiesa, $pret) {
    $query = "UPDATE piese SET DENUMIRE_PIESA='$denumirePiesa', PRET=$pret WHERE ID_PIESA=$idPiesa";
    $db->query($query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "adaugaPiesa") {
    $denumirePiesa = $_POST["denumire_piesa"];
    $pret = $_POST["pret"];

    adaugaPiesa($db, $denumirePiesa, $pret);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "stergePiesa") {
    $idPiesa = $_POST["id_piesa"];

    stergePiesa($db, $idPiesa);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "modificaPiesa") {
    $idPiesa = $_POST["id_piesa"];
    $denumirePiesa = $_POST["denumire_piesa"];
    $pret = $_POST["pret"];

    modificaPiesa($db, $idPiesa, $denumirePiesa, $pret);
}

?>
<form method='get' action='GestionarePiese.php'>
    <label for='sortOrder'>Sortare:</label>
    <select name='sortOrder'>
        <option value='ASC' <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Crescător</option>
        <option value='DESC' <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descrescător</option>
    </select>
    <input type='submit' value='Sortează'>
</form>
<h2 style='color: #333; text-align: center;'>Adaugă piesa</h2>
<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
    <input type='hidden' name='action' value='adaugaPiesa'>
    <label for='denumire_piesa'>Denumire piesa:</label>
    <input type='text' name='denumire_piesa' placeholder='Denumire piesa' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='pret'>Preț:</label>
    <input type='text' name='pret' placeholder='Preț' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <input type='submit' value='Adaugă piesa' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>
</form>

<?php

$db->close();

?>
