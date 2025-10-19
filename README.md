# Farm Produce Tracker

A web app for Namibian farmers to track crops and livestock. Made for WAD621S project.

## 🌾 Project Overview

Agriculture is the backbone of Namibia's economy and livelihood. Many small and medium-scale farmers still rely on paper-based systems or memory to record harvests, sales, and livestock numbers, which often leads to inefficiency, data loss, and reduced profits.

The Farm Produce & Livestock Tracker aims to digitalize farm management by providing a web platform where farmers can:
- Register and manage their farm profiles
- Record crop planting, growth, and harvest data
- Track livestock inventory and health status
- Monitor sales transactions and profitability
- Generate reports and analytics for better decision-making

## 👥 Team Members

**WAD621S - Part-time Group 105**

- Tjatjitua Tjiyahura (221067264)
- Marcus Nicodemus (214021254)
- Hafeni Hashili (217130097)

**Submission Date:** 12 September 2025

## 🚀 Features

### Core Functionality
- **Farmer Registration & Authentication**: Secure login system with user account management
- **Dashboard**: Comprehensive overview of crops, livestock, and sales statistics
- **Crop Management**: Add, edit, delete, and track crops (maize, mahangu, vegetables, etc.)
- **Livestock Tracking**: Manage cattle, goats, chickens, and other livestock with health monitoring
- **Sales Management**: Record sales transactions, buyer information, and payment status
- **Inventory Reports**: Generate basic statistics and visualizations
- **Mobile Responsive**: Works seamlessly on desktop and mobile devices

### Technical Features
[1m🌾 Farm Produce & Livestock Tracker[0m

A web-based management platform designed to help Namibian farmers efficiently monitor and record their crop and livestock production.
Developed as part of the WAD621S – Web Application Development course project.

## 🧭 Project Overview

Agriculture is the foundation of Namibia’s economy and a vital source of income for many communities. However, most small- and medium-scale farmers continue to rely on paper-based systems or memory to manage their farm operations. This often results in inefficiencies, misplaced records, and lost opportunities for growth.

The Farm Produce & Livestock Tracker was developed to address these challenges by offering an accessible, digital solution for farm data management. The application enables farmers to:

- Register and manage personal farm profiles
- Record crop planting, growth progress, and harvest data
- Track livestock inventory and monitor health status
- Manage sales transactions and profit records
- Generate reports and analytics to support informed decision-making

By bridging the gap between traditional and digital recordkeeping, this project promotes efficiency, transparency, and sustainability in Namibia’s agricultural sector.

## 👥 Project Team

WAD621S – Part-Time Group 105

- Tjatjitua Tjiyahura (221067264)
- Markus Nicodemus (214021254)
- Hafeni Hashili (217130097)

📅 Submission Date: 12 September 2025

## 🚀 System Features

### Core Features

- Farmer Registration & Authentication: Secure login and account management.
- Dashboard Overview: Visual summary of crop, livestock, and sales data.
- Crop Management: Add, update, and monitor crops (e.g., mahangu, maize, vegetables).
- Livestock Tracking: Manage details such as species, health records, and ownership.
- Sales Management: Record transactions, buyers, and payment statuses.
- Reports & Analytics: Generate farm performance insights.
- Mobile Responsive Design: Fully functional on both desktop and mobile devices.

### Technical Features

- CRUD Operations: Full Create, Read, Update, and Delete functionality across all modules.
- Data Validation: Enforced at both client and server levels to ensure data integrity.
- Security Mechanisms: Password hashing, SQL injection prevention, and secure session management.
- User-Friendly Interface: Simple, clean, and intuitive design.
- Real-Time Updates: Instant reflection of new or modified records.

## 🛠️ Technology Stack

### Frontend

- HTML5 – Structured and semantic web layout.
- CSS3 – Modern styling with responsive design principles.
- JavaScript – Client-side interactivity and form validation.
- Font Awesome – Icon library for enhanced UI design.

### Backend

- PHP – Server-side scripting and application logic.
- MySQL – Relational database management.
- PDO (PHP Data Objects) – Secure database connection and query handling.

### Development Tools

- Visual Studio Code – Primary IDE.
- GitHub – Source code management and version control.
- GitHub Pages – Hosting and deployment platform.

## 📁 Project Structure

```
farm-tracker/
├── index.html              # Homepage
├── login.html              # Login page
├── register.html           # User registration
├── about.html              # About page
├── css/
│   └── style.css           # Main stylesheet
├── js/
│   └── main.js             # Client-side logic
├── php/
│   ├── config.php          # Database configuration
│   ├── login.php           # Login logic
│   ├── register.php        # Registration logic
│   ├── dashboard.php       # Main dashboard
│   ├── crops.php           # Crop management
│   ├── livestock.php       # Livestock management
│   ├── sales.php           # Sales management
│   ├── reports.php         # Reports and analytics
│   └── logout.php          # Logout functionality
└── database/
    └── schema.sql          # Database schema and sample data
```

## 🗄️ Database Schema

The MySQL database includes the following main tables:

- users: Farmer profiles and authentication details
- crops: Crop planting, monitoring, and harvest records
- harvests: Yield records and productivity metrics
- livestock: Livestock inventory and health details
- sales: Transaction and buyer records
- inventory: Stock tracking for produce and livestock

## ⚙️ Installation & Setup

### Prerequisites

- Apache/Nginx web server
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Installation Steps

Clone the repository

```bash
git clone https://github.com/madcowgenius/WAD-PROJECT
cd farm-tracker
```

Set up the database

```bash
mysql -u root -p
source database/schema.sql
```

Configure the database connection
Open php/config.php and update credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'farm_tracker');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

Deploy to web server

- Copy project files to your server directory.
- Ensure PHP and MySQL are running.
- Adjust file permissions where necessary.

Access the system

- Open your browser and navigate to your server URL.
- Register a new user or log in using sample credentials.

## 📱 User Guide

### For Farmers

- Registration: Create a personal farm account.
- Dashboard: View crop, livestock, and sales summaries.
- Crop Management: Record planting, track growth, and log harvests.
- Livestock Tracking: Add livestock records and monitor health updates.
- Sales Tracking: Record transactions and generate reports.

### For Buyers

- Browse available farm products and livestock.
- Contact farmers directly for inquiries or purchases.
- Build partnerships with verified producers.

## 🧪 Testing

Testing focused on the following areas:

- Authentication: Reliable login/logout functionality.
- Data Validation: Secure and accurate form submissions.
- CRUD Operations: Functional across all modules.
- Responsive Design: Tested on mobile and desktop environments.
- Security: Protection against SQL injection and other vulnerabilities.

## 🔒 Security Features

- Password Hashing: Secure password encryption using PHP’s password_hash() function.
- SQL Injection Prevention: Safe queries through prepared statements.
- Input Sanitization: All inputs validated and sanitized before database interaction.
- Session Management: Controlled and secure user sessions.
- Dual-Level Validation: Both client-side and server-side checks.

## � Future Enhancements

Planned improvements for future development include:

- Native Mobile Application for Android/iOS.
- Advanced Analytics using AI/ML for predictions.
- Integrated Payment Gateway for transactions.
- Weather Forecast Integration for agricultural planning.
- Online Marketplace to connect farmers and buyers.
- Multi-language Support for local accessibility.
- Offline Functionality to enable rural usage.

## 🤝 Contribution Guidelines

Although this is an academic project, constructive feedback and contributions are welcome.

- Fork the repository.
- Create a new feature branch.
- Implement and test your changes.
- Submit a pull request for review.

## 📄 License

This system was developed solely for academic purposes under the WAD621S – Web Application Development module.

## 📞 Support

For questions or technical assistance, please contact:

- ## Markus Nicodemus F.T.
- Email: d.walaulanft94@gmail.com
- Phone: +264 81 366 5914

- ## Tjatjitua Tjiyahura
- Email: tjatjitua055@gmail.com
- Phone: +264 81 321 6667

- ## Hafeni Hashili
- Email: hashilihafeni4@gmail.com
- Phone: +264 81 402 0898

## 🙏 Acknowledgments

Special appreciation goes to:

- Mozilla Developer Network (MDN) – Web development documentation.
- W3Schools – Learning resources for HTML, CSS, JavaScript, and PHP.
- GitHub Documentation – Version control best practices.
- Font Awesome – UI icons and visual enhancements.
- Namibian Agricultural Community – Inspiration and use-case insights.

Developed with passion to Empower Namibian Farmers
Harnessing technology to strengthen agriculture and sustainability.
