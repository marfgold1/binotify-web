<?php
namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Database;
use MusicApp\Models\Song;

use function MusicApp\Core\remove;
use function MusicApp\Core\view;

class HomeController extends Controller {
    public function home() {
        $songs = Song::find('', [], 'ORDER BY song_id DESC LIMIT 10');
        usort($songs, fn($a, $b) => strcmp($a->judul, $b->judul));
        view('home', ['songs' => $songs]);
    }
    public function search() {
        $cond = '(judul LIKE ? OR penyanyi LIKE ? OR YEAR(tanggal_terbit) LIKE ?)';
        $params = [$_GET['judul'] ?? '', $_GET['penyanyi'] ?? '', $_GET['tahun'] ?? ''];
        $params = array_map((fn($param) => $param = '%' . $param . '%'), $params);
        if (isset($_GET['genre'])) {
            $cond .= ' AND genre = ?';
            $params[] = $_GET['genre'];
        }
        $sort = ['judul' => $_GET['sort_judul'] ?? null, 'tanggal_terbit' => $_GET['sort_tahun'] ?? null];
        $sort = array_map(function($k) {
            if ($k === 'ASC' || $k === 'DESC') return $k;
            return null;
        }, $sort);
        $sort['judul'] = $sort['judul'] ?? 'ASC';
        $sort = array_filter($sort);
        $songs = Song::find($cond, [...$params],
            'ORDER BY ' . implode(', ', array_map(fn($k) => $k . ' ' . $sort[$k] , array_keys($sort))) 
        );
        $genre = Database::get()->query('SELECT DISTINCT genre FROM song');
        die(var_dump($genre->fetchAll()));
        view('search', ['songs' => $songs, 'genre' => $genre]);
    }
}
?>