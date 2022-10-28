<?
include_once 'navbar.inc.php'; 
use function MusicApp\Core\echoSidebar;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Search - MusicApp</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/public/css/home.css">
        <link rel="stylesheet" href="/public/css/lagu-detail.css">
    </head>
    <body>
        <? echoSidebar(); ?>
        <div class="content">
            <!-- Hero section start -->
            <section class="hero">
                <h1 class="big-header">Search</h1>
                <p>Apa yang ingin kamu cari?</p>
            </section>
            <!-- Hero section end -->

            <!-- List container start -->
            <section class="list-container">
                <? if ($songs): ?>
                <table id="song-list" class="content-table">
                    <tr>
                        <th>#</th>
                        <th>JUDUL</th>
                        <th>PENYANYI</th>
                        <th>TAHUN TERBIT</th>
                        <th>GENRE</th>
                    </tr>
                    <tr>
                        <form action="" method="GET">
                        <th><input type="submit" /></th>
                        <th>
                        <input type="text" name="judul" placeholder="Cari Judul" />
                          <select name="sort_judul"><option value="ASC">Ascending</option><option value="DESC">Descending</option></select>
                        </th>
                        <th><input type="text" name="penyanyi" placeholder="Cari Penyanyi" />
                        </th>
                        <th><input type="text" name="tahun" placeholder="Cari Tahun Terbit" />
                          <select name="sort_tahun"><option value="ASC">Ascending</option><option value="DESC">Descending</option></select></th>
                        <th>
                          <select name="genre">
                            <?
                            foreach ($genre as $g) {
                              echo "<option value='$g'>$g</option>";
                            } ?>
                          </select>
                        </th>
                        </form>
                    </tr>
                    <?php $i = 1; ?>
                    <?php foreach ($songs as $song) : ?>
                    <tr onclick="window.location.href = '/lagu/<?= $song->song_id ?>'">
                        <td class="number-play">
                            <div class="show-number"><?= $i; ?></div> 
                            <?php $i++; ?>
                            <div class="show-play">
                                <p>▶</p> 
                            </div>
                        </td>
                        <td class="mini-info">
                            <div class="mini-cover">
                                <img id="mini-cover" src="/public/image/<?= $song->image_path ?>" alt="" />
                            </div>
                            <div class="mini-desc">
                                <p id="mini-title" class="mini-title"><?= $song->judul ?></p>
                            </div>
                        </td>
                        <td>
                          <p id="mini-artist" class="mini-title">
                              <?= $song->penyanyi ?>
                          </p>
                        </td>
                        <td><?= substr($song->tanggal_terbit,0,4) ?></td>
                        <td><?= $song->genre ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <? else: ?>
                <p>There are no songs yet.</p>
                <? endif ?>
            </section>
            <!-- List container end -->
                </div>
    </body>
</html>