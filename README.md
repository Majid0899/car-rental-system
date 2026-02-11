# Car Rental System

A complete full-stack car rental management system built with PHP, MySQL, HTML, CSS (Tailwind), and JavaScript.

## Features

### For Customers:
- User registration and login
- Browse all available cars
- View car details (model, number, seating capacity, rent per day)
- Book cars with start date and rental duration
- View booking history

### For Car Rental Agencies:
- Agency registration and login
- Add new cars to the fleet
- Edit existing car details
- Delete cars from the fleet
- View all bookings made by customers
- Mark bookings as completed
- Dashboard with booking statistics

### Security Features:
- Password hashing with bcrypt
- SQL injection prevention using prepared statements
- XSS protection with input sanitization
- Session-based authentication
- Role-based access control (Customer/Agency)
- Protected file uploads directory

## Tech Stack

- **Frontend:** HTML5, Tailwind CSS, JavaScript, Font Awesome
- **Backend:** Core PHP 7.4+
- **Database:** MySQL 5.7+
- **Server:** Apache with mod_rewrite

## Installation Guide

### Prerequisites
- XAMPP/WAMP/MAMP or similar local server environment
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache server with mod_rewrite enabled

### Step 1: Clone/Download the Project
```bash
# Place the project in your web server directory
# For XAMPP: C:/xampp/htdocs/
# For WAMP: C:/wamp/www/
```

### Step 2: Database Setup

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database or use the SQL file:
   - Import the file: `database/schema.sql`
   - Or manually create database: `car_rental_system`
3. The schema includes sample data:
   - Agency: email: `agency@test.com`, password: `agency123`
   - Customer: email: `customer@test.com`, password: `customer123`

### Step 3: Configure Database Connection

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASS', '');              // Your MySQL password
define('DB_NAME', 'car_rental_system');
```

### Step 4: Configure Base URL

Edit `config/database.php`:
```php
define('BASE_URL', 'http://localhost/car-rental-system');
```

### Step 5: Set Permissions (Linux/Mac)
```bash
chmod -R 755 car-rental-system/
chmod -R 777 car-rental-system/uploads/
```

### Step 6: Access the Application

Open your browser and navigate to:
```
http://localhost/car-rental-system
```


## Folder Structure

```
car-rental-system/
│
├── config/
│   └── database.php                 # Database configuration
│
├── includes/
│   ├── header.php                   # Common header
│   ├── footer.php                   # Common footer
│   └── navbar.php                   # Navigation bar
│
├── classes/
│   ├── Database.php                 # Database connection class
│   ├── User.php                     # User model
│   ├── Car.php                      # Car model
│   └── Booking.php                  # Booking model
│
├── controllers/
│   ├── AuthController.php           # Authentication logic
│   ├── CarController.php            # Car management logic
│   └── BookingController.php        # Booking logic
│
├── views/
│   ├── auth/
│   │   ├── customer-register.php    # Customer registration page
│   │   ├── agency-register.php      # Agency registration page
│   │   └── login.php                # Login page
│   ├── customer/
│   │   └── available-cars.php       # Browse and book cars
│   └── agency/
│       ├── add-car.php              # Add/manage cars
│       ├── edit-car.php             # Edit car details
│       └── view-bookings.php        # View customer bookings
│
├── database/
│   └── schema.sql                   # Database schema
│
├── uploads/                         # File uploads directory
│
├── index.php                        # Home page
├── logout.php                       # Logout script
└── .htaccess                        # Apache configuration
```

## Database Schema

### Tables

1. **users** - Stores customer and agency accounts
   - id, user_type, email, password, full_name, phone, address
   - agency_name, license_number (for agencies)

2. **cars** - Stores car information
   - id, agency_id, vehicle_model, vehicle_number
   - seating_capacity, rent_per_day, status

3. **bookings** - Stores rental bookings
   - id, customer_id, car_id, agency_id
   - start_date, end_date, rental_days
   - total_amount, booking_status

## Key Features Implementation

### 1. User Registration
- Separate forms for customers and agencies
- Email uniqueness validation
- Password strength requirements (min 6 characters)
- Password hashing using PHP's password_hash()

### 2. Authentication
- Secure login with password verification
- Session-based user tracking
- Role-based redirects after login

### 3. Car Management (Agency)
- Add new cars with validation
- Edit existing car details
- Delete cars (with booking checks)
- View all cars in the fleet

### 4. Car Booking (Customer)
- Browse all available cars
- Select rental dates and duration
- Automatic total calculation
- Booking confirmation

### 5. Booking Management (Agency)
- View all customer bookings
- See customer contact information
- Mark bookings as completed
- Revenue statistics dashboard

## Security Measures

1. **SQL Injection Prevention**
   - All queries use prepared statements
   - Parameter binding for user inputs

2. **XSS Protection**
   - Input sanitization with htmlspecialchars()
   - Output escaping in templates

3. **Password Security**
   - Bcrypt hashing (PASSWORD_DEFAULT)
   - Passwords never stored in plain text

4. **Session Security**
   - Session-based authentication
   - Session timeout implementation
   - Secure session handling

5. **Access Control**
   - Role-based permissions
   - Protected routes for customers/agencies
   - Automatic redirects for unauthorized access

## Usage Guide

### As a Customer:

1. **Register**
   - Go to Register → As Customer
   - Fill in your details
   - Submit the form

2. **Browse Cars**
   - Navigate to "Available Cars"
   - View all available vehicles

3. **Book a Car**
   - Login to your account
   - Select a car
   - Choose start date and rental days
   - Click "Rent This Car"

### As an Agency:

1. **Register**
   - Go to Register → As Agency
   - Fill in business details
   - Submit the form

2. **Add Cars**
   - Login to your account
   - Navigate to "Manage Cars"
   - Fill in car details
   - Click "Add Car"

3. **Manage Cars**
   - View all your cars
   - Edit car details
   - Delete cars if needed

4. **View Bookings**
   - Navigate to "View Bookings"
   - See all customer bookings
   - View customer contact information
   - Mark bookings as completed

## Validation Rules

### User Registration:
- Email: Valid email format, unique in database
- Password: Minimum 6 characters
- Phone: Required
- Full Name: Required
- Agency Name & License: Required for agencies

### Car Details:
- Vehicle Model: Required
- Vehicle Number: Required, unique in database
- Seating Capacity: Positive integer, 1-50
- Rent Per Day: Positive decimal number

### Booking:
- Start Date: Cannot be in the past
- Rental Days: 1-90 days
- Car must be available for selected dates

## Troubleshooting

### Database Connection Error
- Check `config/database.php` settings
- Verify MySQL is running
- Ensure database exists

### Page Not Found (404)
- Check Apache mod_rewrite is enabled
- Verify .htaccess is present
- Check BASE_URL in config

### Session Not Working
- Ensure PHP session is enabled
- Check folder permissions
- Verify session_start() is called

### Cars Not Showing
- Check database has car records
- Verify car status is 'available'
- Check for SQL errors in logs

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Opera


## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the code comments
3. Check PHP error logs
4. Verify database connections

## License

This project is created for educational purposes.

## Credits

- **Developer:** Full Stack Developer
- **Frontend:** Tailwind CSS
- **Icons:** Font Awesome
- **Database:** MySQL

---

**Note:** Remember to change default passwords and configure proper security settings before deploying to production!