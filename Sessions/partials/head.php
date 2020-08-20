<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP Session Posts</title>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<style>
  body,
  html {
    background-color: #333;
    color: #fff;
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
  }

  nav {
    background-color: #444;
    padding: 7px 10px;
  }

  nav a {
    text-decoration: none;
    color: #ffc;
    padding: 8px 12px;
  }

  nav a:hover {
    background-color: #567;
  }

  h1 {
    text-align: center;
  }

  div#loginbox {
    position: absolute;
    top: 0;
    right: 0;
  }

  div#loginbox input {
    display: block;
  }

  div#currentUser {
    padding: 8px 12px;
    cursor: pointer;
    -moz-user-select: none;
    user-select: none;
    -webkit-user-select: none;
    text-transform: capitalize;
  }

  div#loginForm {
    display: none;
    background-color: #444;
    padding: 5px;
  }

  div#loginForm.visible {
    display: block;
  }

  input {
    border: 2px solid grey;
    border-radius: 3px;
    margin: 5px;
    padding: 4px 12px;
  }

  div#loginStatus {
    font-weight: bold;
    color: #e52;
  }

  .loggedIn {
    display: none;
  }
</style>
<script>
  const SERVER = "http://localhost/Sessions/server.php"
</script>