# CSE Unit - Volunteer Management System (Backend API)

A robust, RESTful API built with **Laravel 11** and **Laravel Sanctum** to manage volunteer operations, activity schedules, and weekly session assignments for the CSE Unit.

## 🚀 Features

- **Authentication & Security:** Secure token-based authentication using Laravel Sanctum.
- **Role-Based Access Control (RBAC):** Distinct permission boundaries for **Admins**, **Session Supervisors**, and **Volunteers**.
- **Activity & Session Scheduling:** Create activities and map them to multi-slot weekly sessions (`day_of_week`, `time_slot`).
- **Dynamic Session Supervision:** Assign or unassign designated supervisors for individual weekly sessions without altering global user roles.
- **Roster & Attendance Management:** Assign volunteers to specific activity sessions with real-time status updates (`assigned`, `completed`, etc.).
- **Volunteer Hour Tracking:** Strict Admin-only management for logging and accumulating total volunteer hours (`volunteer_profiles`).

## 🛠️ Tech Stack & Architecture

- **Framework:** Laravel 11
- **Database:** MySQL / PostgreSQL
- **Authentication:** Laravel Sanctum
- **Architecture:** Controller-Service pattern with Eloquent ORM relationships (`belongsToMany`, `hasMany`, `hasOne`).

## 📋 API Endpoints Summary

### Authentication
- `POST /api/login` - User authentication & token issuance
- `POST /api/logout` - Revoke current access token

### Activities & Sessions (Admin & Supervisors)
- `POST /api/activities` - Create a new activity with weekly session slots *(Admin)*
- `PATCH /api/sessions/{id}/supervisor` - Assign or unassign a supervisor for a session *(Admin)*
- `POST /api/sessions/{id}/assign-volunteers` - Assign volunteers to a session roster *(Admin / Supervisor)*

### Volunteer Portal & Hours
- `GET /api/volunteer/schedule` - Fetch assigned weekly sessions for the authenticated volunteer
- `PATCH /api/volunteers/{id}/hours` - Update or increment total volunteer hours *(Admin)*

## ⚙️ Local Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/your-username/your-repository-name.git](https://github.com/your-username/your-repository-name.git)
   cd your-repository-name