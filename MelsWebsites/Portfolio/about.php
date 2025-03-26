<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$page_title = "Home - Melanie's Portfolio";
$current_page = "home";
require_once 'includes/header.php';
require_once 'includes/db_connect.php'; // Updated to connect to melanie_portfolio
?>

<main class="main-content">
    <section id="home" >
        <p class="section-subtitle">Welcome to my professional portfolio. Here you'll find my latest projects and information about my skills and experience.</p>
    </section>

    <section class="elegant-section" id="portfolio-tables">
        <h2>My Skills and Experience</h2>
        <p class="section-subtitle">Explore my employment history, technology skills, and other abilities.</p>

        <!-- Employment Experience Collapsible Section -->
        <div class="collapsible-section">
            <h3 class="section-toggle">Employment Experience <span class="toggle-arrow">▼</span></h3>
            <div class="table-content">
                <form method="GET" action="index.php" class="search-form">
                    <div class="form-group">
                        <label for="exp_search">Search Experience:</label>
                        <input type="text" id="exp_search" name="exp_search" value="<?php echo isset($_GET['exp_search']) ? htmlspecialchars($_GET['exp_search']) : ''; ?>" placeholder="e.g., Geek Squad, Timmins">
                        <button type="submit" class="submit-btn">Search</button>
                    </div>
                </form>

                <div class="table-scroll">
                    <table class="portfolio-table" id="experience-table">
                        <thead>
                            <tr>
                                <th><a href="?exp_sort=position_title&exp_order=<?php echo (isset($_GET['exp_sort']) && $_GET['exp_sort'] == 'position_title' && $_GET['exp_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&exp_search=<?php echo urlencode($_GET['exp_search'] ?? ''); ?>">Position Title</a></th>
                                <th><a href="?exp_sort=organization&exp_order=<?php echo (isset($_GET['exp_sort']) && $_GET['exp_sort'] == 'organization' && $_GET['exp_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&exp_search=<?php echo urlencode($_GET['exp_search'] ?? ''); ?>">Organization</a></th>
                                <th><a href="?exp_sort=start_date&exp_order=<?php echo (isset($_GET['exp_sort']) && $_GET['exp_sort'] == 'start_date' && $_GET['exp_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&exp_search=<?php echo urlencode($_GET['exp_search'] ?? ''); ?>">Start Date</a></th>
                                <th><a href="?exp_sort=end_date&exp_order=<?php echo (isset($_GET['exp_sort']) && $_GET['exp_sort'] == 'end_date' && $_GET['exp_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&exp_search=<?php echo urlencode($_GET['exp_search'] ?? ''); ?>">End Date</a></th>
                                <th><a href="?exp_sort=location&exp_order=<?php echo (isset($_GET['exp_sort']) && $_GET['exp_sort'] == 'location' && $_GET['exp_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&exp_search=<?php echo urlencode($_GET['exp_search'] ?? ''); ?>">Location</a></th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $exp_search = isset($_GET['exp_search']) ? trim($_GET['exp_search']) : '';
                        $exp_sort = isset($_GET['exp_sort']) && in_array($_GET['exp_sort'], ['position_title', 'organization', 'start_date', 'end_date', 'location']) ? $_GET['exp_sort'] : 'start_date';
                        $exp_order = isset($_GET['exp_order']) && in_array($_GET['exp_order'], ['ASC', 'DESC']) ? $_GET['exp_order'] : 'DESC';

                        $sql = "SELECT position_title, organization, start_date, end_date, location, description 
                                FROM employment_experience 
                                WHERE position_title LIKE :search 
                                   OR organization LIKE :search 
                                   OR start_date LIKE :search 
                                   OR end_date LIKE :search 
                                   OR location LIKE :search 
                                   OR description LIKE :search 
                                ORDER BY $exp_sort $exp_order";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['search' => "%$exp_search%"]);
                        $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($experiences) {
                            $index = 0;
                            foreach ($experiences as $exp) {
                                if ($index >= 10) break; // Limit to 10 rows
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($exp['position_title']) . "</td>";
                                echo "<td>" . htmlspecialchars($exp['organization']) . "</td>";
                                echo "<td>" . htmlspecialchars($exp['start_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($exp['end_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($exp['location']) . "</td>";
                                echo "<td>" . htmlspecialchars($exp['description']) . "</td>";
                                echo "</tr>";
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No experience found matching your search.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Technology Skills Collapsible Section -->
        <div class="collapsible-section">
            <h3 class="section-toggle">Technology Skills <span class="toggle-arrow">▼</span></h3>
            <div class="table-content">
                <form method="GET" action="index.php" class="search-form">
                    <div class="form-group">
                        <label for="tech_search">Search Technology Skills:</label>
                        <input type="text" id="tech_search" name="tech_search" value="<?php echo isset($_GET['tech_search']) ? htmlspecialchars($_GET['tech_search']) : ''; ?>" placeholder="e.g., Networking, Java">
                        <button type="submit" class="submit-btn">Search</button>
                    </div>
                </form>

                <div class="table-scroll">
                    <table class="portfolio-table" id="tech-table">
                        <thead>
                            <tr>
                                <th><a href="?tech_sort=skill_name&tech_order=<?php echo (isset($_GET['tech_sort']) && $_GET['tech_sort'] == 'skill_name' && $_GET['tech_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&tech_search=<?php echo urlencode($_GET['tech_search'] ?? ''); ?>">Skill Name</a></th>
                                <th><a href="?tech_sort=category&tech_order=<?php echo (isset($_GET['tech_sort']) && $_GET['tech_sort'] == 'category' && $_GET['tech_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&tech_search=<?php echo urlencode($_GET['tech_search'] ?? ''); ?>">Category</a></th>
                                <th><a href="?tech_sort=certification&tech_order=<?php echo (isset($_GET['tech_sort']) && $_GET['tech_sort'] == 'certification' && $_GET['tech_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&tech_search=<?php echo urlencode($_GET['tech_search'] ?? ''); ?>">Certification</a></th>
                                <th><a href="?tech_sort=year&tech_order=<?php echo (isset($_GET['tech_sort']) && $_GET['tech_sort'] == 'year' && $_GET['tech_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&tech_search=<?php echo urlencode($_GET['tech_search'] ?? ''); ?>">Year</a></th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $tech_search = isset($_GET['tech_search']) ? trim($_GET['tech_search']) : '';
                        $tech_sort = isset($_GET['tech_sort']) && in_array($_GET['tech_sort'], ['skill_name', 'category', 'certification', 'year']) ? $_GET['tech_sort'] : 'skill_name';
                        $tech_order = isset($_GET['tech_order']) && in_array($_GET['tech_order'], ['ASC', 'DESC']) ? $_GET['tech_order'] : 'ASC';

                        $sql = "SELECT skill_name, category, certification, year, description 
                                FROM technology_skills 
                                WHERE skill_name LIKE :search 
                                   OR category LIKE :search 
                                   OR certification LIKE :search 
                                   OR year LIKE :search 
                                   OR description LIKE :search 
                                ORDER BY $tech_sort $tech_order";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['search' => "%$tech_search%"]);
                        $tech_skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($tech_skills) {
                            $index = 0;
                            foreach ($tech_skills as $skill) {
                                if ($index >= 10) break; // Limit to 10 rows
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($skill['skill_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($skill['category']) . "</td>";
                                echo "<td>" . htmlspecialchars($skill['certification'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($skill['year'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($skill['description']) . "</td>";
                                echo "</tr>";
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='5'>No technology skills found matching your search.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Other Skills Collapsible Section -->
        <div class="collapsible-section">
            <h3 class="section-toggle">Other Skills <span class="toggle-arrow">▼</span></h3>
            <div class="table-content">
                <form method="GET" action="index.php" class="search-form">
                    <div class="form-group">
                        <label for="other_search">Search Other Skills:</label>
                        <input type="text" id="other_search" name="other_search" value="<?php echo isset($_GET['other_search']) ? htmlspecialchars($_GET['other_search']) : ''; ?>" placeholder="e.g., Communication, Teamwork">
                        <button type="submit" class="submit-btn">Search</button>
                    </div>
                </form>

                <div class="table-scroll">
                    <table class="portfolio-table" id="other-table">
                        <thead>
                            <tr>
                                <th><a href="?other_sort=skill_name&other_order=<?php echo (isset($_GET['other_sort']) && $_GET['other_sort'] == 'skill_name' && $_GET['other_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&other_search=<?php echo urlencode($_GET['other_search'] ?? ''); ?>">Skill Name</a></th>
                                <th><a href="?other_sort=category&other_order=<?php echo (isset($_GET['other_sort']) && $_GET['other_sort'] == 'category' && $_GET['other_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&other_search=<?php echo urlencode($_GET['other_search'] ?? ''); ?>">Category</a></th>
                                <th><a href="?other_sort=level&other_order=<?php echo (isset($_GET['other_sort']) && $_GET['other_sort'] == 'level' && $_GET['other_order'] == 'ASC') ? 'DESC' : 'ASC'; ?>&other_search=<?php echo urlencode($_GET['other_search'] ?? ''); ?>">Level</a></th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $other_search = isset($_GET['other_search']) ? trim($_GET['other_search']) : '';
                        $other_sort = isset($_GET['other_sort']) && in_array($_GET['other_sort'], ['skill_name', 'category', 'level']) ? $_GET['other_sort'] : 'skill_name';
                        $other_order = isset($_GET['other_order']) && in_array($_GET['other_order'], ['ASC', 'DESC']) ? $_GET['other_order'] : 'ASC';

                        $sql = "SELECT skill_name, category, level, description 
                                FROM other_skills 
                                WHERE skill_name LIKE :search 
                                   OR category LIKE :search 
                                   OR level LIKE :search 
                                   OR description LIKE :search 
                                ORDER BY $other_sort $other_order";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['search' => "%$other_search%"]);
                        $other_skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($other_skills) {
                            $index = 0;
                            foreach ($other_skills as $skill) {
                                if ($index >= 10) break; // Limit to 10 rows
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($skill['skill_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($skill['category']) . "</td>";
                                echo "<td>" . htmlspecialchars($skill['level']) . "</td>";
                                echo "<td>" . htmlspecialchars($skill['description']) . "</td>";
                                echo "</tr>";
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='4'>No other skills found matching your search.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Collapsible sections
    document.querySelectorAll('.section-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const arrow = this.querySelector('.toggle-arrow');
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                arrow.textContent = '▼';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                arrow.textContent = '▲';
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>