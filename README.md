
---

#  Event Management System

**Event Management System** – A simple web application for managing events and tracking attendees. Built with PHP

![PHP](https://img.shields.io/badge/PHP-8.2-purple) 
![MySQL](https://img.shields.io/badge/MySQL-10.4-blue) 
![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Features
- **Event Management** – Create, edit, delete events with details like name, description, date, location, and ticket price.  
- **Attendee Registration** – Register attendees, auto-generate ticket numbers, and manage attendee lists.  
- **Analytics** – Track attendance, calculate revenue, and view event stats.  

## 🔧 Tech Stack
**Backend:** PHP 8.2  
**Database:** MySQL (MariaDB 10.4)  
**Frontend:** HTML5, CSS3, JavaScript  
**UI:** Pixel art with "Press Start 2P" font  

## 💻 Installation
1. **Clone Repo**  
```bash
git clone https://github.com/onyxkurbah/event_management_system.git
```
2. **Setup Database**  
   - Create a MySQL DB `event_management_system`  
   - Import `event_management_system.sql`  

3. **Configure Database** – Edit `db.php` with your DB credentials.  
4. **Deploy** – Copy files to your web server.  
5. **Access** – Open `http://your-server/event-management-system`.  

## 📁 Structure
```
├── css/                 # Stylesheets
├── includes/            # Templates & Helpers
├── create_event.php     # Create Event
├── register_attendee.php # Register Attendee
├── event_analytics.php  # View Stats
├── db.php               # DB Connection
└── event_management_system.sql
```

## 🎯 Usage
- **Create Events** – Fill in details and submit.  
- **Register Attendees** – Register attendees and generate tickets.  
- **View Analytics** – Track stats and revenue.  

## 🛠️ Customization
- Edit styles in `css/styles.css`  
- Change currency symbol in display files  
- Modify ticket format in `generateTicketNumber()`  

## 🔒 Security
- Uses PDO with prepared statements  
- Consider adding user authentication & CSRF protection  

## 📜 License
Licensed under the MIT License.  

## 👤 Author
**Onyx Kurbah** – [onyxkurbah0@gmail.com](mailto:onyxkurbah0@gmail.com)  

---