<?php
namespace MusicApp\Models;

use MusicApp\Core\Models\Field;
use MusicApp\Core\Models\Model;
use PDO;

class Album extends Model {
    protected static string $table = 'album';

    #[Field([Field::C_AUTO_INCREMENT => true, Field::C_NULLABLE => false, Field::C_PRIMARY => true], PDO::PARAM_INT)]
    protected ?int $album_id = null;
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $judul = null;
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $penyanyi = null;
    #[Field([Field::C_NULLABLE => false], PDO::PARAM_INT)]
    protected ?string $total_duration = null;
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $image_path = null;
    #[Field([Field::C_NULLABLE => false])]
    protected ?string $tanggal_terbit = null;
    #[Field([Field::C_NULLABLE => true])]
    protected ?string $genre = null;
}

Album::load();
?>