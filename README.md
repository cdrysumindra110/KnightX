# KnightX - Gaming Electronics & Game Cards E-commerce Platform

A modern, tech-friendly e-commerce website specializing in gaming electronics and game cards.

## Features

- Dynamic, animated hero banner with high-tech neon aesthetics
- Responsive dark-themed UI with neon-glow accents
- Real-time product search and filtering
- Secure user authentication system
- Shopping cart with real-time updates
- Multiple payment gateway integration
- Order management system
- Admin panel for inventory management

## Tech Stack

- HTML5
- CSS3 (with SASS)
- JavaScript (ES6+)
- PHP 8.0+
- MySQL 8.0+
- GSAP for animations
- jQuery for AJAX operations

## Setup Instructions

1. Clone the repository
2. Set up a local web server (XAMPP recommended)
3. Import the database schema from `database/knightx.sql`
4. Configure database connection in `config/database.php`
5. Install dependencies using Composer:
   ```bash
   composer install
   ```
6. Set up environment variables in `.env` file
7. Access the website through your local server

## Directory Structure

```
KnightX/
├── admin/             # Admin panel files
├── assets/           # Static assets (images, CSS, JS)
├── config/           # Configuration files
├── database/         # Database schema and migrations
├── includes/         # PHP includes and functions
├── vendor/           # Composer dependencies
└── index.php         # Main entry point
```

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention
- XSS protection
- CSRF token implementation
- Secure session management

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 