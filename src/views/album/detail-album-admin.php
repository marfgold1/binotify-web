<?php
include __DIR__ . '/template.inc1.php';
?>



<section class="content">
    <div class="content-middle">
        <div class="albums-content">
            <div class="album-actions">
                <form action="/album/<?=$album->album_id?>" method="post" enctype="multipart/form-data">
                    <div class='albums-head'>
                        <span class='section-title'> Album <?= $album->judul ?></span>
                    </div>
                    <div class='album'>
                        <div class='album-info'>
                            <div class='album-info-art'>
                                <img id="cover" src='<?= $album->image_path?>' />
                            </div>
                            <div class='album-info-meta'>
                                <span class='txt-field-grey'> <input type='text' name='penyanyi' required value='<?= $album->penyanyi ?>'></span>
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
                </form>
            </div>

        </div>
    </div>
    <button class="button-light save" onclick="window.location.href='/album/add/<?= $album->album_id?>'">Tambah lagu</button>
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
            foreach ($listLagu as $lagu) : ?>
                <form action="/album/remove/<?= $lagu->song_id?>" method="post">
                <tr class='track'>
                    <td ><a href='/song/<?= $lagu->song_id?>'><div class="track-text"> <?= $i ?></div></a></td>
                    <td ><a href='/song/<?= $lagu->song_id?>'><div class='track-title'> <img src='/public/img/cover2.jpg' /><?= $lagu->judul ?></div></a></td>
                    <td ><a href='/song/<?= $lagu->song_id?>'><div class='track-text'> <?= intdiv($lagu->duration, 60) . ":" . $lagu->duration % 60 ?></div></a></td>
                    <td ><a href='/song/<?= $lagu->song_id?>'><div class='track-button'> <button type="submit" class="delete">Delete</button></div></a></td>
                </tr>
                </form>
            <?php $i++;
            endforeach; ?>
        </tbody>
    </table>
    </div>
    </div>
</section>

</body>
<script type="text/javascript" src="/public/js/detail-album.js"></script>

</html>