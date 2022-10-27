// Required tags/elements in song details section
const songDetail = document.querySelector(".song-detail"),
    descDuration = songDetail.querySelector("#desc-dur");

// Required tags/elements in music player section
const musicPlayer = document.querySelector(".music-player"),
    playPauseBtn = musicPlayer.querySelector("#play-pause"),
    // nextBtn = musicPlayer.querySelector("#next"),
    // prevBtn = musicPlayer.querySelector("#prev"),
    mainAudio = musicPlayer.querySelector("#main-audio"),
    progressBar = musicPlayer.querySelector("#progress-bar");

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
