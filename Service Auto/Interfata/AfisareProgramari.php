<?php

$db = new mysqli("localhost", "rares", "raresica92003", "serviceauto");

if ($db->connect_error) {
    die("Eroare la conectarea la baza de date: " . $db->connect_error);
}

function afiseazaProgramari($db, $numeClient, $dataProgramare) {
    $query = "SELECT 
                C.NUME AS NUME_CLIENT,
                C.PRENUME AS PRENUME_CLIENT,
                M.MARCA AS MARCA_MASINA,
                M.MODEL AS MODEL_MASINA,
                P.DATA_PROGRAMARE
            FROM 
                CLIENT C
            JOIN 
                MASINA M ON C.CNP_CLIENT = M.CNP_CLIENT
            JOIN 
                PROGRAMARI P ON M.SERIE_SASIU = P.SERIE_SASIU
            WHERE 
                C.NUME = '$numeClient'
                AND P.DATA_PROGRAMARE >= '$dataProgramare'";

    $rezultate = $db->query($query);

    $output = "<h2 style='color: #333;'>Rezultate Programări</h2>";

    if ($rezultate->num_rows > 0) {
        $output .= "<table style='width: 100%; border-collapse: collapse;'>";
        $output .= "<tr style='background-color: #4CAF50; color: white;'>";
        $output .= "<th>Nume Client</th>";
        $output .= "<th>Prenume Client</th>";
        $output .= "<th>Marca Mașină</th>";
        $output .= "<th>Model Mașină</th>";
        $output .= "<th>Data Programare</th>";
        $output .= "</tr>";

        while ($rand = $rezultate->fetch_assoc()) {
            $output .= "<tr style='text-align: center;'>";
            $output .= "<td>{$rand["NUME_CLIENT"]}</td>";
            $output .= "<td>{$rand["PRENUME_CLIENT"]}</td>";
            $output .= "<td>{$rand["MARCA_MASINA"]}</td>";
            $output .= "<td>{$rand["MODEL_MASINA"]}</td>";
            $output .= "<td>{$rand["DATA_PROGRAMARE"]}</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
    } else {
        $output .= "<p style='color: #E53935;'>Nu există programări în baza de date pentru clientul cu numele '$numeClient' și data programării mai mare sau egală cu '$dataProgramare'.</p>";
    }

    return $output;
}

$numeClient = 'Popescu';
$dataProgramare = '2024-01-01';

echo afiseazaProgramari($db, $numeClient, $dataProgramare);

$db->close();

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cererea in SQL</title>
</head>

<body>

    <h2 style="color: #333;">Cererea in SQL</h2>

    <pre>
        <?php
        $sqlQuery = "
        SELECT 
            C.NUME AS NUME_CLIENT,
            C.PRENUME AS PRENUME_CLIENT,
            M.MARCA AS MARCA_MASINA,
            M.MODEL AS MODEL_MASINA,
            P.DATA_PROGRAMARE
        FROM 
            CLIENT C
        JOIN 
            MASINA M ON C.CNP_CLIENT = M.CNP_CLIENT
        JOIN 
            PROGRAMARI P ON M.SERIE_SASIU = P.SERIE_SASIU
        WHERE 
            C.NUME = 'Popescu'
            AND P.DATA_PROGRAMARE >= TO_DATE('2024-01-01', 'YYYY-MM-DD');
        ";

        echo htmlspecialchars($sqlQuery);
        ?>
    </pre>

</body>

</html>
