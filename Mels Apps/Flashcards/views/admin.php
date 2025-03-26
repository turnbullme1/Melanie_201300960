<!-- views/admin.php -->
<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$courses = $courses ?? [];
$categories = $categories ?? [];
$course = $course ?? 'select-course';
$current_banner_message = $current_banner_message ?? '';
$pending_requests = $pending_requests ?? [];
$admins = $admins ?? [];
?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Admin Panel</h1>

    <!-- Success Messages (Hidden unless success is set) -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" id="success-message">
            <?php
            if ($_GET['success'] === 'featured_updated') echo "Featured flashcards updated successfully!";
            elseif ($_GET['success'] === 'banner_updated') echo "Banner message updated successfully!";
            elseif ($_GET['success'] === 'admin_added') echo "Admin user added successfully!";
            elseif ($_GET['success'] === 'bulk_added') echo "Bulk flashcards added successfully!" . (isset($_GET['count']) ? " ({$_GET['count']} flashcards)" : "");
            elseif ($_GET['success'] === 'course_added') echo "Course added successfully!";
            elseif ($_GET['success'] === 'category_added') echo "Category added successfully!";
            elseif ($_GET['success'] === 'admin_approved') echo "Admin request approved!";
            elseif ($_GET['success'] === 'admin_denied') echo "Admin request denied!";
            elseif ($_GET['success'] === 'admin_removed') echo "Admin privileges removed!";
            ?>
        </div>
    <?php endif; ?>

    <!-- Update Banner Message (Collapsible) -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;">Update Banner Message</h2>
        <div class="toggle-content" style="display: none;">
            <form method="post" action="index.php?action=update_banner" class="mb-3">
                <div class="mb-3">
                    <label for="banner_message" class="form-label">Banner Message:</label>
                    <textarea name="banner_message" id="banner_message" class="form-control" rows="3"><?= htmlspecialchars($current_banner_message) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Banner</button>
            </form>
        </div>
    </div>

    <!-- Manage Featured Flashcards (Collapsible) -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;">Manage Featured Flashcards</h2>
        <div class="toggle-content" style="display: none;">
            <form method="get" action="index.php" class="mb-3">
                <input type="hidden" name="action" value="update_featured">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="course" class="form-label">Course:</label>
                        <select name="course" id="course" class="form-select" onchange="this.form.submit()">
                            <option value="select-course" <?= $course === 'select-course' ? 'selected' : '' ?>>Select a Course</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= htmlspecialchars($c['course']) ?>" <?= $course === $c['course'] ? 'selected' : '' ?>><?= htmlspecialchars($c['course']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($course !== 'select-course'): ?>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Categories:</label>
                            <select name="category[]" id="category" class="form-select" multiple="multiple" size="5">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-2">Update Featured Flashcards</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Add Flashcards (Collapsible) -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;">Bulk Add Flashcards</h2>
        <div class="toggle-content" style="display: none;">
            <a href="index.php?action=bulk_add_flashcards" class="btn btn-primary">Go to Bulk Add Page</a>
        </div>
    </div>

    <!-- Current Admins (Collapsible with Remove Button) -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;">Current Admins</h2>
        <div class="toggle-content" style="display: none;">
            <?php if (empty($admins)): ?>
                <p>No admins found.</p>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['username']) ?></td>
                                <td>
                                    <?php if ($admin['username'] !== 'admin'): ?>
                                        <a href="index.php?action=remove_admin&user_id=<?= $admin['id'] ?>" class="btn btn-danger btn-sm">Remove</a>
                                    <?php else: ?>
                                        <span class="text-muted">Main Admin</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pending Admin Requests (Non-Collapsible) -->
    <div class="mb-4">
        <h2 class="h4">Pending Admin Requests</h2>
        <?php if (empty($pending_requests)): ?>
            <p>No pending requests.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['username']) ?></td>
                            <td><?= htmlspecialchars($request['request_date']) ?></td>
                            <td>
                                <a href="index.php?action=approve_admin&request_id=<?= $request['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="index.php?action=deny_admin&request_id=<?= $request['id'] ?>" class="btn btn-danger btn-sm">Deny</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Add Admin User (Collapsible) -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;">Add Admin User</h2>
        <div class="toggle-content" style="display: none;">
            <form method="post" action="index.php?action=add_admin" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="admin_username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                    </div>
                    <div class="col-md-4">
                        <label for="admin_password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add Admin</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle visibility of content when header is clicked
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>