// Initialize navigation
document.addEventListener('DOMContentLoaded', function() {
    // Set up section navigation
    const navLinks = document.querySelectorAll('.admin-nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active navigation link
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Show the corresponding section
            const sectionId = this.getAttribute('data-section');
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        });
    });
    
    // Load initial data
    loadProjects();
    loadSkills();
    loadAchievements();
    loadMessages();
    
    // Set up form buttons
    setupFormButtons();
});

// Setup form visibility toggle buttons
function setupFormButtons() {
    // Project form
    document.getElementById('add-project-btn').addEventListener('click', function() {
        document.getElementById('project-form-container').style.display = 'block';
        document.getElementById('project-form').reset();
        document.getElementById('project-id').value = '';
    });
    
    // Skill form
    document.getElementById('add-skill-btn').addEventListener('click', function() {
        document.getElementById('skill-form-container').style.display = 'block';
        document.getElementById('skill-form').reset();
        document.getElementById('skill-id').value = '';
    });
    
    // Achievement form
    document.getElementById('add-achievement-btn').addEventListener('click', function() {
        document.getElementById('achievement-form-container').style.display = 'block';
        document.getElementById('achievement-form').reset();
        document.getElementById('achievement-id').value = '';
    });
    
    // Cancel buttons
    const cancelButtons = document.querySelectorAll('.cancel-btn');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.form-container').style.display = 'none';
        });
    });
    
    // Setup form submission handlers
    setupFormSubmissions();
}

// Handle form submissions
function setupFormSubmissions() {
    // Projects form
    document.getElementById('project-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const projectId = document.getElementById('project-id').value;
        const projectData = {
            title: document.getElementById('project-title').value,
            description: document.getElementById('project-description').value,
            technologies: document.getElementById('project-technologies').value,
            link: document.getElementById('project-link').value
        };
        
        if (projectId) {
            // Update existing project
            projectData.id = projectId;
            updateProject(projectData);
        } else {
            // Create new project
            createProject(projectData);
        }
    });
    
    // Skills form
    document.getElementById('skill-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const skillId = document.getElementById('skill-id').value;
        const skillData = {
            name: document.getElementById('skill-name').value,
            proficiency: document.getElementById('skill-proficiency').value,
            category: document.getElementById('skill-category').value
        };
        
        if (skillId) {
            // Update existing skill
            skillData.id = skillId;
            updateSkill(skillData);
        } else {
            // Create new skill
            createSkill(skillData);
        }
    });
    
    // Achievements form
    document.getElementById('achievement-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const achievementId = document.getElementById('achievement-id').value;
        const achievementData = {
            title: document.getElementById('achievement-title').value,
            description: document.getElementById('achievement-description').value,
            date: document.getElementById('achievement-date').value
        };
        
        if (achievementId) {
            // Update existing achievement
            achievementData.id = achievementId;
            updateAchievement(achievementData);
        } else {
            // Create new achievement
            createAchievement(achievementData);
        }
    });
}

// ----- CRUD Operations -----

// Load Projects
function loadProjects() {
    fetch('backend/getProjects.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('projects-list');
            container.innerHTML = '';
            data.forEach(project => {
                container.innerHTML += `
                    <div class="item">
                        <h4>${project.title}</h4>
                        <p>${project.description}</p>
                        <small>${project.technologies}</small><br>
                        <a href="${project.link}" target="_blank">View Project</a>
                        <div class="actions">
                            <button onclick='editProject(${JSON.stringify(project)})'>Edit</button>
                            <button onclick='deleteProject(${project.id})'>Delete</button>
                        </div>
                    </div>
                `;
            });
        });
}

function createProject(data) {
    fetch('backend/createProject.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('project-form-container').style.display = 'none';
        loadProjects();
    });
}

function updateProject(data) {
    fetch('backend/updateProject.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('project-form-container').style.display = 'none';
        loadProjects();
    });
}

function deleteProject(id) {
    fetch('backend/deleteProject.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    }).then(() => loadProjects());
}

function editProject(project) {
    document.getElementById('project-form-container').style.display = 'block';
    document.getElementById('project-id').value = project.id;
    document.getElementById('project-title').value = project.title;
    document.getElementById('project-description').value = project.description;
    document.getElementById('project-technologies').value = project.technologies;
    document.getElementById('project-link').value = project.link;
}

// Load Skills
function loadSkills() {
    fetch('backend/getSkills.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('skills-list');
            container.innerHTML = '';
            data.forEach(skill => {
                container.innerHTML += `
                    <div class="item">
                        <h4>${skill.name}</h4>
                        <p>${skill.category} - ${skill.proficiency}</p>
                        <div class="actions">
                            <button onclick='editSkill(${JSON.stringify(skill)})'>Edit</button>
                            <button onclick='deleteSkill(${skill.id})'>Delete</button>
                        </div>
                    </div>
                `;
            });
        });
}

function createSkill(data) {
    fetch('backend/createSkill.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('skill-form-container').style.display = 'none';
        loadSkills();
    });
}

function updateSkill(data) {
    fetch('backend/updateSkill.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('skill-form-container').style.display = 'none';
        loadSkills();
    });
}

function deleteSkill(id) {
    fetch('backend/deleteSkill.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    }).then(() => loadSkills());
}

function editSkill(skill) {
    document.getElementById('skill-form-container').style.display = 'block';
    document.getElementById('skill-id').value = skill.id;
    document.getElementById('skill-name').value = skill.name;
    document.getElementById('skill-proficiency').value = skill.proficiency;
    document.getElementById('skill-category').value = skill.category;
}

// Load Achievements
function loadAchievements() {
    fetch('backend/getAchievements.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('achievements-list');
            container.innerHTML = '';
            data.forEach(achievement => {
                container.innerHTML += `
                    <div class="item">
                        <h4>${achievement.title}</h4>
                        <p>${achievement.description}</p>
                        <small>${achievement.date}</small>
                        <div class="actions">
                            <button onclick='editAchievement(${JSON.stringify(achievement)})'>Edit</button>
                            <button onclick='deleteAchievement(${achievement.id})'>Delete</button>
                        </div>
                    </div>
                `;
            });
        });
}

function createAchievement(data) {
    fetch('backend/createAchievement.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('achievement-form-container').style.display = 'none';
        loadAchievements();
    });
}

function updateAchievement(data) {
    fetch('backend/updateAchievement.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById('achievement-form-container').style.display = 'none';
        loadAchievements();
    });
}

function deleteAchievement(id) {
    fetch('backend/deleteAchievement.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    }).then(() => loadAchievements());
}

function editAchievement(achievement) {
    document.getElementById('achievement-form-container').style.display = 'block';
    document.getElementById('achievement-id').value = achievement.id;
    document.getElementById('achievement-title').value = achievement.title;
    document.getElementById('achievement-description').value = achievement.description;
    document.getElementById('achievement-date').value = achievement.date;
}

// Load Messages
function loadMessages() {
    fetch('backend/getMessages.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('messages-list');
            container.innerHTML = '';
            data.forEach(msg => {
                container.innerHTML += `
                    <div class="message">
                        <strong>${msg.name}</strong> (${msg.email})
                        <p>${msg.message}</p>
                        <small>${msg.date}</small>
                        <button onclick='deleteMessage(${msg.id})'>Delete</button>
                    </div>
                `;
            });
        });
}

function deleteMessage(id) {
    fetch('backend/deleteMessage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    }).then(() => loadMessages());
}
