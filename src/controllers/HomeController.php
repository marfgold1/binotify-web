<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Models\Song;

use function MusicApp\Core\view;

class HomeController extends Controller {
    public function home() {
        $songs = Song::find('', [], 'ORDER BY song_id DESC LIMIT 10');
        usort($songs, fn($a, $b) => strcmp($a->judul, $b->judul));
        view('home', ['songs' => $songs]);
    }
}
?>