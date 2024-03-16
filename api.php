<?php

$db = new PDO("mysql:host=localhost;dbname=php_htmx", "root", "");

function render_post($id, $title, $content, $image)
{
?>
  <div class="col" id="post-<?= $id ?>">
    <div class="card mt-5" style="width: 18rem">
      <div class="card-body">
        <h5 class="card-title"><?= $title ?></h5>
        <h6 class="card-subtitle mb-2 text-body-secondary"><?= $content ?></h6>
        <a href="#" class="btn btn-danger">Delete Post</a>
        <a href="#" class="btn btn-success" hx-get='./edit.php?id=<?= $id ?>&title=<?= $title ?>&content=<?= $content ?>&image=<?= $image ?>' hx-target='#post-<?= $id ?>'>Edit Post</a>
      </div>
    </div>
  </div>
<?php
}

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
    ]);

    render_post($db->lastInsertId(), $_POST['title'], $_POST['content'], "xxx");
    break;

  case 'get_posts':
    $sql = $db->query('select * from posts');
    $posts = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $post) {
      render_post($post['id'], $post['title'], $post['content'], $post['image']);
    }
    break;

  case 'update_post':
    header('HX-Trigger: post_update');
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = $db->prepare('update posts set title = :title, content = :content where id = :id limit 1');
    $sql->execute([
      ":id" => $id,
      ":title" => $title,
      ":content" => $content,
    ]);

    break;

  default:
    echo 'Invalid action';
}
