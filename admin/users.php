<?php
require_once dirname(__DIR__) . '/config/database.php';
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$delete_id]);
    header('Location: users.php');
    exit;
}

if (isset($_POST['edit_user'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_name = trim($_POST['edit_name']);
    $edit_email = trim($_POST['edit_email']);
    $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
    $stmt->execute([$edit_name, $edit_email, $edit_id]);
    header('Location: users.php');
    exit;
}
require_once 'admin_header.php';

$stmt = $pdo->query('SELECT id, name, email FROM users');
$users = $stmt->fetchAll();
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Users</h1>
    <?php if (isset($_GET['edit_id'])):
        $edit_id = intval($_GET['edit_id']);
        $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE id = ?');
        $stmt->execute([$edit_id]);
        $edit_user = $stmt->fetch();
        if ($edit_user): ?>
        <div class="card mb-4">
            <div class="card-header">Edit User</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="edit_id" value="<?= $edit_user['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="edit_name" class="form-control" value="<?= htmlspecialchars($edit_user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="edit_email" class="form-control" value="<?= htmlspecialchars($edit_user['email']) ?>" required>
                    </div>
                    <button type="submit" name="edit_user" class="btn btn-primary">Update</button>
                    <a href="users.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <a href="users.php?edit_id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="users.php?delete_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once 'admin_footer.php'; ?>
