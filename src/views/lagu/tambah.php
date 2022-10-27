<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Tambah Lagu</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="/public/js/duration_calc.js" crossorigin="anonymous" defer></script>
            <link rel="stylesheet" href="/public/css/lagu-detail.css">
        </head>
        <body>
            <div>
                <h1>Tambah Lagu</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label>Judul*</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name = "judul"
                                required
                            />
                        </li>
                        <li>
                            <label>Penyanyi</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name = "penyanyi"
                            />
                        </li>
                        <li>
                            <label>Tanggal Terbit*</label>
                            <input
                                type="date"
                                autocomplete="off"
                                name="tanggal_terbit"
                                required
                            />
                        </li>
                        <li>
                            <label>Genre</label>
                            <input
                                type="text"
                                autocomplete="off"
                                name="genre"
                            />
                        </li>
                        <li>
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
                        </li>
                        <li>
                            <label>File Cover Photo</label>
                            <input
                                type="file"
                                autocomplete="off"
                                id="image_path"
                                name="image_path"
                                accept="image/*"
                            />
                        </li>
                        <li>
                            <button type="submit">Tambah Lagu</button>
                        </li>
                    </ul>
                </form>
            </div>
        </body>
</html>