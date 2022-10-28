<?php
include __DIR__ . '/template.inc1.php'; ?>




<section class="content">
    <div class="content-middle">
        <div class="albums-content">
            <div class="album-actions">
                <div class='albums-head'>
                    <span class='section-title'> Album <?= $album->judul ?></span>
                </div>
                <div class='album'>
                    <div class='album-info'>
                        <div class='album-info-art'>
                            <img src='<? $album->image_path ?>' />
                        </div>
                        <div class='album-info-meta'>
                            <span class='txt-field-grey'> <?= $album->penyanyi ?></span>
                            <span class='txt-field-white'> <?= $album->judul ?></span>
                            <span class='txt-field-grey'><?= intdiv($album->total_duration, 60) . ":" . $album->total_duration % 60 ?></span>
                        </div>
                    </div>
                </div>
                <table class="album-tracks">
                    <thead class="tracks-heading">
                        <tr>
                            <th style="font:italic">#</th>
                            <th>Song</th>
                            <th>Length</th>
                        </tr>
                    </thead>
                    <tbody class="tracks">
                        <?php $i = 1;
                        foreach ($listLagu as $lagu) : ?>
                            <tr class='track'>
                                <td ><a href='/song/<?= $lagu->song_id?>'><div class="track-text"> <?= $i ?></div></a></td>
                                <td ><a href='/song/<?= $lagu->song_id?>'><div class='track-title'> <img src='<? $lagu->image_path?>' /><?= $lagu->judul ?></div></a></td>
                                <td ><a href='/song/<?= $lagu->song_id?>'><div class='track-text'> <?= intdiv($lagu->duration, 60) . ":" . $lagu->duration % 60 ?></div></a></td>
                            </tr>
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