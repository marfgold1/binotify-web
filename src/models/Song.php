<?php
namespace MusicApp\Models;

use MusicApp\Core\Models\Field;
use MusicApp\Core\Models\ForeignKey;
use MusicApp\Core\Models\Model;
use MusicApp\Core\Models\Setter;
use PDO;

class Song extends Model {
    protected static string $table = 'song';

    #[Field([Field::C_AUTO_INCREMENT => true, Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $song_id = null; // song_id int NOT NULL PRIMARY KEY AUTO_INCREMENT
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $judul = null; // Judul char(64) NOT NULL
    #[Field()]
    protected ?string $penyanyi = null; // Penyanyi char(64)
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $tanggal_terbit = null; // Tanggal_Terbit date NOT NULL
    #[Field()]
    protected ?string $genre = null; // Genre char(64)
    #[Field([Field::C_NULLABLE => false], PDO::PARAM_INT)]
    protected ?int $duration = null; // Duration int NOT NULL
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $audio_path = null; // Audio_path char(256) NOT NULL
    #[Field()]
    protected ?string $image_path = null; // Image_path char(256)
    #[Field([], PDO::PARAM_INT)]
    #[ForeignKey('album', 'album_id')]
    protected ?int $album_id = null; // Album_id int REFERENCES album(album_id)
}

Song::load();
?>