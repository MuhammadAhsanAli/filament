# Seat Management System


## Project Overview
The **Seat Management System** is a robust application developed using Filament Laravel, designed to streamline the process of seat booking, such as creating items, manage orders and dining establishments. This project encompasses a comprehensive development cycle that begins with detailed **Figma designs** and transitions into HTML implementations. 

The system features two distinct user roles: **Admin** and **Staff**, each with tailored functionalities to enhance operational efficiency. Admin users can oversee the entire booking process, and configure settings, while Staff members focus on managing bookings and customer interactions.

## Features
- **User Authentication**: Secure login and registration for both Admin and Staff users.
- **Easy Booking System**: Simplified seat selection and booking process for staff, ensuring a seamless experience.
- **Admin Dashboard**: A centralized interface for Admins to manage users, view bookings, and generate reports.
- **Real-Time Notifications**: Instant notification and alerts for users regarding booking.
- **Data Visualization**: Interactive tables and widgets to represent booking statistics and overview effectively.
- **Role-Based Access Control**: Defined permissions for Admin and Staff roles, ensuring secure and efficient management.


## Project Setup Steps
Follow these steps to set up the project locally:

### Prerequisites
- PHP >= 8.2
- Composer
- Laravel >= 8
- MySQL 

### Installation
1. **Clone the repository**:
   ```bash
   git clone https://github.com/MuhammadAhsanAli/filament.git
   cd filament

2. Install PHP dependencies:
 ```
composer install
 ```

3. Copy the environment file:
```
cp .env.example .env
```
 
4. Generate application key:

```
php artisan key:generate
```

5. Configure your database: Update the .env file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

6. Run migrations and seed the database:

```
php artisan migrate --seed
```

7. Start the development server:

```
php artisan serve
```


