<?
include_once __DIR__ . '/../navbar.inc.php';

use function MusicApp\Core\echoSidebar;
use function MusicApp\Core\get;
use function MusicApp\Core\has;

?>
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Tambah Lagu</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="/public/js/duration_calc.js" crossorigin="anonymous" defer></script>
            <link rel="stylesheet" href="/public/css/lagu-tambah.css">
        </head>
        <body>
            <!-- Sidebar start -->
            <!-- 
                User: home, search, daftar album, logout
                admin: home, search, tambah lagu, tambah album, daftar album, logout
                Non-user: home, search, login, register
             -->
            <? echoSidebar(); ?>
            <?
                $flash = get('flash');
                $errors = $flash['errors'] ?? [];
                $values = $flash['values'] ?? [];
            ?>
            <!-- Sidebar end -->
            <div class="modal">
                <div class="text">Tambah Lagu</div>
                <form action="/lagu" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="data">
                            <label>Judul*</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name = "judul"
                                required
                                value="<?= $values['judul'] ?? '' ?>"
                            />
                        </div>
                        <div class="data">
                            <label>Penyanyi</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name = "penyanyi"
                                value="<?= $values['penyanyi'] ?? '' ?>"
                            />
                        </div>
                        <div class="data">
                            <label>Tanggal Terbit*</label>
                            <input
                                type="date"
                                autocomplete="off"
                                name="tanggal_terbit"
                                required
                                value="<?= $values['tanggal_terbit'] ?? '' ?>"
                            />
                        </div>
                        <div class="data">
                            <label>Genre</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name="genre"
                                value="<?= $values['genre'] ?? '' ?>"
                            />
                        </div>
                        <div class="data">
                            <label>File Lagu*</label>
                            <input
                                type="file"
                                id="audio_path"
                                autocomplete="off"
                                name="audio_path"
                                accept="audio/*"
                                required
                                onchange= "audioPreview()"
                            />
                            <input
                                type="hidden"
                                id="duration-helper"
                                name="duration"
                            /> 
                        </div>
                        <div class="data">
                            <label>File Cover Photo</label>
                            <input
                                type="file"
                                autocomplete="off"
                                id="image_path"
                                name="image_path"
                                accept="image/*"
                            />
                        </div>
                        <button type="submit" class="btn">Tambah Lagu</button>
                    </div>
                </form>
            </div>
            <? 
                if(has('flash'))
                    echo '<script>alert("Error: ' . implode('\n', array_map(fn($k) => $k . ':' . $errors[$k], array_keys($errors))) . '")</script>';
            ?>
        </body>
</html>