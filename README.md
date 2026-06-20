# Hostel Management Web Application

A comprehensive web-based hostel management system built with PHP and MySQL. This application helps manage student registrations, room allocations, complaints, payments, and notices.

## Features

- **Student Management**: Register and manage student profiles
- **Room Management**: Allocate and manage hostel rooms
- **Payment Tracking**: Track student payments and fees
- **Complaint System**: Students can file complaints and admins can review them
- **Notice Board**: Post and manage hostel notices
- **Admin Dashboard**: Comprehensive dashboard for administrators
- **User Authentication**: Secure login system for students and admins
- **Profile Management**: Students can update their profiles

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/hidayathassam05-prog/hostel-management.git
cd hostel-management
```

### 2. Set Up Database

1. Create a new MySQL database:
```sql
CREATE DATABASE hostel_management;
```

2. Import the database schema (if available, or run the setup script):
```bash
php setup.php
```

Or manually create tables using `db-init.php`

### 3. Configure Database Connection

Edit `php/config.php` and update your database credentials:
```php
$servername = "localhost";
$username = "your_db_user";
$password = "your_db_password";
$database = "hostel_management";
```

### 4. Deploy to Web Server

Copy all files to your web server's root directory (e.g., `htdocs` for Apache)

### 5. Access the Application

- **URL**: `http://localhost/hostel-management/`
- **Admin Registration**: Use admin code `ADMIN123` during registration

## Usage

### User Roles

- **Student**: Can register, view profile, file complaints, check room info, make payments
- **Admin**: Can manage students, rooms, complaints, payments, and post notices

### Default Admin Code
For registering as admin: `ADMIN123`

## Project Structure

```
hostel-management/
├── index.php                 # Main landing page
├── php/
│   ├── config.php           # Database configuration
│   ├── login.php            # Login page
│   ├── register.php         # Registration page
│   ├── admin-dashboard.php  # Admin dashboard
│   ├── admin-students.php   # Manage students
│   ├── admin-rooms.php      # Manage rooms
│   ├── admin-complaints.php # Review complaints
│   ├── admin-payments.php   # Track payments
│   ├── admin-notices.php    # Post notices
│   ├── student-dashboard.php # Student dashboard
│   ├── profile.php          # User profile
│   ├── complaints.php       # File complaints
│   ├── room-info.php        # Room information
│   ├── payments.php         # Payment tracking
│   ├── notices.php          # View notices
│   └── logout.php           # Logout
├── css/
│   └── style.css            # Stylesheet
├── js/
│   └── script.js            # JavaScript functionality
└── images/                  # Image assets
```

## Database Tables

The application uses the following main tables:
- `users` - Student and admin accounts
- `rooms` - Hostel room information
- `allocations` - Student room allocations
- `payments` - Payment records
- `complaints` - Student complaints
- `notices` - Hostel notices

## Security Notes

⚠️ **Important**: 
- Change the default admin code (`ADMIN123`) in production
- Never commit `php/config.php` with real credentials (use `.gitignore`)
- Use HTTPS in production
- Implement proper input validation and sanitization
- Use prepared statements for database queries

## Debugging

Several debugging scripts are included for troubleshooting:
- `db-diagnostic.php` - Check database connection
- `db-init.php` - Initialize/reset database
- `check-tables.php` - Verify database tables
- `schema-check.php` - Review database schema

## Troubleshooting

### Database Connection Error
1. Verify MySQL is running
2. Check credentials in `php/config.php`
3. Ensure database exists: `hostel_management`
4. Run `db-diagnostic.php` to test connection

### Login Issues
1. Verify user exists in database
2. Check password is correct
3. Clear browser cache and cookies

## Future Enhancements

- Email notifications for payments and complaints
- SMS alerts for important notices
- Mobile app integration
- Advanced reporting and analytics
- Integration with payment gateways

## License

This project is open source and available under the MIT License.

## Contact & Support

For issues, questions, or suggestions, please create an issue on GitHub.

---

**Last Updated**: 2026-06-20
