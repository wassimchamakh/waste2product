# 📘 Reusable Content Templates Guide

Purpose: Quick, copyable templates to create consistent content for Events, Tutorials, Projects, Posts, and Waste (Déchet) items.

Date: November 28, 2025

---

## ✅ How to Use
- Pick a template below, copy it, and paste into the form or file you’re creating.
- Replace placeholder fields like `<Title>`, `<City>`, `<YYYY-MM-DD>`.
- Keep sections short and clear; use bullet points where possible.

---

## 🛠 Event Template (Workshop / Training / Collection / Repair Café)

Title: `<Event Title>`
Type: `<workshop | training | collection | repair_cafe>`
Organizer: `<Organization or User>`
City: `<City>`
Location: `<Venue name or address>`
Date Start: `<YYYY-MM-DD HH:MM>`
Date End: `<YYYY-MM-DD HH:MM>`
Max Participants: `<number>`
Price: `<0 for free | amount>`
Tags: `workshop`, `recycling`, `community`, `education`

Short Description:
- What: `<One sentence summary>`
- Who: `<Target audience>`
- Why: `<Benefit or outcome>`

Program (Agenda):
- 09:00 – Welcome & Registration
- 09:30 – Introduction to `<topic>`
- 10:15 – Hands-on activity: `<activity>`
- 12:00 – Break
- 13:00 – Advanced techniques: `<technique>`
- 14:30 – Q&A + Feedback

Requirements:
- Bring: `<items to bring>`
- Skills: `<beginner | intermediate | advanced>`
- Materials provided: `<yes/no>`

Outcomes:
- Learn: `<key learning points>`
- Do: `<practical outcomes>`
- Receive: `<certificate | materials | resources>`

Contact:
- Email: `<email>`
- Phone: `<phone>`

---

## 📚 Tutorial Template (Step-by-step Guide)

Title: `<Tutorial Title>`
Level: `<beginner | intermediate | advanced>`
Estimated Time: `<minutes/hours>`
Prerequisites: `<tools, skills, accounts>`

Overview:
- Goal: `<what the user will achieve>`
- Outcome: `<final result or product>`

Steps:
1. Prepare: `<setup or installation>`
2. Configure: `<settings or options>`
3. Execute: `<commands or actions>`
4. Validate: `<tests or checks>`
5. Share: `<export or publish>`

Tips:
- `<tip 1>`
- `<tip 2>`

Resources:
- Docs: `<link>`
- Repo: `<link>`
- Video: `<link>`

---

## 🚀 Project Template (Initiative / Build / Hack)

Title: `<Project Name>`
Owner: `<Team/User>`
Status: `<idea | planning | in-progress | completed>`
Start Date: `<YYYY-MM-DD>`
Target Date: `<YYYY-MM-DD>`
Budget: `<amount or N/A>`

Problem Statement:
- `<one sentence on the problem>`

Objectives:
- `<objective 1>`
- `<objective 2>`
- `<objective 3>`

Scope:
- In: `<included features>`
- Out: `<excluded features>`

Plan:
- Phase 1: Research (tasks: `<task 1, task 2>`)
- Phase 2: Build (tasks: `<task 1, task 2>`)
- Phase 3: Test & Launch (tasks: `<task 1, task 2>`)

KPIs:
- Impact: `<metric>`
- Adoption: `<metric>`
- Quality: `<metric>`

Risks & Mitigations:
- Risk: `<risk>` → Mitigation: `<plan>`

---

## 📝 Post Template (News / Update / Blog)

Title: `<Post Title>`
Author: `<Name>`
Date: `<YYYY-MM-DD>`
Category: `<announcement | tutorial | event | update>`

Summary:
- `<2–3 line intro>`

Body:
- Context: `<background>`
- What’s new: `<update>`
- Why it matters: `<value>`
- Call to action: `<join | register | try | share>`

Media:
- Image: `<path or URL>`
- Video: `<link>`

Links:
- Register: `<link>`
- Docs: `<link>`

---

## ♻️ Déchet (Waste Item) Template (Catalog / Post)

Name: `<Waste Type>`
Category: `<plastic | metal | glass | paper | organic | e-waste>`
Condition: `<new | used | damaged | recyclable>`
Quantity: `<units or kg>`
Location: `<City/Area>`
Available From: `<YYYY-MM-DD>`
Contact: `<email/phone>`

Description:
- Source: `<household | industrial | commercial | municipal>`
- Composition: `<materials>`
- Potential Uses: `<upcycling ideas or recycling methods>`
- Handling: `<safe handling instructions>`

Specifications:
- Dimensions: `<size if applicable>`
- Weight: `<kg>`
- Contamination: `<none | low | medium | high>`

Logistics:
- Pickup: `<allowed | by appointment>`
- Delivery: `<available | not available>`

Compliance:
- Regulations: `<local rules or notes>`

---

## 🔧 Quick JSON Payloads (API/Seeds)

Event (POST):
```json
{
  "title": "Recycling 101 Workshop",
  "type": "workshop",
  "city": "Tunis",
  "location": "Community Center",
  "date_start": "2025-12-05 09:00",
  "date_end": "2025-12-05 14:00",
  "max_participants": 40,
  "price": 0,
  "program": [
    {"time": "09:00", "title": "Welcome"},
    {"time": "09:30", "title": "Intro to Recycling"}
  ]
}
```

Tutorial:
```json
{
  "title": "How to Sort Waste at Home",
  "level": "beginner",
  "duration_minutes": 30,
  "prerequisites": ["Trash bags", "Labels"],
  "steps": [
    "Gather household waste",
    "Separate by category",
    "Label each bin",
    "Set pickup schedule"
  ]
}
```

Project:
```json
{
  "title": "Neighborhood Plastic Collection",
  "status": "planning",
  "start_date": "2025-12-01",
  "target_date": "2026-01-30",
  "objectives": [
    "Collect 500kg of plastic",
    "Engage 200 residents"
  ]
}
```

Post:
```json
{
  "title": "New Repair Café Opening",
  "author": "Team Waste2Product",
  "category": "announcement",
  "summary": "We’re launching a community repair café in Ariana.",
  "body": "Join us to repair, learn, and reduce waste.",
  "links": {"register": "https://example.com/register"}
}
```

Déchet:
```json
{
  "name": "PET Bottles",
  "category": "plastic",
  "condition": "used",
  "quantity_kg": 120,
  "location": "Sfax",
  "description": "Clean PET bottles from a beverage distributor.",
  "potential_uses": ["Upcycling into yarn", "Recycling into new bottles"],
  "logistics": {"pickup": "by appointment", "delivery": "available"}
}
```

---

## 🧩 Ready-to-Copy Snippets (Blade)

Event Card (Blade):
```blade
<div class="p-4 border rounded-xl">
  <h3 class="text-lg font-semibold">{{ $event->title }}</h3>
  <p class="text-sm text-gray-600">{{ ucfirst($event->type) }} • {{ $event->location }}</p>
  <p class="text-sm">Du {{ $event->date_start->format('d/m H:i') }} au {{ $event->date_end->format('d/m H:i') }}</p>
  <a href="{{ route('Events.show', $event->id) }}" class="mt-2 inline-block bg-indigo-600 text-white px-3 py-1 rounded">Voir</a>
</div>
```

Post Card (Blade):
```blade
<article class="p-4 border rounded-xl">
  <h3 class="text-xl font-bold">{{ $post->title }}</h3>
  <p class="text-gray-600 text-sm">Par {{ $post->author }} • {{ $post->created_at->diffForHumans() }}</p>
  <p class="mt-2">{{ Str::limit($post->summary, 140) }}</p>
  <a class="text-indigo-600" href="{{ route('posts.show', $post) }}">Lire plus →</a>
</article>
```

---

## 🧭 Naming & Tagging Cheatsheet

- Events: `workshop`, `training`, `collection`, `repair_cafe`, `webinar`
- Topics: `recycling`, `upcycling`, `community`, `education`, `sustainability`
- Difficulty: `beginner`, `intermediate`, `advanced`
- Cities: `Tunis`, `Ariana`, `Sfax`, `Sousse`, `Bizerte`, `Gabes`

---

## 📥 Suggestions
- Create a new item using these templates via the backoffice forms.
- For seeds or API, adapt the JSON payloads.
- Keep titles short (max 60 chars) and descriptive.
- Add 3–5 tags to improve search and filtering.

---

Need more templates (e.g., ticketing, emails, certificates)? Ask and I’ll add them.
