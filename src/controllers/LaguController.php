<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Song;
use MusicApp\Models\User;

use function MusicApp\Core\back;
use function MusicApp\Core\route;
use function MusicApp\Core\send;
use function MusicApp\Core\view;

class LaguController extends Controller {
    public function tambahForm() {
        view('lagu.tambah');
    }

    public function tambah(){
        // Pengecekan $_FILES
        $namaFileAudio = time() . $_FILES['audio_path']['name'];
        $namaFileImage = time() . $_FILES['image_path']['name'];
        $tmpAudio = $_FILES['audio_path']['tmp_name'];
        $tmpImage = $_FILES['image_path']['tmp_name'];

        // pindahkan file 
        move_uploaded_file($tmpAudio, __DIR__ . '/../public/audio/' . $namaFileAudio);
        move_uploaded_file($tmpImage, __DIR__ . '/../public/image/' . $namaFileImage);
        
        $song = new Song();
        $song->judul = $_POST['judul'];
        $song->penyanyi = $_POST['penyanyi'];
        $song->tanggal_terbit = $_POST['tanggal_terbit'];
        $song->genre = $_POST['genre'];
        $song->duration = $_POST['duration'];
        $song->audio_path = $namaFileAudio;
        $song->image_path = $namaFileImage;

        $song->save();
        // Alert berhasil menambah lagu
        echo "<script>alert('Berhasil menambah lagu!'); window.location.href = '/lagu/tambah';</script>";
    }

    public function detail($id) {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            view('lagu.detail', ['song' => $song])->with(['sess' => 'anjay']);
        }
    }

    public function hapus($id)
    {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            $song->delete();
        }
    }

    public function ubah($id)
    {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            // Pengecekan $_FILES
            $namaFileAudio = time() . $_FILES['audio_path']['name'];
            $namaFileImage = time() . $_FILES['image_path']['name'];
            $tmpAudio = $_FILES['audio_path']['tmp_name'];
            $tmpImage = $_FILES['image_path']['tmp_name'];

            // pindahkan file 
            move_uploaded_file($tmpAudio, __DIR__ . '/../public/audio/' . $namaFileAudio);
            move_uploaded_file($tmpImage, __DIR__ . '/../public/image/' . $namaFileImage);
            // Hapus file lama
            unlink(__DIR__ . '/../public/audio/' . $song->audio_path);
            unlink(__DIR__ . '/../public/image/' . $song->image_path);

            $song->judul = $_POST['judul'];
            $song->tanggal_terbit = $_POST['tanggal_terbit'];
            $song->genre = $_POST['genre'];
            $song->duration = $_POST['duration'];
            $song->audio_path = $namaFileAudio;
            $song->image_path = $namaFileImage;

            $song->save();

            // Alert berhasil mengubah lagu
            echo "<script>alert('Berhasil mengubah lagu!'); window.location.href = '/lagu/$id';</script>";
            view('lagu.detail', ['song' => $song])->with(['sess' => 'anjay']);

        }
        
    }


}
?>