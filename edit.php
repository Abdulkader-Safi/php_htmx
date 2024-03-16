<?php

$id = $_GET['id'];
$title = $_GET['title'];
$content = $_GET['content'];
$image = $_GET['image'];

?>

<div class="card mt-5 p-2" style="width: 18rem">
  <form hx-post="./api.php?action=update_post" hx-vals=".form" hx-target="#post-<?= $id ?>">
    <div class="form">
      <input name="id" type="hidden" value="<?= $id ?>" />
      <input name="title" type="text" class="form-control mb-3" value="<?= $title ?>" />
      <textarea name="content" class="form-control mb-3" rows="3"><?= $content ?></textarea>
      <button type="submit" class="btn btn-success">Update Post</button>
    </div>
  </form>
</div>