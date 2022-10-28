<? include __DIR__ . '/template.inc2.php';?>



<section class="content">
    <div class="content-middle">
        <div class="daftar-album-content">
            <div class="daftar-album-head">
                <span class="section-title">Daftar Lagu</span>
            </div>
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
                            <td class='track-title' ><img src='<? $lagu->image_path?>' /><?= $lagu->judul ?></td>
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

</body>

<script type="text/javascript" src="/public/js/form-lagu.js"></script>
</html>