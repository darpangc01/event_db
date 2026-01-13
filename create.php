<?php
include "db.php";
include "include/header.php";

if (isset($_POST['submit'])) {

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $event_name = $_POST['event_name'];
    $registration_fee = $_POST['registration_fee'];

    // PASSWORD HASHING
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Reject invalid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red; text-align:center;'>Invalid email address</p>";
        exit;
    }

    // Reject non-numeric or negative registration fee
    if (!is_numeric($registration_fee) || $registration_fee < 0) {
        echo "<p style='color:red; text-align:center;'>Registration fee must be a positive number</p>";
        exit;
    }

    // Check for duplicate email
    $check = $conn->prepare("SELECT 1 FROM registrations WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo "<p style='color:red; text-align:center;'>Email already exists</p>";
        $check->close();
        exit;
    }
    $check->close();

    $stmt = $conn->prepare(
        "INSERT INTO registrations 
        (full_name, email, phone, event_name, registration_fee, password)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssds",
        $full_name,
        $email,
        $phone,
        $event_name,
        $registration_fee,
        $password
    );

    if ($stmt->execute()) {
        echo "<p style='color:green; text-align:center;'>Data added successfully!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<div style="text-align: center;"><a href="read.php">view List</a><br><br></div>

<body style="background: cadetblue;">
<div style="border:1px solid black; background:slategrey; width:350px; margin:40px auto; padding:20px;">
<form method="POST"> 
    <label>Name:</label>
    <input type="text" name="full_name" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <label>Phone:</label>
    <input type="tel" name="phone" required><br><br>

    <label>Event Name:</label>
    <input type="text" name="event_name" required><br><br>

    <label>Registration Fee:</label>
    <input type="number" name="registration_fee" step="0.01" min="0" required><br><br>

    <button type="submit" name="submit">Submit</button>
</form>
</div>
<?php require "include/footer.php"; ?>
</body>
</html>
