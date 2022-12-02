<?
    include_once __DIR__ . '/../navbar.inc.php';
    use function MusicApp\Core\echoSidebar;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>$title - MusicApp</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/public/js/input.js" crossorigin="anonymous" defer></script>
        <script src="/public/js/duration_calc.js" crossorigin="anonymous" defer></script>
        <script src="/public/js/dropdown.js" crossorigin="anonymous" defer></script>
        <link rel="stylesheet" href="/public/css/list-lagu.css">
    </head>
    <body> 
    <div class = "page-container">
        <? echoSidebar() ?>
        <div class="content">
            
            <section class="hero">
                <h1 class="big-header">List Lagu Premium</h1>
                
            </section>

            <section class="list-container">
                <? if ($listLagu): ?>
                <table id="song-list" class="content-table">
                    <tr>
                        <th>#</th>
                        <th>JUDUL</th>
                        <th> Play </th>
                    </tr>
                    <?php $i = 1; ?>
                    <?php foreach ($listLagu as $song) : ?>
                    <tr>
                        <td>
                            <div class="show-number"><?= $i; ?></div> 
                            <?php $i++; 
                            ?>
                        </td>
                        <td class='track-text'> <?= $song["judul"] ?></td>
                        <td><audio id="player" src=<?="http://{$_ENV['REST_HOST']}/{$song['audio_path']}" ?> controls preload='auto'></audio></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <? else: ?>
                <p class="no-songs">Lagu belum tersedia.</p>
                <? endif ?>
            </section>
        </div>
    </div>
    </body>
</html>