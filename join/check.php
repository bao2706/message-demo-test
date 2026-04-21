
<?php
session_start();
require("./dbconnect.php");
if (!isset($_SESSION['join'])) {
    header('Location: index.php'); // Chuyển hướng về trang nhập liệu nếu không có dữ liệu
    exit();
}
if(!empty($_POST)) {
// 登録処理をする
$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?,
picture=?, created=NOW()');
echo $ret = $statement->execute(array(
$_SESSION['join']['name'],
$_SESSION['join']['email'],
sha1($_SESSION['join']['password']),
$_SESSION['join']['image']
));
unset($_SESSION['join']);
header('Location: thanks.php');
 exit();}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div id="content">
        <p>記入した内容を確認して、「登録する」ボタンを押してください    </p> 
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="submit">
        <dl>
        <dt>ニックネーム</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
        </dd>
        <dt>メールアドレス</dt>
        <dd>
             <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>

        </dd>
        <dt>パスワード</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']['password'], ENT_QUOTES); ?>

        </dd>
        <dt>写真など</dt>
        <dd>
            <img src="../member_picture/<?php echo htmlspecialchars($_SESSION ['join']['image'], ENT_QUOTES); ?>" width="100" height="100" alt="">
        </dd>
        </dl>
        <div>
        <a href="index.php?action=rewrite">&laquo; &nbsp; 書き直す</a>
        | <input type="submit" value="登録する">
        </div>
        </form>
    </div>
</body>
</html>