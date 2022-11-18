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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="/public/css/header.css">
    <link rel="stylesheet" href="/public/css/daftar-album.css">
   
</head>

<body id="start">
<? echoSidebar(); ?>
<div class="middle">
    <section class="header">
        <div class="title">
            <h1>Daftar Album</h1>
        </div>
    </section>
<section class="content">
    <div class="content-middle">
        <div class="daftar-album-content">
            <div id="tabel">
            </div>
        </div>
    </div>
    <div class="content-bottom">
        <div class="pagination" id="pagIn">
        </div>
    </div>
</section>
</div>
</body>

<script type="text/javascript" src="/public/js/daftar-album.js"></script>

</html>
