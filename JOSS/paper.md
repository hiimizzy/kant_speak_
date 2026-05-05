---
title: "KantSpeak: An Open-Source Experimental Framework for Adaptive Learning Research Using Contextual Bandits and Multimodal Interaction"

tags:
  - adaptive learning
  - human-computer interaction
  - contextual bandits
  - educational data mining
  - reproducible research

authors:
  - name: Isabela Araujo Costa
    affiliation: 1
    orcid: 0000-0002-9981-6343
  - name: Samya Rodrigues da Costa
    affiliation: 1
  - name: Carlos Henrique Guedes de Sousa
    affiliation: 1
  - name: Pedro Henrique Alves Ribeiro
    affiliation: 1

affiliations:
  - name: Universidade do Estado do Pará, Redenção, Brazil
    index: 1

date: 2026
bibliography: paper.bib
---

# Summary

KantSpeak is an open-source experimental framework designed for research in adaptive learning systems and human-computer interaction (HCI). The framework enables reproducible experiments on adaptive task selection using behavioral metrics such as accuracy and response time in multimodal learning environments.

Unlike educational applications, KantSpeak is designed as a research infrastructure that integrates experimental control, adaptive decision-making, structured logging, and offline statistical analysis.

---

# Statement of Need

Research in adaptive learning systems requires reproducible and configurable environments for evaluating how users respond to different adaptation strategies.

Existing educational tools typically implement proprietary or non-transparent adaptation mechanisms, limiting reproducibility and experimental control. Additionally, many systems lack structured data pipelines suitable for scientific analysis.

KantSpeak addresses these limitations by providing a modular framework that supports:

- controlled experimental design  
- configurable adaptive policies  
- structured behavioral data logging  
- reproducible offline analysis  

This enables systematic evaluation of adaptive learning strategies under controlled conditions.

---

# Research Impact Statement

KantSpeak contributes to research in adaptive learning systems by providing a full experimental pipeline that integrates interaction, adaptation, and analysis. The framework supports evaluation of contextual bandit approaches and rule-based adaptation strategies in multimodal learning tasks.

By decoupling interface components from experimental logic, KantSpeak enables reproducible experimentation and comparative evaluation of adaptive algorithms across different conditions.

---

# System Architecture

KantSpeak is composed of five core components:

## 1. Logger

The Logger records all user interactions during experimental sessions, including:

- timestamps  
- event types  
- user responses  
- correctness  
- response time  

All data is stored in structured JSON format to ensure reproducibility.

---

## 2. ExperimentManager

The ExperimentManager controls experimental execution by:

- assigning participants to experimental groups  
- defining experimental conditions  
- managing session flow  
- aggregating performance metrics  

---

## 3. AdaptiveEngine

The AdaptiveEngine implements adaptive decision-making strategies for task selection.

Supported approaches include:

- Contextual Multi-Armed Bandits (e.g., Thompson Sampling)  
- fuzzy rule-based adaptation models  

The engine selects the next task based on observed user performance metrics such as accuracy and response time.

---

## 4. Instrument API

The system exposes HTTP endpoints that connect the frontend with the experimental backend:

- `/instrument.php` — receives and stores interaction logs  
- `/adapt.php` — returns the next selected task based on the adaptive policy  

This separation ensures modularity between user interface and experimental logic.

---

## 5. Researcher Dashboard

The Researcher Dashboard provides a web-based interface for data exploration and analysis, including:

- session-level inspection  
- aggregated metrics per experimental group  
- export of structured logs  
- comparison between conditions  

Access is intended for research and analysis purposes.

---

# Adaptive Mechanism

The adaptive system computes a performance score based on:

- accuracy of responses  
- response time  

This score is used by the AdaptiveEngine to update task selection policies using either:

- probabilistic bandit-based methods, or  
- fuzzy rule-based evaluation strategies  

All parameters are configurable to support reproducible experimental designs.

---

# Data Collection and Reproducibility

KantSpeak ensures reproducibility through:

- structured JSON logging of all interactions  
- configurable experimental parameters  
- separation between UI, experimental logic, and analysis layers  
- exportable datasets for offline evaluation  

These design choices enable full reconstruction of experimental sessions.

---

# Offline Analysis

The framework includes Python-based tools for:

- descriptive statistics  
- hypothesis testing (t-test, ANOVA)  
- learning curve visualization  
- comparison between experimental groups  

---

# Implementation

KantSpeak is implemented using standard web technologies:

- HTML, CSS, JavaScript (frontend)  
- PHP (API layer)  
- Python (analysis pipeline)  

No proprietary dependencies are required, ensuring accessibility and reproducibility.

---

# Limitations

KantSpeak is a research framework and does not provide clinical validation or therapeutic functionality. It is intended exclusively for experimental and analytical use in academic contexts.

---

# License

MIT License

---

# Citation

If you use KantSpeak in academic work, please cite:

> KantSpeak: An Open-Source Experimental Framework for Adaptive Learning Research (2026)

---

# Notes for Reviewers

KantSpeak is explicitly designed as a **reproducible experimental research framework**, not as an educational application. Its primary contribution is enabling controlled studies of adaptive learning strategies through modular architecture, structured logging, and offline analysis tools.
