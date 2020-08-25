<ul class="card-list">
  <?php

  if (!$loggedIn) return;

  require 'db.php';

  $query = <<<JOINEDPOSTS
  SELECT 
    session_user.username,
    session_post.id,
    session_post.content,
    session_post.posted_on
  FROM session_post
  INNER JOIN session_user
    ON session_post.user_id=session_user.id
  WHERE session_post.user_id=:user_id
  ORDER BY session_post.posted_on DESC
  JOINEDPOSTS;

  $stm = $con->prepare($query);

  $stm->bindParam(':user_id', $_SESSION['userId']);

  $stm->execute();

  while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
    echo <<<EACHPOST
    <li>
      <form action="server.php" method="POST">
        <input name="action" type="hidden" value="deletePost" />
        <input name="postId" type="hidden" value="{$row['id']}" />
        <button class="delete">X</button>
      </form>
      <h3>{$row['content']}</h3>
      <div>
        {$row['username']}
        <span>{$row['posted_on']}</span>
      </div>
    </li>
    EACHPOST;
  }

  ?>
</ul>