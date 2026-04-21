<?php
session_start();
require('join/dbconnect.php');

// 1. Kiểm tra xem đã đăng nhập chưa
if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];

    // 2. Lấy thông tin bài viết từ DB để kiểm tra "chủ sở hữu"
    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    // 3. 判定 (Hantei): Chỉ xóa nếu ID người dùng trong Session khớp với member_id của bài viết
    if ($message['member_id'] == $_SESSION['id']) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
}

// 4. Xóa xong (hoặc không có quyền xóa) thì đều quay về trang chủ
header('Location: index.php');
exit();
?>