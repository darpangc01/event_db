<?php
include 'db.php';
include "include/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $event_name = $_POST['event_name'];
    $registration_fee = $_POST['registration_fee'];
    $registered_at = $_POST['registered_at'];

    // PASSWORD HASHING
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Reject invalid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    // Reject non-numeric or negative registration fee
    if (!is_numeric($registration_fee) || $registration_fee < 0) {
        echo "Registration fee must be a positive number";
        exit;
    }

    $update_stmt = mysqli_prepare(
        $conn,
        "UPDATE registrations 
         SET full_name=?, email=?, phone=?, event_name=?, registration_fee=?, registered_at=?, password=?
         WHERE event_id=?"
    );

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssdssi",
        $full_name,
        $email,
        $phone,
        $event_name,
        $registration_fee,
        $registered_at,
        $password,
        $id
    );

    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: read.php");
        exit;
    } else {
        echo "Error updating record";
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM registrations WHERE event_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Student not found.");
}
?>
<!DOCTYPE html>
<html>
<body style="background-color: yellowgreen;">
<h2 style="text-align: center;">Update Event</h2>

<div style="border:1px solid black; background:white; width:350px; margin:40px auto; padding:20px;">
<form method="POST">
    <label>Name:</label>
    <input type="text" name="full_name" value="<?= htmlspecialchars($data['full_name']) ?>" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <label>Phone:</label>
    <input type="tel" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" required><br><br>

    <label>Event Name:</label>
    <input type="text" name="event_name" value="<?= htmlspecialchars($data['event_name']) ?>" required><br><br>

    <label>Registration Fee:</label>
    <input type="number" name="registration_fee" step="0.01" min="0" value="<?= htmlspecialchars($data['registration_fee']) ?>" required><br><br>

    <label>Registered At:</label>
    <input type="datetime-local" name="registered_at" value="<?= str_replace(' ', 'T', $data['registered_at']) ?>" required><br><br>

    <button type="submit">Update Event</button>
    <a href="read.php">Cancel</a>
</form>
</div>

<?php include "include/footer.php"; ?>
</body>
</html>
