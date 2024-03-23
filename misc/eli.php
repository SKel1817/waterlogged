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

$sql = "SELECT * FROM logs WHERE plant_id in (SELECT id FROM user_plants WHERE user_id = $user_id)";

?>