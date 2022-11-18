<?
include_once __DIR__ . '/../navbar.inc.php';

use function MusicApp\Core\echoSidebar;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Binofity</title>

    <link rel="stylesheet" href="/public/css/header.css">
    <link rel="stylesheet" href="/public/css/detail-album.css">
   
</head>

<body id="start">
<? echoSidebar(); ?>
<div class="middle">
    <section class="header">
        <div class="title">
            <h1>Album <?= $album->judul ?></h1>
        </div>
    </section>



<section class="content">
    <div class="content-middle">
        <div class="albums-content">
            <div class="album-actions">
                <?php if ($admin): ?>
                <form action="/album/<?=$album->album_id?>" method="post" enctype="multipart/form-data">
                    <?php endif; ?>
                    <div class='album'>
                        <div class='album-info'>
                            <div class='album-info-art'>
                                <img id="cover" src='/public/image/<?= $album->image_path?>' />
                            </div>
                            <div class='album-info-meta'>
                                <?php if ($admin): ?>
                                <span class='txt-field-grey'> <?= $album->penyanyi ?></span>
                                <span class='txt-field-white'> <input type='text' name='judul' required value='<?= $album->judul ?>'></span>
                                <span class='txt-field-grey'><?= intdiv($album->total_duration, 60) . ":" . $album->total_duration % 60 ?></span>
                                <div class="input-file">
                                    <label for="file-upload" class="custom-file-upload">
                                        Ganti Cover
                                    </label>
                                    <input id="file-upload" accept="img/*" type="file" name="berkas" />
                                </div>
                                <div class="set">
                                    <input type="submit" value="Save edit" name="update-button">
                                    <button type="button" onclick="deleteAlbum();" class="delete-button" id="delete">Delete album</button>
                                </div>
                                <?php else: ?>
                                    <span class='txt-field-grey'> <?= $album->penyanyi ?></span>
                                    <span class='txt-field-white'> <?= $album->judul ?></span>
                                    <span class='txt-field-grey'><?= intdiv($album->total_duration, 60) . ":" . $album->total_duration % 60 ?></span>
                                <?php endif; ?>
                                </div>
                                </div>
                                </div>
                <?php if ($admin): ?>
                </form>
                <?php endif; ?>
            </div>

    <?php if ($admin): ?>
    <button class="button-light save" onclick="window.location.href='/album/add/<?= $album->album_id?>'">Tambah lagu</button>
    <?php endif; ?>
    <table class="album-tracks">
        <thead class="tracks-heading">
            <tr>
                <th style="font:italic">#</th>
                <th>Song</th>
                <th>Length</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="tracks">
            <?php $i = 1;
            if($listLagu):
            foreach ($listLagu as $lagu) : ?>
                <?php if ($admin): ?>
                <form action="/album/<?= $lagu->album_id ?>/delete/<?= $lagu->song_id?>" method="post">
                <tr class='track'>
                    <td ><a href='/lagu/<?= $lagu->song_id?>'><div class="track-text"> <?= $i ?></div></a></td>
                    <td ><a href='/lagu/<?= $lagu->song_id?>'><div class='track-title'> <img src='/public/image/<?= $lagu->image_path ?>' /><?= $lagu->judul ?></div></a></td>
                    <td ><a href='/lagu/<?= $lagu->song_id?>'><div class='track-text'> <?= intdiv($lagu->duration, 60) . ":" . $lagu->duration % 60 ?></div></a></td>
                    <td ><a href='/lagu/<?= $lagu->song_id?>'><div class='track-button'> <button type="submit" class="delete">Delete</button></div></a></td>
                </tr>
                </form>
                <?php else: ?>
                    <tr class='track'>
                        <td ><a href='/lagu/<?= $lagu->song_id?>'><div class="track-text"> <?= $i ?></div></a></td>
                        <td ><a href='/lagu/<?= $lagu->song_id?>'><div class='track-title'> <img src='<? $lagu->image_path?>' /><?= $lagu->judul ?></div></a></td>
                        <td ><a href='/lagu/<?= $lagu->song_id?>'><div class='track-text'> <?= intdiv($lagu->duration, 60) . ":" . $lagu->duration % 60 ?></div></a></td>
                    </tr>
                <?php endif; ?>
            <?php $i++;
            endforeach; endif; ?>
        </tbody>
    </table>
    </div>
    </div>
</section>
</div>
</body>
<script type="text/javascript" src="/public/js/detail-album.js"></script>

</html>