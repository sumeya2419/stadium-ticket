1️⃣ System Overview

The Stadium Ticket Management System is a web-based application that allows:

Customers to:

Register & login

View events

Select seats or ticket type

Purchase tickets

Receive e-ticket (QR code)

View order history

Admins to:

Create and manage events

Manage venues and seats

View orders

Issue refunds

View sales reports

The system ensures:

No double booking of seats

Simple QR validation at entry

Basic access control (admin / staff / customer)

This is a monolithic web application (one project, one database).

2️⃣ 🎯 Project Goal
Main Goal

Build a simple and functional ticket booking system that demonstrates:

CRUD operations

Authentication & Authorization

Relational database design

Payment integration structure (mocked or simple gateway)

QR code generation & validation

Role-based access control

Technical Goals (For You as Developer)

Practice:

PHP (backend logic)

MySQL (database design)

HTML/CSS (simple UI)

JavaScript (basic seat interaction)

Understand:

Transactions

Foreign keys

Inventory handling

Secure authentication

Order lifecycle

3️⃣ 🚫 Non-Goals (What We Will NOT Build)

To keep the system simple, we will NOT implement:

❌ Complex real-time distributed seat locking

❌ AI fraud detection

❌ Multi-payment gateways

❌ Advanced analytics dashboards

❌ Microservices architecture

❌ Multi-language support

❌ Full PCI compliance system

❌ Advanced caching systems

❌ Push notifications

❌ Dynamic seat rendering engine

We focus only on core ticket booking functionality.

4️⃣ Core Modules (Simplified)
1. Authentication

Register

Login

Logout

Role-based access (customer, staff, admin)

2. Event Management (Admin)

Create event

Update event

Delete (soft delete)

List events

3. Venue & Seats

Create venue

Define sections

Add seats

Block/unblock seats

4. Ticket Purchase Flow

Select event

Choose seat OR choose ticket type (GA)

Create order (status = pending)

Process payment (mock or real)

Mark order as paid

Generate QR ticket

Save ticket record

5. Gate Scanning

Staff scans QR

System checks:

Exists?

Paid?

Already used?

Mark as used