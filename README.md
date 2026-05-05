# KantSpeak

KantSpeak is an open-source, web-based platform designed to support English language learning through interactive and multimodal activities. The system is structured as a modular and extensible environment, enabling the development and evaluation of adaptive learning strategies, particularly in contexts involving neurodivergent users.

---

## Overview

KantSpeak provides a structured interaction environment that integrates visual, auditory, and gesture-based inputs to support language acquisition. The platform is designed to reduce cognitive load through predictable interaction patterns and simplified interface design.

Beyond its educational use, KantSpeak can be used as a prototyping environment for:

* adaptive learning interfaces
* multimodal interaction strategies
* assistive educational technologies

---

## Key Features

The system is organized into modular components, each targeting specific aspects of language acquisition:

* **Alphabet** – Letter recognition and phonetic awareness
* **Speak** – Guided oral practice with structured prompts
* **Write** – Text production and writing exercises
* **Listen** – Audio-based comprehension tasks
* **Memory Game** – Vocabulary reinforcement through association
* **Time Trial** – Timed recall tasks for cognitive engagement
* **Numbers** – Numerical vocabulary learning
* **I Spy** – Attention and listening-based interaction
* **Draw** – Visual-semantic association tasks
* **Build Word** – Word construction using syllabic components
* **Sorting Game** – Categorization and semantic grouping
* **Karaoke** – Pronunciation and rhythm training through music

---

## System Architecture

KantSpeak follows a modular architecture composed of:

* **Interaction Layer**
  Handles user input and output, including gesture-based interaction via browser-supported visual input (Air Canvas).

* **Content Module**
  Organizes educational activities into structured units targeting multiple language skills.

* **Progression Structure**
  Supports flexible navigation and controlled sequencing of activities.

* **Session Context (optional extension)**
  Can be extended to track user interactions for analysis and adaptive behavior.

This architecture enables extensibility and facilitates integration with additional components such as machine learning models or analytics modules.

---

## Gesture-Based Interaction

KantSpeak integrates an Air Canvas component using web-based technologies, enabling gesture-based interaction directly in the browser.

This allows users to:

* interact through hand movements
* engage with activities without physical contact
* explore alternative interaction modalities

No additional installation is required beyond a compatible browser.

---

## Technologies

KantSpeak is implemented using:

* HTML, CSS, JavaScript
* PHP (backend services)
* Web-based computer vision integration (Air Canvas)

---

## Installation

### Requirements

* Modern web browser (Chrome, Firefox, Edge)
* Local server environment (e.g., XAMPP)

### Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/seu-usuario/kantspeak.git
   ```

2. Navigate to the project directory:

   ```bash
   cd kantspeak
   ```

3. Move the project to your server directory:

   ```
   htdocs/ (XAMPP)
   ```

4. Start the local server and open in browser:

   ```
   http://localhost/kantspeak
   ```

---

## Potential Use Cases

* Language learning support in inclusive education
* Prototyping adaptive learning systems
* Research in human-computer interaction (HCI)
* Assistive technology development

---

## Contribution

Contributions are welcome. The modular structure allows the addition of new activities, interaction methods, or adaptive components.

---

## License

Specify your license here (e.g., MIT, GPL-3.0).
