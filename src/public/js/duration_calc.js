const audioPreview = () => {
    var url;
    var file = document.querySelector("#audio_path").files[0];
    var reader = new FileReader();
    reader.onload = function (evt) {
        url = evt.target.result;
        var audio = new Audio(url);
        audio.oncanplay = () => {
            var duration = document.querySelector("#duration-helper");
            let audioDuration = audio.duration;
            duration.value = Math.round(audioDuration);
        };
    };
    reader.readAsDataURL(file);
};
