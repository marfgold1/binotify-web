<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/public/css/home.css">
        </head>
        <body>
        <!-- Hero section start -->
        <section class="hero">
            <h1 class="big-header">Home</h1>
            <!-- <p>Menampilkan 10 Lagu Terbaru</p> -->
        </section>
        <!-- Hero section end -->

        <!-- List container start -->
        <section class="list-container">
            <table id="song-list" class="content-table">
                <tr>
                    <th>#</th>
                    <th>JUDUL</th>
                    <th>TAHUN TERBIT</th>
                    <th>GENRE</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach ($data['songs'] as $song) : ?>
                <tr>
                    <td class="number-play">
                        <div class="show-number"><?= $i; ?></div> 
                        <?php $i++; ?>
                        <div class="show-play">
                            <a href="">▶</a> 
                        </div>
                    </td>
                    <td class="mini-info">
                        <div class="mini-cover">
                            <img id="mini-cover" src="<?= $song['image_path'] ?>" alt="" />
                        </div>
                        <div class="mini-desc">
                            <p id="mini-title" class="mini-title"><?= $song['judul'] ?></p>
                            <p id="mini-artist" class="mini-artist">
                                <?= $song['penyanyi'] ?>
                            </p>
                        </div>
                    </td>
                    <td><?= substr($song['tanggal_terbit'],0,4) ?></td>
                    <td><?= $song['genre'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
        <!-- List container end -->
