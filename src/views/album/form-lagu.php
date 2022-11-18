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
    <link rel="stylesheet" href="/public/css/form-lagu.css">
   
</head>

<body id="start">
<? echoSidebar(); ?>
<div class="middle">
    <section class="header">
        <div class="title">
            <h1>Tambah Lagu</h1>
        </div>
    </section>

<section class="content">
    <div class="content-middle">
        <div class="daftar-album-content">
            <table class="album-tracks">
                <thead class="tracks-heading">
                    <tr>
                        <th style="font:italic">#</th>
                        <th>Song</th>
                        <th>Genre</th>
                        <th>Length</th>
                    </tr>
                </thead>
                <tbody class="tracks">
                    <?php $i = 1;
                    foreach ($listLagu as $lagu) : ?>
                    <form action="/album/add/<?= $album_id?>/<?= $lagu->song_id?> " method="post">
                        <tr class='track' onclick="selectLagu()">
                            <td class="track-text"><?= $i ?></td>
                            <td class='track-title' ><img src='/public/image/<? $lagu->image_path?>' /><?= $lagu->judul ?></td>
                            <td class='track-text'> <?= $lagu->genre ?></td>
                            <td class='track-text'><?= intdiv($lagu->duration, 60) . ":" . $lagu->duration % 60 ?></td>
                        </tr>
                    </form>
                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="content-bottom">
    </div>
</section>
</div>
</body>

<script type="text/javascript" src="/public/js/form-lagu.js"></script>
</html>