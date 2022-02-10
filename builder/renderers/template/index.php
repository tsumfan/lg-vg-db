<!DOCTYPE html>
<html>

<body>
<?php
$games = $this->graph->getAllByType('Game');

foreach($games as $game) {
    echo '<div class="game">'.$game->name.'</div>';
}

?>
</body>
</html>