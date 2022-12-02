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
                            <td class='track-text'> <?= $penyanyi["name"] ?></td>
                            <?php if ($listSubscribe):?>
                                <?php foreach ($listSubscribe as $sub) : 
                                 foreach ($sub as $subscriber) : ?>
                                    
                                    <form action="/penyanyi/<?= $penyanyi["user_id"]?>" method="post">
                                <?php if ($subscriber["creator_id"] != $penyanyi["user_id"]) : ?> 
                                <td class='track-button'><button type="submit" id="subscribe" onclick="">Subscribe</button></td>
                                </form>
                                    <?php else:
                                        if ($subscriber["status"] == "PENDING") :?>
                                            <td class='track-button'><button disabled id="subscribing" >Waiting</button></td>
                                        <?php elseif ($subscriber["status"] == "ACCEPTED") :?>
                                            <td class='track-button'><button disabled id="subscribed" >Subscribed</button></td>
                                            <td class='track-button'><button type="button" onclick="window.location.href='/penyanyi/<?= $penyanyi["user_id"] ?>'">View Lagu</button></td>
                                            <?php else: ?>
                                            <td class='track-button'><button disabled id="unsubscribing" >Rejected</button></td>
                                            <td class='track-button'><button disabled>Can't View</button></td>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <form action="/penyanyi/<?= $penyanyi["user_id"]?>" method="post">
                                <td class='track-button'><button type="submit" id="subscribe" onclick="">Subscribe</button></td>
                                </form>
                            <?php endif; ?>
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