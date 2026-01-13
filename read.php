<?php
include 'db.php';
include "include/header.php";

$result = mysqli_query($conn, "SELECT * FROM registrations");
?>
<!DOCTYPE html>
<html>
<body style="background-color: lightskyblue;">
<h2>Event List</h2>
<a href="create.php">Add Event List</a><br><br>

<table border="1" cellpadding="8" style="border-collapse: collapse;">
<tr>
    <th>Event ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Password</th>
    <th>Phone Number</th>
    <th>Event Name</th>
    <th>Registration Fee</th>
    <th>Registered At</th>
    <th>Actions</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['event_id']; ?></td>
    <td><?= $row['full_name']; ?></td>
    <td><?= $row['email']; ?></td>
    <td>********</td>
    <td><?= $row['phone']; ?></td>
    <td><?= $row['event_name']; ?></td>
    <td>$<?= number_format($row['registration_fee'], 2); ?></td>
    <td><?= $row['registered_at']; ?></td>
    <td>
        <a href="change.php?id=<?= $row['event_id'] ?>">Edit</a> | 
        <a href="remove.php?id=<?= $row['event_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php } ?>
</table>

<footer style="margin-top: 35%; padding: 10px; background-color: slategrey; color: black; text-align: center;">
<p>&copy; 2024 Crud Management System. All rights reserved.</p>
</footer>
</body>
</html>
