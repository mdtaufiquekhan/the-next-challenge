# 🚀 The Next Challenge – AI-Powered Onboarding for Innovation

**Live Demo:** [https://thenextchallenge.click](https://thenextchallenge.click)  
**GitHub Repo:** [github.com/mdtaufiquekhan/the-next-challenge](https://github.com/mdtaufiquekhan/the-next-challenge)  
**Video Demo:** [Watch on YouTube](https://youtu.be/quOyeqU8yac)

---

## 📖 Overview

**The Next Challenge** is a Laravel-based web platform that helps users create and launch innovation challenges through an intelligent, step-by-step wizard. A built-in AI assistant guides users in defining objectives, setting configurations, and preparing launch-ready challenges — ideal for hackathons, open innovation calls, educators, or competition hosts.

---

## ✅ Key Features

- 🧠 AI-powered onboarding assistant (ChatGPT/OpenAI compatible)
- 🧾 Challenge wizard with 12 structured steps
- 📁 Submission format selection (e.g., GitHub, video, PDF)
- 🏆 Prize setup with tier and milestone logic
- 🗓️ Timeline configuration and announcements
- 📊 Evaluation models and rubrics
- 🖼️ AI thumbnail generation (planned)
- ⚡ Lightweight frontend with vanilla JavaScript

---

## 🧩 Configuration Flow

- **Intake** – Define problem, goals, and challenge type  
- **Audience** – Set language, region, team rules, and Q&A  
- **Submissions** – Select format and required documentation  
- **Prizes** – Configure single, tiered, or milestone-based rewards  
- **Timeline** – Launch, deadline, judging, announcement dates  
- **Evaluation** – Reviewer roles, scoring logic, rubric  
- **Monitoring** – Add announcements, resources, and updates

> ⚠️ **Note:** Please wait 3–4 seconds between steps during the demo.  
> Some JS features may load with a slight delay in this non-production build.

---

## 🤖 AI Assistant

The AI agent acts as a virtual mentor during challenge setup:

- Suggests titles, goals, and submission formats
- Refines inputs based on context
- Uses Quill.js for rich editing with prompt integration
- LLM-agnostic design — currently uses OpenAI API

---

## 🛠 Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade + Bootstrap 5 + SCSS
- **Editor:** Quill.js with prompt injection
- **JS:** Vanilla JavaScript for wizard flow
- **Build Tool:** Vite
- **Database:** SQLite (default), MySQL compatible

---

## 📦 Local Setup Instructions

### Requirements

- PHP 8.2+
- Composer
- Node.js & npm

### Installation

```bash
git clone https://github.com/mdtaufiquekhan/the-next-challenge.git
cd the-next-challenge
composer install
npm install
cp .env.example .env
php artisan key:generate

Set OpenAI API Key
In your .env file, add:

OPENAI_API_KEY=your-openai-api-key-here
💬 If you need an API key to test locally, contact me directly.

Configure Database
For SQLite:

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
Or configure MySQL as needed.

Run the Project

npm run build
php artisan migrate
php artisan serve
```

## 👨‍💻 About the Developer

**Taufique Khan**  
Founder of [lookmyweb.com](https://lookmyweb.com)  
Full-stack Web Developer  
UI/UX Designer  
SEO Strategist  
GitHub: [@mdtaufiquekhan](https://github.com/mdtaufiquekhan)

I built this project solo — from UX design to backend development — to showcase how Agentic AI can empower users to build structured, impactful challenges without friction.

---

## 📄 License

**MIT License**  
© 2025 Taufique Khan
