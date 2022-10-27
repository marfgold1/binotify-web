<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\send;
use function MusicApp\Core\route;
use function MusicApp\Core\view;

class DetailLaguController extends Controller {
    public function index() {
        send(__DIR__ . '/HomeController.php');
    }

    public function get($id) {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            // view('detaillagu', ['song' => $song])->with(['sess' => 'anjay']);
        }
    }

    public function delete($id)
    {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            $song->delete();
        }
    }

    public function update($id)
    {
        $song = Song::get($id);
        if ($song === null) {
            // back()->withErrors(['user' => 'User not found']);
        } else {
            // view('detaillagu', ['song' => $song])->with(['sess' => 'anjay']);
            $song->judul = $_POST['judul'];
            $song->tanggal_terbit = $_POST['tanggal_terbit'];
            $song->genre = $_POST['genre'];
            $song->duration = $_POST['duration'];
            $song->audio_path = '/public/audio/' . $_POST['audio_path'];
            $song->image_path = '/public/image/' . $_POST['image_path'];
            $song->save();
            route('detaillagu', ['id' => $song->id]);
        }
        
    }


}

?>