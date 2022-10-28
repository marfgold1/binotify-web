
//event
document.getElementById('file-upload').onchange=function(){
  var file = this.files[0].name;
  document.getElementById('cover').src="/public/img/" + file;
}
function deleteAlbum() {
  var form = document.querySelector('form');
  form.action += '/delete';
  form.submit();
}
