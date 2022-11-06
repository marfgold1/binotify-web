<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Models\Validation;
use MusicApp\Models\Album;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\route;
use function MusicApp\Core\view;

class LaguController extends Controller {
    private const AUDIO_DIR = __DIR__ . '/../public/audio/';
    private const IMAGE_DIR = __DIR__ . '/../public/image/';

    public function __construct() {
        if (!is_dir(self::AUDIO_DIR)) mkdir(self::AUDIO_DIR);
        if (!is_dir(self::IMAGE_DIR)) mkdir(self::IMAGE_DIR);
    }

    public function create() {
        view('lagu.tambah');
    }

    public function store() {
        $flash = [
            'errors' => [],
            'values' => [
                'judul' => $_POST['judul'] ?? null,
                'penyanyi' => $_POST['penyanyi'] ?? null,
                'tanggal_terbit' => $_POST['tanggal_terbit'] ?? null,
                'genre' => $_POST['genre'] ?? null,
                'duration' => $_POST['duration'] ?? null,
            ]
        ];
        // Validasi
        $song = new Song();
        $song->set($flash['values']);
        $flash['errors'] = $song->validate([
            'judul' => [Validation::REQUIRED, Validation::MAX => 64],
            'penyanyi' => [Validation::MAX => 64],
            'tanggal_terbit' => [Validation::REQUIRED, Validation::TYPE => Validation::T_DATE],
            'genre' => [Validation::MAX => 64],
            'duration' => [Validation::REQUIRED, Validation::TYPE => Validation::T_INT],
        ]);

        if (count($flash['errors']) > 0) {
            back()->flash($flash);
            return;
        }

        // Pengecekan $_FILES
        $namaFileAudio = time() . '.' . pathinfo($_FILES['audio_path']['name'], PATHINFO_EXTENSION);
        $tmpAudio = $_FILES['audio_path']['tmp_name'];

        // pindahkan file 
        if (!move_uploaded_file($tmpAudio, self::AUDIO_DIR . $namaFileAudio))
            $flash['errors']['audio_path'] = 'Failed to upload audio!';

        $namaFileImage = null;
        if (isset($_FILES['image_path']) && is_uploaded_file($_FILES['image_path']['tmp_name'])) {
            $namaFileImage = time() . '.' . pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION);
            $tmpImage = $_FILES['image_path']['tmp_name'];
            if (!move_uploaded_file($tmpImage, self::IMAGE_DIR . $namaFileImage))
                $flash['errors']['image_path'] = 'Failed to upload image!';
        }

        if (count($flash['errors']) > 0) {
            back()->flash($flash);
            return;
        }
            
        $song->audio_path = $namaFileAudio;
        $song->image_path = $namaFileImage;
        $song->save();
        // Alert berhasil menambah lagu
        route('lagu.detail', ['id' => $song->song_id]);
        //back()->flash('Berhasil menambah lagu');
    }

    public function show($id) {
        $song = Song::get($id);
        if ($song === null) {
            route('home');
        } else {
            view('lagu.detail', ['song' => $song]);
        }
    }

    private function deleteOldFile($audioPath=null, $imagePath=null) {
        if ($audioPath && file_exists(self::AUDIO_DIR . $audioPath))
            unlink(self::AUDIO_DIR . $audioPath);
        if ($imagePath && file_exists(self::IMAGE_DIR . $imagePath))
            unlink(self::IMAGE_DIR . $imagePath);
    }

    public function delete($id) {
        $song = Song::get($id);
        if ($song === null) {
            route('home');
        } else {
            $song->delete();
            $this->deleteOldFile($song->audio_path, $song->image_path);
            route('home');
        }
    }

    public function update($id) {
        $song = Song::get($id);
        if ($song === null) {
            route('home');
        } else {
            $flash = [
                'errors' => [],
                'values' => [
                    'judul' => $_POST['judul'] ?? null,
                    'tanggal_terbit' => $_POST['tanggal_terbit'] ?? null,
                    'genre' => $_POST['genre'] ?? null,
                    'duration' => $_POST['duration'] ? intval($_POST['duration']) : null,
                ]
            ];
            $flash['values'] = array_filter($flash['values'], function($value) {
                return $value !== null;
            });

            $album = null;
            if ($song->album_id !== null && isset($flash['values']['duration'])) {
                $album = Album::get($song->album_id);
                $album->total_duration -= $song->duration;
                $album->total_duration += $flash['values']['duration'];
            }

            // Validasi
            $song->set($flash['values']);
            $flash['errors'] = $song->validate([
                'judul' => [Validation::REQUIRED, Validation::MAX => 64],
                'tanggal_terbit' => [Validation::REQUIRED, Validation::TYPE => Validation::T_DATE],
                'genre' => [Validation::MAX => 64],
                'duration' => [Validation::TYPE => Validation::T_INT],
            ]);

            if (count($flash['errors']) > 0) {
                back()->flash($flash);
                return;
            }

            $namaFileAudio = null;
            if (isset($_FILES['audio_path']) && is_uploaded_file($_FILES['audio_path']['tmp_name'])) {
                $namaFileAudio = time() . '.' . pathinfo($_FILES['audio_path']['name'], PATHINFO_EXTENSION);
                $tmpAudio = $_FILES['audio_path']['tmp_name'];
                if (!move_uploaded_file($tmpAudio, self::AUDIO_DIR . $namaFileAudio))
                    $flash['errors']['audio_path'] = 'Failed to upload audio!';
            }
            if (isset($_FILES['image_path']) && is_uploaded_file($_FILES['image_path']['tmp_name'])) {
                $namaFileImage = time() . '.' . pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION);
                $tmpImage = $_FILES['image_path']['tmp_name'];
                if (move_uploaded_file($tmpImage, self::IMAGE_DIR . $namaFileImage))
                    $this->deleteOldFile(null, $song->image_path);
                else
                    $flash['errors']['image_path'] = 'Failed to upload image!';
                $song->image_path = $namaFileImage;
            }

            if (count($flash['errors']) > 0) {
                back()->flash($flash);
                return;
            }

            if ($namaFileAudio) { // defer for accidentally deleting if image failed to upload
                $this->deleteOldFile($song->audio_path, null);
                $song->audio_path = $namaFileAudio;
            }
            $song->save();
            if ($album) // defer saving for total_duration change album
                $album->save();

            // Alert berhasil mengubah lagu
            back()->flash('Berhasil mengubah lagu!');
        }
    }
}
?>