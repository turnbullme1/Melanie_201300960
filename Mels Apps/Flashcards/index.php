<?php
session_start();
require_once 'model/db_connect.php';
require_once 'model/functions.php';

$db = $db ?? null;
$banner_message = $db ? getSetting('banner_message', $db) : '';

ob_start();

$action = isset($_GET['action']) ? strtolower($_GET['action']) : 'home';

function isAdmin() {
    return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

switch ($action) {
    case 'my_account':
        define('IN_CONTROLLER', true);
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        // Fetch flashcards with id
        $stmt = $db->prepare("SELECT id, course, category, question, answer, explanation FROM questions WHERE added_by = ? ORDER BY id DESC");
        $stmt->execute([$_SESSION['username']]);
        $my_flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT q.course, q.category, q.question, q.answer, q.explanation, q.id 
            FROM user_flashcard_interactions ufi 
            JOIN questions q ON ufi.question_id = q.id 
            WHERE ufi.user_id = ? AND ufi.interaction_type = 'missed' 
            ORDER BY ufi.interaction_date DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $missed_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT q.course, q.category, q.question, q.answer, q.explanation, q.id 
            FROM user_flashcard_interactions ufi 
            JOIN questions q ON ufi.question_id = q.id 
            WHERE ufi.user_id = ? AND ufi.interaction_type = 'favorite' 
            ORDER BY ufi.interaction_date DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $favorite_flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/header.php';
        include 'views/my_account.php';
        include 'views/footer.php';
        break;

    case 'delete_flashcard':
    define('IN_CONTROLLER', true);
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
    $question_id = $_GET['question_id'] ?? 0;
    $flashcard_index = max(0, (int)($_GET['flashcard_index'] ?? 0));
    if ($question_id) {
        $stmt = $db->prepare("DELETE FROM questions WHERE id = ? AND added_by = ?");
        $stmt->execute([$question_id, $_SESSION['username']]);
        $stmt = $db->prepare("DELETE FROM user_flashcard_interactions WHERE question_id = ?");
        $stmt->execute([$question_id]);
    }
    // Adjust index if it exceeds the new count after deletion
    $stmt = $db->prepare("SELECT COUNT(*) FROM questions WHERE added_by = ?");
    $stmt->execute([$_SESSION['username']]);
    $new_count = $stmt->fetchColumn();
    $new_index = min($flashcard_index, max(0, $new_count - 1));
    header("Location: index.php?action=my_account&open=flashcards&flashcard_index=$new_index");
    exit;
    break;
	
    case 'remove_missed':
        define('IN_CONTROLLER', true);
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $question_id = $_GET['question_id'] ?? 0;
        if ($question_id) {
            $stmt = $db->prepare("DELETE FROM user_flashcard_interactions WHERE user_id = ? AND question_id = ? AND interaction_type = 'missed'");
            $stmt->execute([$_SESSION['user_id'], $question_id]);
        }
        $study_index = $_GET['study_index'] ?? 0;
        header("Location: index.php?action=my_account&study_index=$study_index&open=study");
        exit;
        break;

    case 'remove_favorite':
        define('IN_CONTROLLER', true);
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $question_id = $_GET['question_id'] ?? 0;
        if ($question_id) {
            $stmt = $db->prepare("DELETE FROM user_flashcard_interactions WHERE user_id = ? AND question_id = ? AND interaction_type = 'favorite'");
            $stmt->execute([$_SESSION['user_id'], $question_id]);
        }
        $favorite_index = $_GET['favorite_index'] ?? 0;
        header("Location: index.php?action=my_account&favorite_index=$favorite_index&open=favorites");
        exit;
        break;

    case 'update_account':
        define('IN_CONTROLLER', true);
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_username = trim($_POST['new_username'] ?? '');
            $new_password = trim($_POST['new_password'] ?? '');
            $current_password = trim($_POST['current_password'] ?? '');

            $stmt = $db->prepare("SELECT password FROM users WHERE id = ? AND username = ?");
            $stmt->execute([$_SESSION['user_id'], $_SESSION['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $message = 'Account not found.';
            } elseif (!password_verify($current_password, $user['password'])) {
                $message = 'Current password is incorrect.';
            } elseif (empty($new_username)) {
                $message = 'Username cannot be empty.';
            } elseif (strlen($new_username) < 3) {
                $message = 'Username must be at least 3 characters long.';
            } elseif (strlen($new_username) > 50) {
                $message = 'Username cannot exceed 50 characters.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
                $message = 'Username can only contain letters, numbers, and underscores.';
            } elseif ($new_username !== $_SESSION['username'] && $db->query("SELECT COUNT(*) FROM users WHERE username = " . $db->quote($new_username))->fetchColumn() > 0) {
                $message = 'Username is already taken.';
            } elseif ($new_password && strlen($new_password) < 6) {
                $message = 'New password must be at least 6 characters long.';
            } else {
                $original_username = $_SESSION['username'];
                $update_sql = "UPDATE users SET username = ?";
                $params = [$new_username];
                if ($new_password) {
                    $update_sql .= ", password = ?";
                    $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                }
                $update_sql .= " WHERE id = ? AND username = ?";
                $params[] = $_SESSION['user_id'];
                $params[] = $original_username;

                $stmt = $db->prepare($update_sql);
                if ($stmt->execute($params)) {
                    $_SESSION['username'] = $new_username;
                    $message = 'Account updated successfully!';
                } else {
                    $message = 'Failed to update account. Please try again.';
                }
            }
        }
        $stmt = $db->prepare("SELECT id, course, category, question, answer, explanation FROM questions WHERE added_by = ? ORDER BY id DESC");
        $stmt->execute([$_SESSION['username']]);
        $my_flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/header.php';
        include 'views/my_account.php';
        include 'views/footer.php';
        break;

    case 'login':
        define('IN_CONTROLLER', true);
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $message = 'Username and password are required.';
            } elseif (strlen($username) < 3) {
                $message = 'Username must be at least 3 characters long.';
            } else {
                $stmt = $db->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    $_SESSION['username'] = $username;
                    if (isAdmin()) {
                        header('Location: index.php?action=admin');
                    } else {
                        header('Location: index.php?action=home');
                    }
                    exit;
                } else {
                    $message = 'Invalid username or password.';
                }
            }
        }
        include 'views/header.php';
        include 'views/login.php';
        include 'views/footer.php';
        break;

    case 'signup':
        define('IN_CONTROLLER', true);
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $request_admin = isset($_POST['request_admin']) ? 1 : 0;

            if (empty($username) || empty($password)) {
                $message = 'Username and password are required.';
            } elseif (strlen($username) < 3) {
                $message = 'Username must be at least 3 characters long.';
            } elseif (strlen($username) > 50) {
                $message = 'Username cannot exceed 50 characters.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $message = 'Username can only contain letters, numbers, and underscores.';
            } elseif (strlen($password) < 6) {
                $message = 'Password must be at least 6 characters long.';
            } else {
                $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $message = 'Username is already taken. Please choose another.';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
                    if ($stmt->execute([$username, $hashed_password])) {
                        $user_id = $db->lastInsertId();
                        if ($request_admin) {
                            $stmt = $db->prepare("INSERT INTO pending_admin_requests (user_id, username) VALUES (?, ?)");
                            $stmt->execute([$user_id, $username]);
                        }
                        header('Location: index.php?action=login&signup=success');
                        exit;
                    } else {
                        $message = 'Signup failed due to a server error. Please try again.';
                    }
                }
            }
        }
        include 'views/header.php';
        include 'views/login.php';
        include 'views/footer.php';
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?action=home');
        exit;
        break;

    case 'admin':
        define('IN_CONTROLLER', true);
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        $courses = getCourses($db);
        $course = $_GET['course'] ?? 'select-course';
        $course_categories_map = [];
        foreach ($courses as $c) {
            $course_categories_map[$c['course']] = getCategories($c['course'], $db);
        }
        $categories = ($course !== 'select-course') ? getCategories($course, $db) : [];
        $current_banner_message = $banner_message;
        $stmt = $db->prepare("SELECT id, user_id, username, request_date FROM pending_admin_requests ORDER BY request_date ASC");
        $stmt->execute();
        $pending_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT id, username FROM users WHERE is_admin = 1 ORDER BY username ASC");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/header.php';
        include 'views/admin.php';
        include 'views/footer.php';
        break;

    case 'remove_admin':
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        $user_id = $_GET['user_id'] ?? 0;
        if ($user_id) {
            $stmt = $db->prepare("SELECT username FROM users WHERE id = ? AND is_admin = 1");
            $stmt->execute([$user_id]);
            $username = $stmt->fetchColumn();
            if ($username && $username !== 'admin') {
                $stmt = $db->prepare("UPDATE users SET is_admin = 0 WHERE id = ?");
                $stmt->execute([$user_id]);
            }
        }
        header('Location: index.php?action=admin&success=admin_removed');
        exit;
        break;

    case 'approve_admin':
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        $request_id = $_GET['request_id'] ?? 0;
        if ($request_id) {
            $stmt = $db->prepare("SELECT user_id FROM pending_admin_requests WHERE id = ?");
            $stmt->execute([$request_id]);
            $user_id = $stmt->fetchColumn();
            if ($user_id) {
                $stmt = $db->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");
                $stmt->execute([$user_id]);
                $stmt = $db->prepare("DELETE FROM pending_admin_requests WHERE id = ?");
                $stmt->execute([$request_id]);
            }
        }
        header('Location: index.php?action=admin&success=admin_approved');
        exit;
        break;

    case 'deny_admin':
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        $request_id = $_GET['request_id'] ?? 0;
        if ($request_id) {
            $stmt = $db->prepare("DELETE FROM pending_admin_requests WHERE id = ?");
            $stmt->execute([$request_id]);
        }
        header('Location: index.php?action=admin&success=admin_denied');
        exit;
        break;

    case 'update_featured':
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        $course = $_GET['course'] ?? 'select-course';
        $categories = $_GET['category'] ?? [];
        updateFeaturedFlashcards($course, $categories, $db);
        header('Location: index.php?action=admin&success=featured_updated&course=' . urlencode($course));
        exit;
        break;

    case 'update_banner':
        define('IN_CONTROLLER', true);
        if (!isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $banner_message_update = $_POST['banner_message'] ?? '';
            $stmt = $db->prepare("REPLACE INTO settings (setting_key, setting_value) VALUES ('banner_message', ?)");
            $stmt->execute([$banner_message_update]);
            $banner_message = $banner_message_update;
            header('Location: index.php?action=admin&success=banner_updated');
            exit;
        }
        $courses = getCourses($db);
        $course = $_GET['course'] ?? 'select-course';
        $categories = ($course !== 'select-course') ? getCategories($course, $db) : [];
        $current_banner_message = $banner_message;
        include 'views/header.php';
        include 'views/admin.php';
        include 'views/footer.php';
        break;



    case 'add flashcard':
        define('IN_CONTROLLER', true);
        $courses = getCourses($db);
        $course_categories_map = [];
        foreach ($courses as $c) {
            $course_categories_map[$c['course']] = getCategories($c['course'], $db);
        }
        $username = isset($_SESSION['user_id']) ? $_SESSION['username'] : null;
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course = $_POST['course'] ?? '';
            $category = $_POST['category'] ?? '';
            $question = $_POST['question'] ?? '';
            $answer = $_POST['answer'] ?? '';
            $explanation = $_POST['explanation'] ?? '';
            $question_type = $_POST['question_type'] ?? 'Text';
            $options = !empty($_POST['options']) ? json_encode(array_filter($_POST['options'])) : null;
            $added_by = $username;

            if ($course && $category && $question && $answer) {
                $stmt = $db->prepare("INSERT INTO questions (course, category, question, answer, explanation, question_type, options, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$course, $category, $question, $answer, $explanation, $question_type, $options, $added_by])) {
                    $message = '<div class="success-message">Flashcard added successfully!</div>';
                } else {
                    $message = '<div class="error-message">Failed to add flashcard. Please try again.</div>';
                }
            } else {
                $message = '<div class="error-message">All required fields must be filled.</div>';
            }
        }
        include 'views/header.php';
        include 'views/add_flashcard.php';
        include 'views/footer.php';
        break;

 case 'bulk_add_flashcards':
    define('IN_CONTROLLER', true);
    if (!isset($_SESSION['user_id'])) { // Changed from !isAdmin() to check for login only
        header('Location: index.php?action=login');
        exit;
    }
    $message = '';
    $selected_course = $_POST['course'] ?? $_GET['course'] ?? '';
    $selected_category = $_POST['category'] ?? $_GET['category'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$selected_course || !$selected_category) {
            $message = 'Error: Please select both a course and a category.';
        } else {
            $flashcards = $_POST['flashcards'] ?? [];
            $success_count = 0;
            $added_by = isset($_SESSION['user_id']) ? $_SESSION['username'] : null;

            if (empty($flashcards)) {
                $message = 'Error: No flashcards provided to add.';
            } else {
                foreach ($flashcards as $f) {
                    $question = trim($f['question'] ?? '');
                    $answer = trim($f['answer'] ?? '');
                    $explanation = trim($f['explanation'] ?? '');
                    $question_type = $f['question_type'] ?? 'Text';
                    $options = isset($f['options']) ? trim($f['options']) : '';

                    if (empty($question) || empty($answer)) {
                        continue; // Skip incomplete rows
                    }

                    try {
                        // Process options based on question type
                        $options_json = null;
                        if ($question_type === 'Multiple Choice' && $options) {
                            $options_json = json_encode(array_filter(explode("\n", $options)));
                        } elseif ($question_type === 'Match' && $options) {
                            $options_json = json_encode(array_filter(explode("\n", $options)));
                        }

                        $stmt = $db->prepare("
                            INSERT INTO questions (course, category, question, answer, explanation, question_type, options, added_by) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$selected_course, $selected_category, $question, $answer, $explanation, $question_type, $options_json, $added_by]);
                        $success_count++;
                    } catch (PDOException $e) {
                        error_log("Bulk add error: " . $e->getMessage());
                        $message = 'Error: An error occurred while adding flashcards. Please try again.';
                        break;
                    }
                }
                if ($success_count > 0 && !$message) {
                    header("Location: index.php?action=bulk_add_flashcards&success=bulk_added&count=$success_count");
                    exit;
                } elseif (!$message) {
                    $message = 'Error: No valid flashcards were added.';
                }
            }
        }
    }

    $courses = getCourses($db);
    $course_categories_map = [];
    foreach ($courses as $c) {
        $course_categories_map[$c['course']] = getCategories($c['course'], $db);
    }
    include 'views/header.php';
    include 'views/bulk_add_flashcards.php';
    include 'views/footer.php';
    break;

case 'quizzes':
    define('IN_CONTROLLER', true);


    // Fetch courses (only those with questions that have options)
    $stmt = $db->query("SELECT DISTINCT course FROM questions WHERE options IS NOT NULL AND options != ''");
    $courses = $stmt->fetchAll();

    $course = $_GET['course'] ?? 'select-course';
    $category = $_GET['category'] ?? 'select-category';
    $categories = [];
    $questions = [];
    $submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
    $score = 0;
    $user_answers = [];

    if ($course !== 'select-course') {
        // Fetch categories for the selected course (only those with questions that have options)
        $stmt = $db->prepare("SELECT DISTINCT category FROM questions WHERE course = ? AND options IS NOT NULL AND options != ''");
        $stmt->execute([$course]);
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($category !== 'select-category') {
            // Fetch quiz questions (only those with options)
            $stmt = $db->prepare("
                SELECT id, question, answer, options
                FROM questions
                WHERE course = ? AND category = ? AND options IS NOT NULL AND options != ''
                ORDER BY RAND()
                LIMIT 5
            ");
            $stmt->execute([$course, $category]);
            $questions = $stmt->fetchAll();

            // Shuffle options for each question
            foreach ($questions as &$q) {
                $q['options'] = json_decode($q['options'], true);
                if (empty($q['options'])) {
                    // Skip this question if options are empty after decoding (extra safety)
                    continue;
                }
                shuffle($q['options']); // Randomize option order
            }
            unset($q);

            // Remove any skipped questions
            $questions = array_filter($questions, fn($q) => !empty($q['options']));

            if ($submitted) {
                $user_answers = $_POST;
                foreach ($questions as $q) {
                    if (isset($user_answers[$q['id']]) && $user_answers[$q['id']] === $q['answer']) {
                        $score++;
                    }
                }
            }
        }
    }

    $pageTitle = "Quizzes";
    renderView('quiz', compact('courses', 'course', 'category', 'categories', 'questions', 'submitted', 'score', 'user_answers'));
    break;

case 'flashcards':
    define('IN_CONTROLLER', true);
    $courses = getCourses($db);
    $course = $_GET['course'] ?? 'select-course';
    $category = $_GET['category'] ?? [];
    if (!is_array($category)) {
        $category = $category ? [$category] : [];
    }

    if ($course !== 'select-course') {
        $categories = getCategories($course, $db);
    }

    $flashcards = [];
    if ($course !== 'select-course' && !empty($category)) {
        $placeholders = implode(',', array_fill(0, count($category), '?'));
        $query = "
            SELECT q.id, q.question, q.answer, q.explanation, q.question_type, q.options, q.image,
                   IF(ufi.user_id IS NOT NULL, 1, 0) AS is_favorite
            FROM questions q
            LEFT JOIN user_flashcard_interactions ufi 
                ON ufi.question_id = q.id 
                AND ufi.user_id = ? 
                AND ufi.interaction_type = 'favorite'
            WHERE q.course = ? 
            AND (q.category IN ($placeholders) OR ? IN ('all'))
        ";
        $params = [$_SESSION['user_id'] ?? 0, $course];
        $params = array_merge($params, $category);
        $params[] = implode(',', $category);
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Randomize flashcards
        shuffle($flashcards);

        foreach ($flashcards as &$f) {
            if ($f['options']) {
                $f['options'] = json_decode($f['options'], true);
            }
            if ($f['question_type'] === 'Match' && $f['answer']) {
                $f['answer'] = json_decode($f['answer'], true) ?? $f['answer'];
            }
        }
        unset($f);
    }

    include 'views/header.php';
    include 'views/flashcards.php';
    include 'views/footer.php';
    break;

case 'home':
    define('IN_CONTROLLER', true);
    require_once 'model/db_connect.php';


    $stmt = $db->prepare("
        SELECT q.id, q.course, q.category, q.question, q.answer, q.explanation, q.question_type, q.options, q.image
        FROM questions q
        WHERE q.featured = 1
        ORDER BY RAND()
        LIMIT 5
    ");
    $stmt->execute();
    $featured_flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($featured_flashcards as &$f) {
        if ($f['options']) $f['options'] = json_decode($f['options'], true);
        if ($f['question_type'] === 'Match' && $f['answer']) $f['answer'] = json_decode($f['answer'], true) ?? $f['answer'];
    }
    unset($f);

    $user_favorites = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("SELECT question_id FROM user_flashcard_interactions WHERE user_id = ? AND interaction_type = 'favorite'");
        $stmt->execute([$_SESSION['user_id']]);
        $user_favorites = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'question_id');
    }

    $pageTitle = "Home";
    include 'views/header.php';
    include 'views/home.php';
    include 'views/footer.php';
    break;


case 'subscribe':
    define('IN_CONTROLLER', true);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $db->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
            $stmt->execute([$email]);
            $message = "Thanks for subscribing!";
        } else {
            $message = "Invalid email address.";
        }
    }
    header("Location: index.php?action=home&message=" . urlencode($message));
    exit;
    break;



case 'search':
    define('IN_CONTROLLER', true);
    $query = $_GET['query'] ?? '';
    $stmt = $db->prepare("
        SELECT q.id, q.course, q.category, q.question, q.answer, q.explanation, q.question_type, q.options, q.image
        FROM questions q
        WHERE q.question LIKE ? OR q.answer LIKE ? OR q.category LIKE ?
    ");
    $stmt->execute(["%$query%", "%$query%", "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as &$r) {
        if ($r['options']) $r['options'] = json_decode($r['options'], true);
        if ($r['question_type'] === 'Match' && $r['answer']) $r['answer'] = json_decode($r['answer'], true) ?? $r['answer'];
    }
    unset($r);

    $user_favorites = [];
    if (isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("SELECT question_id FROM user_flashcard_interactions WHERE user_id = ? AND interaction_type = 'favorite'");
        $stmt->execute([$_SESSION['user_id']]);
        $user_favorites = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'question_id');
    }

    $pageTitle = "Search Results";
    include 'views/header.php';
    include 'views/search_results.php';
    include 'views/footer.php';
    break;

// Update toggle_favorite to handle search redirect
case 'toggle_favorite':
    define('IN_CONTROLLER', true);
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
    $question_id = $_GET['question_id'] ?? 0;
    $from = $_GET['from'] ?? 'home';
    $query = $_GET['query'] ?? '';

    if ($question_id) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_flashcard_interactions WHERE user_id = ? AND question_id = ? AND interaction_type = 'favorite'");
        $stmt->execute([$_SESSION['user_id'], $question_id]);
        $is_favorite = $stmt->fetchColumn() > 0;

        if ($is_favorite) {
            $stmt = $db->prepare("DELETE FROM user_flashcard_interactions WHERE user_id = ? AND question_id = ? AND interaction_type = 'favorite'");
            $stmt->execute([$_SESSION['user_id'], $question_id]);
        } else {
            $stmt = $db->prepare("INSERT INTO user_flashcard_interactions (user_id, question_id, interaction_type) VALUES (?, ?, 'favorite')");
            $stmt->execute([$_SESSION['user_id'], $question_id]);
        }
    }
    $redirect = $from === 'search' ? "index.php?action=search&query=" . urlencode($query) : "index.php?action=$from";
    header("Location: $redirect");
    exit;
    break;

	
	
	

    case 'code snippets':
        $snippets = getCodeSnippets();
        include 'views/header.php';
        include 'views/code_snippets.php';
        include 'views/footer.php';
        break;

    default:
        $pageTitle = "Home";
        $featured_flashcards = getFeaturedFlashcards($db);
        include 'views/header.php';
        include 'views/home.php';
        include 'views/footer.php';
        break;
}

ob_end_flush();
?>