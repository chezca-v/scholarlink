# 🎓 ScholarLink
### Scholarship Application & Screening Management System  
**Course:** Software Design  
**Target Output:** Web-Based Application 

---

## 📌 Overview

ScholarLink is a centralized scholarship management platform designed to modernize scholarship administration in the Philippines.  

The system enables students to:

- Browse scholarships without logging in
- Create a professional academic profile
- Apply and track applications
- Receive real-time updates via In-App, Email, and SMS

Organizations can:

- Manage scholarships
- Evaluate applicants
- Apply dynamic weighted scoring
- Ensure blind screening for fairness

ScholarLink integrates both **web-based software architecture** and **embedded hardware systems (ESP32 + GSM)** to ensure accessibility, transparency, and inclusivity.

---

## 🎯 Key Features

### 🔎 Public Scholarship Browsing
- Filter by course, GPA, income bracket, location
- Deadline countdown timer
- “Coming Soon” indicator
- Philippine scholarships only

---

### 👤 Scholar Profile (Student Wallet)
A LinkedIn-style academic profile including:

- GPA / Academic Records
- Financial Documents (ITR)
- Valid ID
- Certificates
- Portfolio uploads

Reusable verified documents across multiple applications.

---

### 📊 Intelligent Scholarship Matching
- Match percentage display
- Eligibility validation
- Recommendation explanation
- Prevents ineligible applications

---

### 🧮 Dynamic Weighted Scoring Engine
Customizable scoring:

- Merit-focused
- Needs-focused
- Configurable grade vs income weights

---

### 👁 Blind Screening
During evaluation, system hides:
- Name
- Address
- Gender
- School (optional)

Ensures fairness and bias reduction.

---

### 📈 Application Status Tracking
Stages:
- Submitted
- Document Review
- Under Evaluation
- Interview Scheduled
- Final Decision

Color-coded visual indicators.

---

### 📅 Deadline Management System
Automated reminders:
- 14 days – Email
- 7 days – Email + In-app
- 3 days – Email + SMS
- 1 day – Email + SMS
- 6 hours – SMS only

Includes:
- Deadline extensions
- Google Calendar integration

---

### 📩 Embedded SMS Notification Gateway
Hardware Integration:
- ESP32
- GSM Module (SIM800L)

Workflow:
1. Admin updates application status.
2. Laravel backend triggers API request.
3. ESP32 receives JSON command.
4. GSM sends SMS to applicant.

Ensures accessibility for students with limited internet.

---

### 🤖 AI Help Desk Chatbot
- 24/7 FAQ Support
- Scholarship suggestion assistance
- Step-by-step guidance

---

## 🏗 System Architecture

### Backend
- PHP (Laravel Framework)

### Frontend
- HTML5
- Tailwind CSS
- JavaScript

### Database
- MySQL

### Authentication
- Email & Password
- Google OAuth
- Facebook OAuth
- Microsoft OAuth

### Notifications
- PHP Mailer (Email)
- ESP32 + GSM (SMS)

### Embedded System
- ESP32 Development Board
- SIM800L GSM Module
- HTTP REST API (JSON Communication)

---

## 👥 User Roles

### 1️⃣ Superadmin
- System-wide management
- Organization oversight
- Analytics dashboard

### 2️⃣ Admin
- Manages assigned scholarships
- Adjusts deadlines
- Reviews metrics

### 3️⃣ Evaluator
- Reviews applications
- Verifies documents
- Applies weighted scoring

### 4️⃣ Applicant
- Creates profile
- Applies to scholarships
- Tracks application status
- Receives notifications

---

## 🗄 Database Design Highlights

Implements:
- Multi-Tenancy Architecture
- Role-Based Access Control (RBAC)
- Logical isolation of organizations
- Conflict detection & fraud prevention

