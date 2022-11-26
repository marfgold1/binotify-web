
//event
document.getElementById('file-upload').onchange=function(){
  var file = this.files[0].name;
  document.getElementById('cover').src="/public/image/" + file;
}
function deleteAlbum() {
  var form = document.querySelectorAll('form')[1];
  console.log(form);
  form.action += '/delete';
  form.submit();
}
