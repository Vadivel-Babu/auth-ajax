# GUVI Internship - Authentication App

A full-stack authentication system built for the GUVI internship task.

## Features

- User registration (name, email, password) with validation
- Login with email/password
- Profile page showing user details
- Secure session management using:
  - **Frontend**: Browser `localStorage` for token
  - **Backend**: Redis for session storage
- User profile data stored/updated in MongoDB on login
- Responsive Bootstrap forms
- jQuery AJAX for all interactions (no native form submission)
- MySQL (Railway) for core user data
- MongoDB Atlas for profile details
- Redis (Upstash) for session tokens

## Tech Stack

- **Frontend**: HTML, CSS (Bootstrap 5), JavaScript, jQuery
- **Backend**: PHP (vanilla/procedural)
- **Database**:
  - MySQL (core users table) → Railway.app
  - MongoDB (user profiles on login) → MongoDB Atlas (free tier)
- **Session**: Redis → Upstash (free tier)
- **Password hashing**: PHP `password_hash()` + `password_verify()`
- **Dependency management**: Composer (Predis for Redis)

## Credential
