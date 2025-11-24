# Glassify-CI

**Glassify-CI** is a web-based 2D modeling and ordering system built using **CodeIgniter 3**. This project is designed to streamline the glass and aluminum product modeling and ordering process for businesses.

---

## ⚙️ Framework & Requirements

- **Framework:** CodeIgniter 3
- **PHP Version:** 7.4
- **Local Server:** XAMPP (Windows recommended)
- **XAMPP Download (PHP 7.4.33):** [Download Here](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33)

**Note:** Ensure that the PHP version is exactly 7.4 to avoid compatibility issues with CodeIgniter 3.

---

## 🚀 Getting Started

Follow these steps to set up Glassify-CI on your local machine:

1. **Download and Install XAMPP**
   - Use the link above to download XAMPP with PHP 7.4.
   - Install XAMPP and make sure **Apache** and **MySQL** modules are running.

2. **Clone or Download the Repository**
   ```bash
   git clone <repository-url>
Or download the ZIP and extract it into your xampp/htdocs folder.

Setup Database

Open phpMyAdmin via http://localhost/phpmyadmin/.

Create a new database (e.g., glassify_db).

Import the provided SQL file (if available) to initialize tables.

Configure CodeIgniter

Open application/config/config.php and set:

php
Copy code
$config['base_url'] = 'http://localhost/glassify-ci/';
Open application/config/database.php and update:

php
Copy code
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'glassify_db',
    'dbdriver' => 'mysqli',
    // ...
);
Access the Application

Open your browser and go to:

arduino
Copy code
http://localhost/glassify-ci/
You should now see the Glassify-CI homepage.

🌟 Features
2D modeling of glass and aluminum products

Product ordering and quotation system

User authentication and management

Admin dashboard for managing orders, products, and users

Responsive and user-friendly interface

📂 Documents & Diagrams
For better understanding and development references, you can find the following resources:


System Diagram / Architecture: 
https://drive.google.com/file/d/1X9l5x7Ue_9_YK2a8cxdH1aEFTdUJwQG6/view?usp=sharing

(Replace the example links with actual URLs or file paths.)
