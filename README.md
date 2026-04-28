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
- Receive real-time updates via In-App and Email

Organizations can:

- Manage scholarships
- Evaluate applicants
- Apply dynamic weighted scoring
- Ensure blind screening for fairness

ScholarLink integrates web-based software architecture to ensure accessibility, transparency, and inclusivity.

---

## 🎯 Key Features

### 🔎 Public Scholarship Browsing
- Filter by course, GPA, income bracket, location
- Deadline countdown timer
- "Coming Soon" indicator
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
- 3 days – Email + In-app
- 1 day – Email + In-app

---

## 📋 System Requirements
- PHP 8.2+
- Laravel 12
- MySQL
- Node.js & NPM

## 🚀 Installation
1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Copy `.env.example` to `.env` and configure
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `npm run build`
8. Run `php artisan serve`

## 📚 Usage
- Register as an applicant or admin
- Admins can create scholarships
- Applicants can browse, apply, and track
- Evaluators review applications

## 🤝 Contributing
- Follow Laravel conventions
- Add tests for new features
- Ensure code quality

## 📄 License
MIT License
