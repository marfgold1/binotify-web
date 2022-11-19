<?
    include_once 'navbar.inc.php'; 
    include_once 'template.inc.php';
    use function MusicApp\Core\echoSidebar;
    starthtml('Home');
?>
        <div class = "page-container">
            <? echoSidebar() ?>
            <div class="content">
                <!-- Hero section start -->
                <section class="hero">
                    <h1 class="big-header">Daftar User</h1>
                    <p>Menampilkan Daftar User</p>
                </section>
                <!-- Hero section end -->

                <!-- List container start -->
                <section class="list-container">
                    <? if ($users): ?>
                    <table id="song-list" class="content-table">
                        <tr>
                            <th>ID</th>
                            <th>NAMA</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                        </tr>
                        <?php foreach ($users as $user) : ?>
                        <tr>
                            <td>
                                <p><?= $user->user_id; ?></p> 
                            </td>
                            <td>
                                <p><?= $user->name?></p>
                            </td>
                            <td><?= $user->username?></td>
                            <td><?= $user->email?></td>
                            <td><?= $user->isAdmin ? 'Admin' : 'User' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <? else: ?>
                    <p class="no-songs">Belum ada daftar pengguna.</p>
                    <? endif ?>
                </section>
                <!-- List container end -->
            </div>
        </div>
<? endhtml(); ?>