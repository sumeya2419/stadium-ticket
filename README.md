# StadiumPass | Enterprise Stadium Ticketing & Event Management

StadiumPass is an enterprise-grade, production-ready ticketing platform designed for large-scale stadiums and events. Built with security, scalability, and premium user experience at its core, the system features real-time seat selection, advanced analytics, and robust administrative control.

## 🚀 Key Features

### 🏛️ Smart Event Management
- **Real-Time Seat Maps**: High-fidelity selection grid with atomic locking to prevent double-bookings.
- **Dynamic Seating Engine**: Supports varying stadium layouts and venue capacities.
- **Mock Payment Lifecycle**: Secure checkout simulation with digital invoicing and unique QR codes.

### 🛡️ Enterprise Security (OWASP Aligned)
- **Role-Based Access Control (RBAC)**: Fine-grained permissions for Super Admins, Stadium Managers, Staff, and Customers.
- **CSRF & XSS Mitigation**: Comprehensive protection across all forms and state-changing requests.
- **Hardened Auth**: Bcrypt password hashing and session regeneration for maximum account integrity.

### 📊 Admin Intelligence Dashboard
- **Live Analytics**: Real-time tracking of revenue, occupancy rates, and ticket sales.
- **Activity Feed**: Live stream of transaction and stadium entry logs.
- **Venue Management**: Seamless creation of venues, events, and ticket categories through premium glassmorphic interfaces.

## 🛠️ Technology Stack
- **Backend**: PHP 8+ (OOP, Clean MVC Architecture)
- **Database**: MySQL (3NF Normalized, Optimized Indexing)
- **Frontend**: Modular Vanilla CSS3 (Glassmorphism), ES6+ JavaScript
- **Security**: Custom Security Layer, CSRF Middleware, PDO Prepared Statements

## 📁 Project Structure
```bash
├── config/             # Database & environment configuration
├── controllers/        # Logical controllers (MVC)
├── models/             # Database models & business logic
├── public/             # Entry point & static assets (CSS, JS, Images)
├── src/                # Core system components (Core, Middleware)
├── views/              # Presentation layer (HTML templates)
└── database.sql        # Optimized MySQL schema
```

## ⚙️ Installation & Setup

1. **Prerequisites**:
   - PHP 1.0+
   - MySQL
   - Apache / Nginx

2. **Database Setup**:
   - Create a database named `stadium_ticketing`.
   - Import `database.sql` to initialize tables and seed roles/permissions.

3. **Configuration**:
   - Copy `.env.example` to `.env`.
   - Update database credentials and Base Path.

4. **Serve**:
   - Point your document root to the `/public` directory.

## 🔐 Built for Scale
The platform is designed with a service-oriented mindset, utilizing Singleton patterns for database connections and a modular middleware system for request lifecycle management.

---
*Developed as an enterprise-grade upgrade for professional stadium management.*
