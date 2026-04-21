<?php
session_start();
require('join/dbconnect.php');
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
        $members = $db->prepare('SELECT *FROM members WHERE id=?');
        $members->execute(array($_SESSION['id']));
        $member = $members->fetch();
}else{
    header('Location: login.php'); exit();
}
if(!empty($_POST)) {
    if($_POST['message'] != ''){
        $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id =?,
        created=NOW()');
        $message->execute(array(
            $member['id'],
            $_POST['message'],
            $_POST["reply_post_id"]
            ));
    header('Location: index.php'); exit();
    }
}
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id
ORDER BY p.created DESC');
if(isset($_REQUEST['res'])){
$response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
$response->execute(array($_REQUEST['res']));
$table= $response->fetch();
$message = '@'. $table['name']." ".$table['message'];}
function h($value) {
return htmlspecialchars($value, ENT_QUOTES);
};
function makeLink ($value) {
return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",
' <a href="\1\2">\1\2</a>', $value);
}

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
        <div id="head">

        </div>
            <h1>ひとこと掲示板</h1>
            <div style="text-align:right;"><a href="logout.php">ログアウト</a></div>
            <form action="" method="post">
                <dl>
                    <dt><?php echo htmlspecialchars(h($member["name"]), ENT_QUOTES); ?>さん、メッセージをどうぞ</dt>
                    <dd>
                        <textarea name="message" cols="50" rows="5" placeholder="Viết gì đó..."><?php 
                            if (isset($message)) {
                                echo htmlspecialchars(makeLink(h($message)), ENT_QUOTES);    
                            }
                        ?></textarea>
                        <input type="hidden" name="reply_post_id" value="<?php 
                            if (isset($message)) {
                                echo htmlspecialchars(h($_REQUEST["res"]), ENT_QUOTES);    
                            }
                        ?>">
                    </dd>
                </dl>
                <div><input type="submit" value="投稿する"></div>
                    </form>

            <div class="message-container" style="height: 400px; overflow-y: scroll;">
                <?php
                    foreach($posts as $post):
                ?>
                <div class="msg" >
                    <img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>">
                    [<a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">Re</a>]
                    <p>

                            <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES); ?><span class="name"> (<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>)</span></p>
                            <p class="day"> <a href="view.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>"><?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></p></a>
                    </p>
                    <?php 
                    if ($post["reply_post_id"] > 0): 
                    ?>
                    <a href="view.php?id=<?php echo htmlspecialchars($post['reply_post_id'], ENT_QUOTES) ?>;">返信元のメッセージ</a>
                    <?php endif;
                    ?>
                    <?php
                    if($_SESSION['id'] == $post['member_id']):
                    ?>
                    [<a href="delete.php?id=<?php echo h($post['id']); ?>"
                    style="color: #f33;">削除</a>]
                    <?php
                    endif;
                    ?>
                </div>
                <?php endforeach;?>
            </div>
</body>
</html>