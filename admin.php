<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
    // Include authentication check
    require_once '../utils/auth_check.php';
    check_auth();
    
    // Generate CSRF token
    require_once '../utils/validation.php';
    $csrf_token = Validator::generateToken();
    ?>
    
    <div class="admin-container">
        <header class="admin-header">
            <h1>Portfolio Admin Dashboard</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | 
                <a href="logout.php">Logout</a>
            </div>
        </header>
        
        <nav class="admin-nav">
            <ul>
                <li><a href="#projects" class="active" data-section="projects">Projects</a></li>
                <li><a href="#skills" data-section="skills">Skills</a></li>
                <li><a href="#achievements" data-section="achievements">Achievements</a></li>
                <li><a href="#messages" data-section="messages">Messages</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <!-- Projects Section -->
            <section id="projects" class="content-section active">
                <div class="section-header">
                    <h2>Manage Projects</h2>
                    <button class="add-btn" id="add-project-btn">Add New Project</button>
                </div>
                
                <div class="form-container" id="project-form-container" style="display:none;">
                    <form id="project-form" class="admin-form">
                        <input type="hidden" id="project-id">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="form-group">
                            <label for="project-title">Title</label>
                            <input type="text" id="project-title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="project-description">Description</label>
                            <textarea id="project-description" name="description" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="project-technologies">Technologies</label>
                            <input type="text" id="project-technologies" name="technologies" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="project-link">Project Link</label>
                            <input type="url" id="project-link" name="link">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Project</button>
                            <button type="button" class="cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <div class="data-table-container">
                    <table class="data-table" id="projects-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Technologies</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Projects will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Skills Section -->
            <section id="skills" class="content-section">
                <div class="section-header">
                    <h2>Manage Skills</h2>
                    <button class="add-btn" id="add-skill-btn">Add New Skill</button>
                </div>
                
                <div class="form-container" id="skill-form-container" style="display:none;">
                    <form id="skill-form" class="admin-form">
                        <input type="hidden" id="skill-id">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="form-group">
                            <label for="skill-name">Skill Name</label>
                            <input type="text" id="skill-name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="skill-proficiency">Proficiency (%)</label>
                            <input type="number" id="skill-proficiency" name="proficiency" min="1" max="100" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="skill-category">Category</label>
                            <input type="text" id="skill-category" name="category">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Skill</button>
                            <button type="button" class="cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <div class="data-table-container">
                    <table class="data-table" id="skills-table">
                        <thead>
                            <tr>
                                <th>Skill</th>
                                <th>Proficiency</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Skills will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Achievements Section -->
            <section id="achievements" class="content-section">
                <div class="section-header">
                    <h2>Manage Achievements</h2>
                    <button class="add-btn" id="add-achievement-btn">Add New Achievement</button>
                </div>
                
                <div class="form-container" id="achievement-form-container" style="display:none;">
                    <form id="achievement-form" class="admin-form">
                        <input type="hidden" id="achievement-id">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="form-group">
                            <label for="achievement-title">Title</label>
                            <input type="text" id="achievement-title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="achievement-description">Description</label>
                            <textarea id="achievement-description" name="description" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="achievement-date">Date</label>
                            <input type="date" id="achievement-date" name="date">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Achievement</button>
                            <button type="button" class="cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <div class="data-table-container">
                    <table class="data-table" id="achievements-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Achievements will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Messages Section -->
            <section id="messages" class="content-section">
                <div class="section-header">
                    <h2>View Messages</h2>
                </div>
                
                <div class="data-table-container">
                    <table class="data-table" id="messages-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Messages will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    
    <!-- Add a hidden div for confirmation dialogs -->
    <div id="confirm-dialog" class="dialog-overlay" style="display:none;">
        <div class="dialog-content">
            <h3>Confirm Action</h3>
            <p id="confirm-message">Are you sure you want to delete this item?</p>
            <div class="dialog-buttons">
                <button id="confirm-yes" class="btn-danger">Yes, Delete</button>
                <button id="confirm-no" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Admin functionality -->
    <script>
        // Store the CSRF token for AJAX requests
        const csrfToken = '<?php echo $csrf_token; ?>';
        
        // Add this to all fetch requests that modify data
        const fetchOptions = {
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            }
        };
        
        // Rest of admin JavaScript will go here
        // This would include functions to load data, handle form submissions,
        // and manage the UI interactions
    </script>
    <script src="js/admin.js"></script>
</body>
</html>