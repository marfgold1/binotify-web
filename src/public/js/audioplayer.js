// Required tags/elements in song details section
const songDetail = document.querySelector(".song-detail"),
    //     bigCover = songDetail.querySelector("#big-cover"),
    //     descTitle = songDetail.querySelector("#desc-title"),
    //     descArtist = songDetail.querySelector("#desc-artist"),
    //     descGenre = songDetail.querySelector("#desc-genre"),
    //     descDate = songDetail.querySelector("#desc-date"),
    descDuration = songDetail.querySelector("#desc-dur");

// const editTitle = songDetail.querySelector("#edit-title"),
//     editArtist = songDetail.querySelector("#edit-artist"),
//     editGenre = songDetail.querySelector("#edit-genre"),
//     editDate = songDetail.querySelector("#edit-date"),
//     editSong = songDetail.querySelector("#edit-song"),
//     editCover = document.querySelector("#edit-cover"),
//     saveBtn = document.querySelector("#save-btn"),
//     closeBtn = document.querySelector("#show");

// Required tags/elements in music player section
const musicPlayer = document.querySelector(".music-player"),
    // miniCover = musicPlayer.querySelector("#mini-cover"),
    // miniTitle = musicPlayer.querySelector("#mini-title"),
    // miniArtist = musicPlayer.querySelector("#mini-artist"),
    playPauseBtn = musicPlayer.querySelector("#play-pause"),
    // nextBtn = musicPlayer.querySelector("#next"),
    // prevBtn = musicPlayer.querySelector("#prev"),
    mainAudio = musicPlayer.querySelector("#main-audio"),
    progressBar = musicPlayer.querySelector("#progress-bar");

// let musicIndex = 1;

// window.addEventListener("load", () => {
//     loadMusic(musicIndex); // Calling load music function
// });

// Load music function
// function loadMusic(indexNumb) {
//     // Song detail section
//     bigCover.src = `img/${allMusic[indexNumb - 1].img}`;
//     descTitle.innerText = allMusic[indexNumb - 1].name;
//     descArtist.innerText = allMusic[indexNumb - 1].artist;
//     descGenre.innerText = allMusic[indexNumb - 1].genre;
//     descDate.innerText = allMusic[indexNumb - 1].date;

//     // Edit detail section
//     editTitle.value = allMusic[indexNumb - 1].name;
//     editArtist.value = allMusic[indexNumb - 1].artist;
//     editGenre.value = allMusic[indexNumb - 1].genre;
//     editDate.value = allMusic[indexNumb - 1].date;

//     // Music player section
//     miniCover.src = `img/${allMusic[indexNumb - 1].img}`;
//     miniTitle.innerText = allMusic[indexNumb - 1].name;
//     miniArtist.innerText = allMusic[indexNumb - 1].artist;
//     mainAudio.src = `songs/${allMusic[indexNumb - 1].src}`;
// }

// Play music function
function playMusic() {
    musicPlayer.classList.add("paused");
    playPauseBtn.querySelector("i").className = "pause-button";
    mainAudio.play();
}

// Pause music function
function pauseMusic() {
    musicPlayer.classList.remove("paused");
    playPauseBtn.querySelector("i").className = "play-button";
    mainAudio.pause();
}

// // Next music function
// function nextMusic() {
//     musicIndex++;
//     musicIndex > allMusic.length ? (musicIndex = 1) : (musicIndex = musicIndex);
//     loadMusic(musicIndex);
//     playMusic();
// }

// // Previous music function
// function prevMusic() {
//     musicIndex--;
//     musicIndex < 1 ? (musicIndex = allMusic.length) : (musicIndex = musicIndex);
//     loadMusic(musicIndex);
//     playMusic();
// }

// Event listeners
playPauseBtn.addEventListener("click", () => {
    const isMusicPaused = musicPlayer.classList.contains("paused");
    isMusicPaused ? pauseMusic() : playMusic();
});

// nextBtn.addEventListener("click", () => {
//     nextMusic();
// });

// prevBtn.addEventListener("click", () => {
//     prevMusic();
// });

mainAudio.addEventListener("timeupdate", (e) => {
    const currentTime = e.target.currentTime;
    const duration = e.target.duration;
    let progressWidth = (currentTime / duration) * 100;
    progressBar.style.width = `${progressWidth}%`;
});

mainAudio.addEventListener("loadeddata", () => {
    let musicCurrentTime = musicPlayer.querySelector("#current-time"),
        musicDuration = musicPlayer.querySelector("#max-duration");
    // Song total duration
    let audioDuration = mainAudio.duration;
    let totalMin = Math.floor(audioDuration / 60);
    let totalSec = Math.floor(audioDuration % 60);
    if (totalSec < 10) {
        totalSec = `0${totalSec}`;
    }

    musicDuration.innerText = `${totalMin}:${totalSec}`;
    descDuration.innerText = `${totalMin}:${totalSec}`;

    // Playing song current time
    setInterval(() => {
        let audioCurrentTime = mainAudio.currentTime;
        let currentMin = Math.floor(audioCurrentTime / 60);
        let currentSec = Math.floor(audioCurrentTime % 60);
        if (currentSec < 10) {
            currentSec = `0${currentSec}`;
        }
        musicCurrentTime.innerText = `${currentMin}:${currentSec}`;
    });
});

// Progress bar width on click
progressBar.parentNode.addEventListener("click", (e) => {
    const width = progressBar.parentNode.clientWidth;
    const clickX = e.offsetX;
    const duration = mainAudio.duration;

    mainAudio.currentTime = (clickX / width) * duration;
    playMusic();
});

// // Update song detail on edit
// saveBtn.addEventListener("click", () => {
//     const musicObjIndex = allMusic.findIndex((obj) => obj.id == musicIndex);
//     let musicObj = allMusic[musicObjIndex];
//     let activeMusic = allMusic[musicIndex - 1];
//     if (editTitle.value != "") {
//         musicObj.name = editTitle.value;
//         activeMusic.name = editTitle.value;
//         descTitle.innerText = editTitle.value;
//         miniTitle.innerText = editTitle.value;
//     }
//     if (editArtist.value != "") {
//         musicObj.artist = editArtist.value;
//         activeMusic.artist = editArtist.value;
//         descArtist.innerText = editArtist.value;
//         miniArtist.innerText = editArtist.value;
//     }
//     if (editGenre.value != "") {
//         musicObj.genre = editGenre.value;
//         activeMusic.genre = editGenre.value;
//         descGenre.innerText = editGenre.value;
//     }
//     if (editDate.value != "") {
//         musicObj.date = editDate.value;
//         activeMusic.date = editDate.value;
//         descDate.innerText = editDate.value;
//     }
//     if (editSong.value != "") {
//         musicObj.src = editSong.value;
//         activeMusic.src = editSong.value;
//         mainAudio.src = `songs/${editSong.value}`;
//     }
//     if (editCover.files.length > 0) {
//         musicObj.img = editCover.files[0].name;
//         activeMusic.img = editCover.files[0].name;
//         bigCover.src = `img/${editCover.files[0].name}`;
//         miniCover.src = `img/${editCover.files[0].name}`;
//     }
//     closeBtn.click();
//     // update allMusic
// });
