# KantSpeak: An Experimental Framework for Adaptive Learning Research

[![License: BSD-3](https://img.shields.io/badge/License-BSD--3-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.x-blue)](https://php.net)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow)](https://developer.mozilla.org/)
[![MediaPipe](https://img.shields.io/badge/MediaPipe-Hands-red)](https://developers.google.com/mediapipe)

**KantSpeak** is an open‑source experimental framework designed for research in adaptive learning systems, human‑computer interaction, and multimodal educational environments. It enables controlled experiments where learning tasks are dynamically adapted based on user performance (accuracy, response time). The system integrates:

- 📊 **Structured logging** of all interactions  
- 🧪 **Experiment management** (A/B testing, multi‑arm bandits)  
- 🤖 **Adaptive engine** (contextual bandits / fuzzy logic)  
- 📈 **Offline analysis tools** (Python scripts)  

Unlike conventional educational software, KantSpeak is built as a **research instrument** – fully transparent, reproducible, and extensible.

---

## 🧩 System Overview

KantSpeak consists of five core components:

| Component | Description |
|-----------|-------------|
| **Logger** | Records every user action (clicks, answers, voice input, gestures) with timestamps and metadata. |
| **ExperimentManager** | Assigns participants to experimental groups and controls conditions (JSON configurable). |
| **AdaptiveEngine** | Implements Thompson Sampling (contextual bandits) and fuzzy rule‑based adaptation. |
| **Instrument API** | HTTP endpoints `/instrument.php` (logging) and `/adapt.php` (next action recommendation). |
| **Offline Analysis** | Python scripts for descriptive stats, hypothesis testing, and learning curve visualization. |

The front‑end uses vanilla JavaScript, Tailwind CSS, and integrates:
- **MediaPipe Hands** – real‑time finger tracking for drawing letters in the air.
- **Web Speech API** – speech synthesis (English) and recognition for pronunciation activities.

Back‑end is plain PHP 8.x with a modular, object‑oriented architecture. All data is stored in structured JSON files, easily exportable for further analysis.

---

## 🎯 Use Cases

- **Academic research**: Evaluate different adaptation policies (bandit vs. rule‑based) on learning outcomes.
- **Special education**: Study how children with autism spectrum disorder (levels 1‑2) respond to multimodal, adaptive tasks.
- **HCI studies**: Measure the impact of gesture‑based input vs. mouse/touch on engagement and error rates.
- **Benchmarking**: Compare different reinforcement learning algorithms in a realistic educational environment.

---

## 📦 Activities Included

The framework currently provides 8+ fully instrumented learning activities:

| Activity | Modality | Adaptive Features |
|----------|----------|-------------------|
| Alphabet | Gesture (finger) + speech | Next letter selection, difficulty (size, speed) |
| Listen | Audio comprehension | Item difficulty (word length, noise) |
| Speak | Voice recognition | Prompt complexity, repetition threshold |
| Write | Typing | Word length, hint availability |
| Sorting Game | Drag & drop | Number of categories, item count |
| Build a Word | Drag & drop (syllables) | Syllable length, word complexity |
| Math Builder | Drag & drop + input | Number range, operator type (+/-) |
| I Spy | Object detection (camera) | Category difficulty, time per task |

All activities send interaction events to the logger and can be easily extended or modified.

---

## 🚀 Quick Start (Local Installation)

### Requirements
- PHP 8.0+ (with `session` extension)
- Web server (Apache / Nginx) or PHP built‑in server
- Modern browser (Chrome, Edge, Firefox) – camera and microphone required for some activities

### Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/yourlab/kant_speak_
   cd kant_speak_
2. Start a PHP server
   php -S localhost:8000
