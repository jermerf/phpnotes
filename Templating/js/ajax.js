
function sillyAjax() {
  var xhr = new XMLHttpRequest();

  xhr.addEventListener("load", (ev) => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert(xhr.responseText)
    }
  })

  xhr.open("post", "server.php");
  xhr.send();
}
