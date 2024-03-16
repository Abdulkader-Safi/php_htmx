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
        <img src="<?= $image ?>" alt="image" class="rounded w-100">
        <br />
        <a class="btn btn-danger" hx-delete='./api.php?action=delete_post&id=<?= $id ?>'>Delete Post</a>
        <a class="btn btn-success" hx-get='./edit.php?id=<?= $id ?>&title=<?= $title ?>&content=<?= $content ?>&image=<?= $image ?>' hx-target='#post-<?= $id ?>'>Edit Post</a>
      </div>
    </div>
  </div>
<?php
}

switch ($_GET['action']) {
  case 'add_post':

    $uploadDir = 'uploads/';
    $imageSrc = $uploadDir . basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $imageSrc);

    if ($_POST['title'] == "" || $_POST['content'] == "") {
      break;
    }

    $sql = $db->prepare('insert into posts (title, content, image) values (:title, :content, :image)');
    $sql->execute([
      ":title" => $_POST['title'],
      ":content" => $_POST['content'],
      ":image" => $imageSrc,
    ]);

    render_post($db->lastInsertId(), $_POST['title'], $_POST['content'], $imageSrc);
    break;

  case 'get_posts':
    $sql = $db->query('select * from posts');
    $posts = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $post) {
      render_post($post['id'], $post['title'], $post['content'], $post['image']);
    }
    break;

  case 'update_post':
    header('HX-Trigger: update_post');
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

  case 'delete_post':
    header('HX-Trigger: delete_post');
    $id = $_GET['id'];
    $sql = $db->prepare('delete from posts where id = :id limit 1');
    $sql->execute([
      ":id" => $id,
    ]);
    break;


  default:
    echo 'Invalid action';
}
