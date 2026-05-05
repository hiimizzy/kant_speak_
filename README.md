# KantSpeak

KantSpeak is an open-source experimental framework for research in adaptive learning systems and human-computer interaction. It provides an infrastructure for designing, executing, and analyzing reproducible experiments on adaptive task selection in multimodal learning environments.

The system is not intended as an educational application, but as a research tool for controlled experimentation.

---

## Scientific Scope

KantSpeak supports research in:

- adaptive learning systems  
- human-computer interaction (HCI)  
- educational data mining  
- reinforcement learning in education  
- assistive technologies for neurodivergent users  

---

## System Architecture

KantSpeak is composed of five core modules:

### 1. Logger

Records all interaction events with structured metadata:

- timestamp  
- event type  
- user response  
- correctness  
- response time  

All logs are stored in JSON format to ensure reproducibility.

---

### 2. ExperimentManager

Controls experimental execution:

- assignment of participants to groups (e.g., control vs adaptive)  
- definition of experimental conditions  
- session management  
- aggregation of metrics  

---

### 3. AdaptiveEngine

Implements adaptive decision-making strategies:

- Contextual Multi-Armed Bandits (Thompson Sampling)  
- rule-based fuzzy adaptation models  

The engine selects the next task based on observed performance metrics such as accuracy and response time.

---

### 4. Instrument API

HTTP endpoints for integration between frontend and experimental logic:

- `/instrument.php` → receives and stores interaction logs  
- `/adapt.php` → returns next adaptive action  

This separation ensures modularity between UI and research logic.

---

### 5. Researcher Dashboard

A web-based interface for data analysis and visualization:

- session inspection  
- aggregated metrics per experiment  
- export of logs  
- comparison between experimental groups  

---

## Installation

### Requirements

- Web server (XAMPP)  
- PHP 7.4+  
- Modern browser  
- Python 3.8+ (for offline analysis)

---

### Quick Start
1. Open the application in a browser 
2. Select an interaction module (e.g., Memory Game, Build Word)
3. Perform tasks normally
4. The system automatically logs interactions and adapts difficulty

### Setup

Clone the repository:

```bash
git clone https://github.com/your-repo/kant_speak_
cd kant_speak_
```

### Research Dashboard Access
The Researcher Dashboard is available at: 
`researcher/index.html`




