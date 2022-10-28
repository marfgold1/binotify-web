<?php

namespace MusicApp\Controllers;

use MusicApp\Core\Controller;
use MusicApp\Core\Models\Pagination;
use MusicApp\Models\Album;
use MusicApp\Models\Song;

use function MusicApp\Core\back;
use function MusicApp\Core\get;
use function MusicApp\Core\has;
use function MusicApp\Core\route;
use function MusicApp\Core\set;
use function MusicApp\Core\view;

class AlbumController extends Controller
{
    private const IMAGE_DIR = __DIR__ . '/../public/image/';

    public function __construct() {
        if (!is_dir(self::IMAGE_DIR)) mkdir(self::IMAGE_DIR);
    }

    public function daftarAlbum()
    {
        view('album.daftar-album');
    }

    public function detailAlbum($id)
    {
        $listSong = Song::find('album_id = ?', [$id]);
        $album = Album::get($id);
        if (has('user')) {
            if (get('user')->role == 'admin') {
                view('album.detail-album-admin', ['listLagu' => $listSong, 'album' => $album]);
            }
        }
        view('album.detail-album-user', ['listLagu' => $listSong, 'album' => $album]);
    }

        
    public function formAlbum()
    {
        view('album.form-album');
    }

    public function formLagu($album_id)
    {
        $penyanyi = Album::get($album_id)->penyanyi;
        $lagu = Song::find('penyanyi = ? AND album_id IS NULL', [$penyanyi]);
        view('album.form-lagu', ['listLagu' => $lagu, 'album_id' => $album_id]);
    }

    public function showListAlbum()
    {
        $album = Album::paginate(
            '',
            [],
            'ORDER BY judul ASC',
            [
                'page' => intval($_GET['page'] ?? 1),
                'limit' => 10
            ]
        );
        echo json_encode($album ?? []);
    }

    public function changeData($album_id)
    {
        $album = Album::get($album_id);
        $album->penyanyi = $_POST['penyanyi'];
        $album->judul = $_POST['judul'];
        if (isset($_FILES['berkas']) && is_uploaded_file($_FILES['berkas']['tmp_name'])) {
            $namaFile = time() . '-albumart.' . pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['berkas']['tmp_name'], self::IMAGE_DIR . $namaFile);
            $album->image_path = $namaFile;
        }
        $album->save();
        route('album.daftar-album')->with(['success' => 'Album berhasil diubah']);
    }
    public function hapusAlbum($album_id)
    {
        $album = Album::get($album_id);
        $album->delete();
        route('album.daftar-album')->with(['success' => 'Album berhasil dihapus']);
        exit;
    }

    public function tambahAlbum()
    {
        $namaFile = $_FILES['image_path']['name'];
        $fileLocation = "/public/img/" . $namaFile;
        $album = new Album();
        $album->penyanyi = $_POST['penyanyi'];
        $album->judul = $_POST['judul'];
        $album->image_path = $fileLocation;
        $album->tanggal_terbit = $_POST['tanggal_terbit'];
        $album->total_duration = 0;
        $album->save();
        route('album.daftar-album')->with(['success' => 'Album berhasil ditambahkan']);
    }

    public function tambahLagu($album_id, $song_id)
    {

        $lagu = Song::get($song_id);
        $lagu->album_id = $album_id;
        $lagu->save();
    }

    public function hapusLagu($song_id)
    {
        $lagu = Song::get($song_id);
        $lagu->album_id = null;
        $lagu->save();
        back()->with(['success' => 'Lagu berhasil dihapus']);
    }
}
