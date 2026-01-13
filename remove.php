<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM registrations WHERE event_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: read.php");
    }else {
        header("Location: read.php?msg=error");
    }
    mysqli_stmt_close($stmt);
}
?>