<ul class="posts">
  <?php


  require 'db.php';

  $query = <<<JOINEDPOSTS
  SELECT 
    session_user.username,
    session_post.content,
    session_post.posted_on
  FROM session_post
  INNER JOIN session_user
    ON session_post.user_id=session_user.id
  ORDER BY session_post.posted_on DESC
  JOINEDPOSTS;

  $stm = $con->prepare($query);

  $stm->execute();

  while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
    echo <<<EACHPOST
    <li>
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