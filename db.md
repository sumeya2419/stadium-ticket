Simplified Database Schema

🔹 Users Table
users
-----
id (PK)
name
email (unique)
phone
password
role (customer, staff, admin)
created_at
🔹 Venues Table
venues
------
id (PK)
name
location
capacity
created_at
🔹 Sections Table
sections
--------
id (PK)
venue_id (FK → venues.id)
name
🔹 Seats Table
seats
-----
id (PK)
section_id (FK → sections.id)
row_number
seat_number
status (available, blocked)
🔹 Events Table
events
------
id (PK)
title
venue_id (FK → venues.id)
event_date
start_time
status (scheduled, cancelled)
created_at
🔹 Ticket Types Table

(For General Admission or VIP)

ticket_types
------------
id (PK)
event_id (FK → events.id)
name (VIP, General)
price
quantity_available
🔹 Orders Table
orders
------
id (PK)
user_id (FK → users.id)
event_id (FK → events.id)
total_amount
status (pending, paid, refunded, cancelled)
created_at
🔹 Order Items Table

Each row represents one ticket.

order_items
-----------
id (PK)
order_id (FK → orders.id)
ticket_type_id (FK → ticket_types.id)
seat_id (FK → seats.id, nullable)
price
qr_code
is_used (boolean)
🔹 Payments Table
payments
--------
id (PK)
order_id (FK → orders.id)
transaction_id
amount
status (pending, success, failed)
paid_at
🔹 Scans Table (Gate Validation)
scans
-----
id (PK)
order_item_id (FK → order_items.id)
scanned_by (FK → users.id)
scanned_at
result (valid, already_used, invalid)
6️⃣ System Workflow (Simple Version)
Purchase Flow
User selects event
    ↓
Choose seat or ticket type
    ↓
Create order (pending)
    ↓
Payment success?
    ↓ yes
Update order = paid
Generate QR
Seat marked as booked
Gate Scan Flow
Scan QR
    ↓
Check order_item
    ↓
If paid AND not used
    ↓
Mark used = true
Save scan record
7️⃣ Basic Security Rules

Passwords → hashed (bcrypt)

Use prepared statements

Check role before admin routes

Prevent double booking using:

Database transaction

Check seat availability before insert

QR code should contain:

Unique token (not plain ID)

8️⃣ Suggested Tech Stack (Simple)

Since you’re already using XAMPP:

Backend: PHP (OOP style if possible)

Database: MySQL

Frontend: HTML + CSS (simple)

JS: Basic DOM interaction

QR Code: PHP QR library

Payment: Mock payment or test gateway

9️⃣ Project Structure Example
stadium_ticket/
│
├── config/
├── controllers/
├── models/
├── views/
├── admin/
├── staff/
├── public/
└── database.sql
