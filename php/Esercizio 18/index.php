<?php
$provincia_sigla = [
    "AG" => "Agrigento",
    "AL" => "Alessandria",
    "AN" => "Ancona",
    "AO" => "Aosta",
    "AR" => "Arezzo",
    "AP" => "Ascoli Piceno",
    "AT" => "Asti",
    "AV" => "Avellino",
    "BA" => "Bari",
    "BT" => "Barletta-Andria-Trani",
]

?>

<!DOCTYPE html>
<html lang="en">
<body>
    <form action="index.php" method="get">
        <label for="provincia">Provincia:</label>
        <select id="provincia" name="provincia">
            <option value="">-- Seleziona una provincia --</option>
            <?php 
            foreach($provincia_sigla as $sigla => $nome) {
                echo "<option value='$sigla'>$nome ($sigla)</option>";
            }
            ?>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>
</html>