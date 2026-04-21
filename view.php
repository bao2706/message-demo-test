<?php
session_start();
require('./join/dbconnect.php');
if(empty($_REQUEST['id'])){
header('Location: index.php'); exit();
}
// 投稿を取得する
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p
WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
$posts->execute(array($_REQUEST['id']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="wrap">
        <div id="content">

       <p>&laquo;<a href="index.php">一覧に戻る</a></p>
            <?php
            if($post = $posts->fetch()):
            ?>
                    <div class="msg">
                    <img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>">
                    [ <a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">Re</a>]
                    <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES); ?><span class="name"> (<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>)</span></p>
                    <p class="day"> <?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></p>
                </div>
<?php
else:
?>
<p>その投稿は削除されたか、URLが間違えています</p>
<?php
endif;
?>
</div>
</body>
</html>