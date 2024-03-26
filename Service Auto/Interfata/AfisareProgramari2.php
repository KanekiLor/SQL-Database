<?php

$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaProgramariClienti($db) {
    $query = "SELECT 
                C.NUME AS NUME_CLIENT,
                COUNT(P.NR_PROGRAMARE) AS NUMAR_PROGRAMARI
            FROM 
                CLIENT C
            JOIN 
                MASINA M ON C.CNP_CLIENT = M.CNP_CLIENT
            JOIN 
                PROGRAMARI P ON M.SERIE_SASIU = P.SERIE_SASIU
            GROUP BY 
                C.NUME
            HAVING 
                COUNT(P.NR_PROGRAMARE) > 2";

    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333;'>Rezultate Programări Clienti</h2>";

    $output .= "<div style='background-color: #f2f2f2; padding: 10px; margin-bottom: 20px;'>";
    $output .= "<strong>SQL Query:</strong><br>";
    $output .= "<code>" . nl2br(htmlspecialchars($query)) . "</code>";
    $output .= "</div>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>Nume Client</th>";
        $output .= "<th>Număr Programări</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["NUME_CLIENT"]}</td>";
            $output .= "<td>{$rand["NUMAR_PROGRAMARI"]}</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "<p style='color: #E53935;'>Nu există rezultate conform cererii.</p>";
    }

    return $output;
}

echo afiseazaProgramariClienti($db);

$db->close();

?>
