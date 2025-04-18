# InternIntelligence Portfolio

This repository contains a **Portfolio Management System** created for InternIntelligence. It allows users to manage and showcase their **projects**, **skills**, **achievements**, and **messages** using a dynamic interface powered by PHP and MySQL.

## ✨ Features

- 🔐 Login Authentication
- 📁 Project CRUD (Create, Read, Update, Delete)
- 🧠 Skill Management
- 🏆 Achievement Showcase
- 📬 Contact Message Handling
- 🎨 Modern UI with HTML & CSS
- ⚙️ Backend Powered by PHP & MySQL

## 🧰 Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Database**: MySQL

## 🗂️ Folder Structure

InternIntelligence_Portfolio/
├── assets/                # Stylesheets, images, and JavaScript
├── dashboard/             # Admin dashboard and CRUD interfaces
│   ├── projects.php
│   ├── skills.php
│   ├── achievements.php
│   └── messages.php
├── auth/                  # Login & Logout scripts
├── config/                # Database configuration
├── index.html             # Landing page or login interface
├── dashboard.php          # Dashboard home after login
├── README.md              # Documentation

## 🚀 Getting Started

### Prerequisites

- PHP 7.x or later
- MySQL Server
- Apache Server (XAMPP/WAMP/LAMP)

### Installation Steps

1. **Clone this repository**

git clone https://github.com/Tanneeshha/InternIntelligence_Portfolio.git


2. **Set up the database**
   - Go to `phpMyAdmin`
   - Create a new database (e.g., `portfolio_db`)
   - Import the provided SQL file

3. **Configure database connection**
   - Open `config/db.php` and update credentials:
     
     $host = "localhost";
     $user = "root";
     $pass = "";
     $dbname = "portfolio_db";

4. **Run on localhost**
   - Start Apache & MySQL via XAMPP/WAMP
   - Visit: http://localhost/InternIntelligence_Portfolio/
     
## 📌 Future Improvements

- User role management
- Profile photo and bio sections
- Search and filter features
- Mobile responsiveness improvements

## 🤝 Contributing

Feel free to fork this repo, submit issues, or create pull requests to contribute.

## 📜 License

This project is licensed under the [MIT License](LICENSE).


> Built by [Taneesha](https://github.com/Tanneeshha)

