document.querySelector('#currentUser').addEventListener('click', ev => {
  document.querySelector('#loginForm').classList.toggle('visible')
})

function register() {
  document.querySelector("input[name=action]").value = "register"
}

function login() {
  document.querySelector("input[name=action]").value = "login"
}

// Handled by php
function loginSuccess(user) {
  $('#currentUser').text(user.username)
  $('.notLoggedIn').hide()
  $('.loggedIn').show()
}


var cookies = {}

function getCookies() {
  var cookieParts = document.cookie.split(';')
  for (const p of cookieParts) {
    let subParts = p.trim().split('=')
    let k = subParts[0]
    let v = decodeURI(subParts[1])
    cookies[k] = v
  }
}
getCookies()

if (cookies.authError) {
  document.getElementById('loginStatus').innerText = cookies.authError
}

function togglePostForm(id, toggleContent = false) {
  var form = document.querySelector('#post' + id + " form.edit-hidden")
  var h3 = document.querySelector('#post' + id + " h3")

  if (form) {
    form.classList.remove('edit-hidden')
    form.classList.add('edit')
    if (toggleContent) h3.style.display = "none"
  } else {
    form = document.querySelector('#post' + id + " form.edit")
    form.classList.remove('edit')
    form.classList.add('edit-hidden')
    if (toggleContent) h3.style.display = "block"
  }
}

function getPostComments(id) {
  let spinner = $('#post' + id + " .fa-spinner")
  spinner.css({ display: "inline-block" })
  $.post("server.php", { action: "commentsForPost", postId: id }, res => {
    spinner.css({ display: "none" })
    if (res.success) {
      let comments = $('#post' + id + " ul.comments")
      comments.html("")
      for (const c of res.comments) {
        let li = $("<li>").text(c.content)
        let divFoot = $("<div>").text("from " + c.username)
        let spanDate = $("<span>").text(c.posted_on)
        divFoot.append(spanDate)
        li.append(divFoot)
        comments.append(li)
      }
    } else {
      console.log(res)
    }
  })
  try {
    togglePostForm(id)
  } catch (ex) { }
}