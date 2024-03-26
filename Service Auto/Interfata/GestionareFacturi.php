<?php
$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaFacturi($db, $sortOrder = 'ASC') {
    $query = "SELECT * FROM factura ORDER BY ID_FACTURA $sortOrder";
    $rezultate = $db->query($query);

    $output = "<h2 style='text-align: center;'>Listă facturi</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>ID Factura</th>";
        $output .= "<th>Data Emitere</th>";
        $output .= "<th>Total Plata</th>";
        $output .= "<th>Acțiuni</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["ID_FACTURA"]}</td>";
            $output .= "<td>{$rand["DATA_EMITERE"]}</td>";
            $output .= "<td>{$rand["TOTAL_PLATA"]}</td>";
            $output .= "<td>";
            $output .= "<a style='color: #4CAF50; text-decoration: none;' href='GestionareFacturi.php?action=edit&id={$rand["ID_FACTURA"]}'>Editează</a>";
            $output .= " | ";
            $output .= "<a style='color: #E53935; text-decoration: none;' href='GestionareFacturi.php?action=delete&id={$rand["ID_FACTURA"]}'>Șterge</a>";
            $output .= "</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "Nu există facturi în baza de date.";
    }

    return $output;
}
$sortOrder = 'ASC'; 
if (isset($_GET['sortOrder']) && ($_GET['sortOrder'] == 'ASC' || $_GET['sortOrder'] == 'DESC')) {
    $sortOrder = $_GET['sortOrder'];
}
echo afiseazaFacturi($db, $sortOrder);

function adaugaFactura($db, $dataEmitere, $totalPlata) {
    $query = "INSERT INTO factura (DATA_EMITERE, TOTAL_PLATA) VALUES ('$dataEmitere', $totalPlata)";
    $db->query($query);
}

function stergeFactura($db, $idFactura) {
    $query = "DELETE FROM factura WHERE ID_FACTURA = $idFactura";
    $db->query($query);
}

function modificaFactura($db, $idFactura, $dataEmitere, $totalPlata) {
    $query = "UPDATE factura SET DATA_EMITERE='$dataEmitere', TOTAL_PLATA=$totalPlata WHERE ID_FACTURA=$idFactura";
    $db->query($query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "adaugaFactura") {
    $dataEmitere = $_POST["data_emitere"];
    $totalPlata = $_POST["total_plata"];

    adaugaFactura($db, $dataEmitere, $totalPlata);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "stergeFactura") {
    $idFactura = $_POST["id_factura"];

    stergeFactura($db, $idFactura);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "modificaFactura") {
    $idFactura = $_POST["id_factura"];
    $dataEmitere = $_POST["data_emitere"];
    $totalPlata = $_POST["total_plata"];

    modificaFactura($db, $idFactura, $dataEmitere, $totalPlata);
}

?>
<form method='get' action='GestionareFacturi.php'>
    <label for='sortOrder'>Sortare:</label>
    <select name='sortOrder'>
        <option value='ASC' <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Crescător</option>
        <option value='DESC' <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descrescător</option>
    </select>
    <input type='submit' value='Sortează'>
</form>
<h2 style='text-align: center;'>Adaugă factură</h2>
<form action='' method='post' style='width: 50%; text-align: center; margin: 20px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
    <input type='hidden' name='action' value='adaugaFactura'>
    <label for='data_emitere'>Data Emitere:</label>
    <input type='date' name='data_emitere' placeholder='Data Emitere' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <label for='total_plata'>Total Plata:</label>
    <input type='text' name='total_plata' placeholder='Total Plata' required style='width: 100%; padding: 8px; margin-bottom: 10px;'>
    <input type='submit' value='Adaugă factură' style='background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;'>
</form>

<?php
$db->close();
?>
