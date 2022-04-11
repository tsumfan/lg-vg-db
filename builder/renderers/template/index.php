<?php
use GraphRenderer\Helpers;?>
<!DOCTYPE html>
<html>
<head>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (!empty($node)): ?>
        <title><?=Helpers::esc($node->{"@type"})?> | <?=Helpers::esc($node->name ?: "Untitled")?></title>
    <?php else: ?>
        <title><?=Helpers::projectTitle();?></title>
    <?php endif; ?>
</head>
<body>

<?php include 'globalnav.php'; ?>

<?php if ( !empty($node) ): ?>
    <div class="content-wrap meta-area">
        <?php if(!empty($node->name)): ?><h1><?=Helpers::esc($node->name)?> <span class="type-header"><?=Helpers::esc($node->{"@type"})?></span></h1><?php endif; ?>
        <?php if(!empty($node->description)): ?><p class="description"><?=Helpers::esc($node->description)?></p><?php endif; ?>
    </div>
<?php endif; ?>

<div class="content-wrap game-area">

<?php
foreach($entries as $entry) {
    include 'index-entry.php';
}
?>
</div>
</body>
</html>