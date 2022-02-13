<?php
use GraphRenderer\Helpers;
$image = $entry->getFirstImage();
$thumbsrc = '';
if ( $image && is_file( $this->getDataLocation($image->name) ) ) {
    $this->ensureOutputDirectory('thumbs');
    list($output, $return_code) = Helpers::resizeImage(
        $this->getDataLocation($image->name),
        $this->getOutputLocation($thumbsrc = 'thumbs/'.md5($entry->name).'.jpg'),
        '400',
        '200'
    );
    $thumbcaption = $image->caption ?: "Thumbnail for ".$entry->name;
    if ( $return_code != 0 ) {
        throw new \Exception('Could not generate thumbname for '.$entry->name);
    }
    $genre = null;
    if ( !empty($entry->genre) ) {
        $genre = is_array($entry->genre) ? $entry->genre : [ $entry->genre ];
    }
    $platform = null;
    if ( !empty($entry->platform) ) {
        $platform = is_array($entry->platform) ? $entry->platform : [ $entry->platform ];
    }

}
?>
<div class="index-entry">
    <?php if($thumbsrc): ?>
        <img src="<?=$thumbsrc?>" alt="<?=Helpers::esc($thumbcaption)?>">
    <?php endif; ?>
    <h2><?=Helpers::esc($entry->name)?></h2>
    <?php if (!empty($genre)): ?>
        <div class="genre-list">
            <?php foreach($genre as $tag_entry): ?>
                <?php if(is_object($tag_entry)): ?>
                    <span class="linked-genre" data-genre-id="<?=Helpers::esc($tag_entry->id)?>">
                        <a href="<?=Helpers::esc($tag_entry->id)?>.html">
                            <?=Helpers::esc($tag_entry->name)?>
                        </a>
                    </span>
                <?php else : ?>
                    <span class="unlinked-genre"><?=Helpers::esc($tag_entry);?></span>
                <?php endif; ?>
            <?php endforeach;?>

            <?php foreach($platform as $tag_entry): ?>
                <?php if(is_object($tag_entry)): ?>
                    <span class="linked-genre" data-genre-id="<?=Helpers::esc($tag_entry->id)?>">
                        <a href="<?=Helpers::esc($tag_entry->id)?>.html">
                            <?=Helpers::esc($tag_entry->name)?>
                        </a>
                    </span>
                <?php else : ?>
                    <span class="unlinked-genre"><?=Helpers::esc($tag_entry);?></span>
                <?php endif; ?>
            <?php endforeach;?>
        </div>
    <?php endif; ?>
</div>