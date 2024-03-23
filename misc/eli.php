<!-- index -->
<?php

$sql = "SELECT name FROM locations JOIN images ON locations.id = images.location_id WHERE images.plant_.id IS NULL";

?>

<!-- plants for a given location -->
<?php

$sql = "SELECT name, image FROM plants WHERE location_id = $location_id";

?>

<!-- plants -->
<?php

$sql = "SELECT name, image FROM plants";

?>

<!-- logs -->
<?php

$sql = "
use waterlogged;
SELECT
    p.name AS PlantName,
    l.date_watered AS DateWatered
FROM
    waterlogged.logs as l
INNER JOIN user_plants up ON l.user_plant_id = up.id
INNER JOIN plants as p ON up.plant_id = p.id
ORDER BY
    l.date_watered DESC;
";

?>

