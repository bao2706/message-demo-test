<?php
session_start();
$error = [];
if(!empty($_POST)) {
if($_POST['name'] == '') {
$error['name'] = 'blank';
}
if($_POST['email'] == ''){
$error['email'] = 'blank';
}
if(strlen($_POST['password']) < 4){
$error['password'] = 'length';
}
if($_POST['password'] == '') {
$error['password'] = 'blank';
};
$fileName = $_FILES['image']['name'];
if(!empty($fileName)) {
$ext = pathinfo($fileName, PATHINFO_EXTENSION);
$ext = strtolower($ext);
if (!in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
    $error['image'] = 'type';
}
}
if(empty($error)) {
$image = date('YmdHis'). $_FILES['image']['name'];
move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/'.$image);
$_SESSION['join'] = $_POST;
$_SESSION['join']["image"] = $image;
header('Location: check.php');
exit();
}
}
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}
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

<form action="" method="post" enctype="multipart/form-data">
        <p>次のフォームに必要事項をご記入ください。</p>
    <div id="content">
            <dl>
            <dt>ニックネーム<span class="required">必須</span></dt>
            <dd><input type="text" name="name" size="35" maxlength="255" autocomplete="off" value="<?php echo htmlspecialchars($_POST["name"]?? '',ENT_QUOTES);?>"></dd>
                <?php if(isset($error['name']) == 'blank'): ?>
                    <p class="error">* ニックネームを入力してください</p>
                <?php endif; ?>
            <dt>メールアドレス<span class="required">必須</span></dt>
            <dd><input type="text" name="email" size="35" maxlength="255" autocomplete="off" value="<?php echo htmlspecialchars($_POST["email"]?? '',ENT_QUOTES);?>"></dd>
            <?php if(isset($error["email"]) == 'blank'): ?>
            <p class="error">* メールアドレスを入力してください</p>
            <?php endif ; ?>
            <dt>パスワード<span class="required">必須</span></dt>
            <dd><input type="password" name="password" size="10" autocomplete="off" maxlength="20" value="<?php echo htmlspecialchars($_POST["password"]?? '',ENT_QUOTES);?>"></dd>
            <?php if(isset($error['password']) == 'blank'): ?>
            <p class="error">* パスワードを入力してください</p>
            <?php endif; ?>
            <?php if(isset($error['password']) == 'length'): ?>
            <p class="error">* パスワードは4文字以上で入力してください</p>
            <?php endif; ?>
            <dt>写真など</dt>
            <dd><input type="file" name="image" size="35" autocomplete="off"></dd>
            <?php if(isset($error['image']) == 'type'): ?>
            <p class="error">* 写真などは「.gif」 または 「.jpg」または「.png」の画像を指定してください</p>
            <?php endif; ?>
            <?php if(!empty($error)): ?>
            <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
            <?php endif; ?>
            </dl>
            <div><input type="submit" value="入力内容を確認する"></div>
    </div>
</form>
</body>
</html>