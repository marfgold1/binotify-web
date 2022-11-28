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
    <link rel="stylesheet" href="/public/css/list-penyanyi.css">
   
</head>

<body id="start">
<? echoSidebar(); ?>
<div class="middle">
    <section class="header">
        <div class="title">
            <h1>List Penyanyi Premium</h1>
        </div>
    </section>

<section class="content">
    <div class="content-middle">
        <div class="daftar-album-content">
        <?php if ($listPenyanyi): ?>
            <table class="album-tracks">
                <thead class="tracks-heading">
                    <tr>
                        <th style="font:italic">#</th>
                        <th>Penyanyi</th>
                        <th>Subscribe</th>
                        <th>List Lagu</th>
                    </tr>
                </thead>
                <tbody class="tracks">
                    <?php $i = 1; 
                    foreach ($listPenyanyi as $penyanyi) : 
                    ?>
                        <tr class='track'>
                            <td class="track-text"><?= $i ?></td>
                            <td class='track-text'> <?= $penyanyi->penyanyi ?></td>
                            <!-- ?php if ($listSubscribe.status = "ACCEPTED" OR "PENDING"): ?-->
                            <td class='track-button'><button onclick=subscribe() id="unsubscribing" class="delete" id="unsubscribing">Unsubscribe</button></td>
                            <td class='track-button'><button onclick="window.location.href='/penyanyi/<?= $penyanyi->penyanyi ?>'" class="delete">View Lagu</button></td>
                            <!-- ?php else: ?
                            <td><div class='track-button'><button onclick=subscribe() id="subscribing" class="delete">Subscribe</button></div></td>
                            <td class="track-text">Belum Subscribe</td>-->
                        </tr>
                    <?php $i++;
                    endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="content-bottom">
    </div>
</section>
</div>
</body>

<script type="text/javascript" src="/public/js/form-lagu.js"></script>
</html>