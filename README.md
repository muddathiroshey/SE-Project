# Nexus — Freelance Marketplace Platform

> A specialized freelance marketplace connecting clients with skilled professionals. Built as a CS251 Software Engineering project.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Database Schema](#database-schema)
- [Design Patterns](#design-patterns)
- [UML & Documentation](#uml--documentation)
- [Team](#team)

---

## Overview

Nexus is a full-stack web application that facilitates freelance work by connecting clients who need services with freelancers who offer them. The platform supports user registration, profile management, job posting, bidding, and role-based dashboards — all built on a custom MVC framework in PHP.

---

## Features

- **Authentication** — Secure session-based registration and login with role selection (Client / Freelancer)
- **Role-Based Dashboards** — Separate dashboard views and workflows for clients and freelancers
- **Profile Management** — Editable profiles with avatar upload (MIME-validated), bio, and skills
- **Job Postings** — Clients can create, edit, and manage job listings
- **Bidding System** — Freelancers can browse open jobs and submit proposals
- **Admin Panel** — Oversight and management of users and listings
- **SQL Injection Protection** — Prepared statements throughout the data layer

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (custom MVC framework) |
| Database | MySQL (MySQLi extension) |
| Frontend | HTML, CSS, JavaScript |
| Dev Environment | Docker |
| Version Control | Git / GitHub |

---

## Project Structure

```
SE-Project/
├── app/
│   ├── controllers/        # Request handling logic
│   │   ├── AuthController.php
│   │   ├── ProfileController.php
│   │   ├── DashboardController.php
│   │   └── ...
│   ├── models/             # Database interaction layer
│   ├── views/              # HTML templates
│   └── core/               # Router, base controller, DB connection
├── public/                 # Entry point (index.php), assets
│   ├── index.php
│   ├── css/
│   └── js/
├── config/                 # Database and app configuration
├── docker-compose.yml
└── README.md
```

---

## Getting Started

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/muddathiroshey/SE-Project.git
cd SE-Project

# 2. Start the Docker containers
docker-compose up -d

# 3. Import the database schema
#    Access phpMyAdmin at http://localhost:8080
#    and import the schema from /config/schema.sql

# 4. Open the app
#    http://localhost:8000
```

### Configuration

Copy and edit the config file to set your database credentials:

```bash
cp config/config.example.php config/config.php
```

---

## Database Schema

The database consists of **8 core tables**:

| Table | Description |
|---|---|
| `users` | All registered users (clients & freelancers) |
| `profiles` | Extended user profile data |
| `jobs` | Job listings posted by clients |
| `bids` | Freelancer proposals on jobs |
| `categories` | Job categories and tags |
| `messages` | In-platform messaging |
| `reviews` | Post-completion ratings and feedback |
| `sessions` | User session management |

---

## Design Patterns

The codebase applies **42 design functions** across six sections (A–F), including:

- **MVC (Model-View-Controller)** — Core architectural pattern separating concerns
- **Front Controller** — Single `index.php` entry point routing all requests
- **Repository Pattern** — Models abstract all DB queries from controllers
- **Singleton** — Database connection class
- **Strategy Pattern** — Role-based rendering logic in dashboard views
- **Template Method** — Base controller defining shared lifecycle hooks

---

## UML & Documentation

Full software engineering documentation was produced alongside the implementation:

- Use Case Diagram
- Class Diagram
- Object Diagram
- Activity Diagram
- Sequence Diagram
- Functional & Non-Functional Requirements
- 30+ table MySQL schema design document

---

## Team

Developed by the CS251 Software Engineering team at [University Name].

| Name | Role |
|---|---|
| Muddathir Oshey | Lead Developer |
| | |
| | |

---

> **Course:** CS251 — Software Engineering  
> **Academic Year:** 2025–2026
