<?php

$db = new PDO("mysql:host=localhost;dbname=php_htmx", "root", "");

switch ($_GET['action']) {
  case 'add_post':

    if ($_POST['title'] == "" || $_POST['content'] == "") {
      break;
    }

    $sql = $db->prepare('insert into posts (title, content, image) values (:title, :content, :image)');
    $sql->execute([
      ":title" => $_POST['title'],
      ":content" => $_POST['content'],
      ":image" => "xxx",
    ])
?>
    <div class="col">
      <div class="card mt-5" style="width: 18rem">
        <div class="card-body">
          <h5 class="card-title"><?= $_POST['title'] ?></h5>
          <h6 class="card-subtitle mb-2 text-body-secondary"><?= $_POST['content'] ?></h6>
          <a href="#" class="btn btn-danger">Delete Post</a>
          <a href="#" class="btn btn-success">Edit Post</a>
        </div>
      </div>
    </div>
    <?php
    break;

  case 'get_posts':
    $sql = $db->query('select * from posts');
    $posts = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $post) {
    ?>
      <div class="col">
        <div class="card mt-5" style="width: 18rem">
          <div class="card-body">
            <h5 class="card-title"><?= $post['title'] ?></h5>
            <h6 class="card-subtitle mb-2 text-body-secondary"><?= $post['content'] ?></h6>
            <a href="#" class="btn btn-danger">Delete Post</a>
            <a href="#" class="btn btn-success">Edit Post</a>
          </div>
        </div>
      </div>
<?php
    }


    break;

  default:
    echo 'Invalid action';
}
