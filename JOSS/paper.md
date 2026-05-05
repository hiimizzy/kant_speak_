---
title: "KantSpeak: An Experimental Framework for Adaptive Learning Research Using Multimodal Interaction and Contextual Bandits"

tags:
  - adaptive learning
  - human-computer interaction
  - contextual bandits
  - educational data mining
  - autism spectrum disorder

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

KantSpeak is an open-source experimental framework designed for research in adaptive learning systems, human-computer interaction, and multimodal educational environments. The system enables controlled experiments in which learning tasks are dynamically adapted based on user performance, including accuracy and response time.

Unlike conventional educational software, KantSpeak is designed as a research instrument. It integrates a logging system, an experimental management layer, an adaptive decision engine, and tools for offline statistical analysis, enabling reproducible studies on adaptive learning strategies.

---

# Statement of Need

Adaptive learning research requires reproducible experimental environments that allow systematic evaluation of how users respond to different pedagogical strategies and interaction modalities.

Existing educational applications often implement proprietary or non-transparent adaptation mechanisms, limiting reproducibility and comparative evaluation. Furthermore, many assistive learning tools for neurodivergent populations lack structured data collection pipelines for scientific analysis.

KantSpeak addresses these limitations by providing a modular and extensible experimental framework that supports:

- controlled experimental design  
- transparent adaptive decision-making  
- structured behavioral data collection  
- reproducible offline analysis  

This enables researchers to design, execute, and validate adaptive learning hypotheses in a controlled and extensible environment.

---

# Research Impact Statement

KantSpeak contributes to research in adaptive learning systems by providing a complete experimental pipeline for studying context-dependent adaptation strategies in educational tasks. The framework supports evaluation of bandit-based and rule-based adaptive policies in multimodal learning environments.

By decoupling interaction, adaptation, and analysis layers, KantSpeak enables reproducible experimentation and comparative evaluation of adaptive strategies, particularly in contexts involving heterogeneous cognitive profiles.

---

# Framework Architecture

KantSpeak is composed of five integrated components:

## 1. Logger

The Logger records all user interactions with precise timestamps, including:

- user responses  
- correctness of actions  
- response times  
- contextual metadata  

All data is stored in structured formats suitable for downstream analysis.

---

## 2. ExperimentManager

The ExperimentManager orchestrates experimental execution by:

- assigning participants to experimental groups  
- controlling experimental conditions  
- aggregating session-level metrics  
- ensuring consistency across trials  

---

## 3. AdaptiveEngine

The AdaptiveEngine implements decision policies for task selection. Two approaches are supported:

- **Contextual Bandits (Thompson Sampling)** for probabilistic action selection  
- **Fuzzy Rule-Based System** for interpretable adaptation strategies  

The engine dynamically selects the next activity based on observed user performance.

---

## 4. Instrument API

The system exposes HTTP endpoints for integration:

- `/instrument.php` — ingestion of interaction logs  
- `/adapt.php` — returns next adaptive action  

This enables decoupling between front-end interaction and experimental logic.

---

## 5. Offline Analysis Module

Python-based scripts provide:

- descriptive statistics  
- hypothesis testing (t-tests, ANOVA)  
- learning curve visualization  
- comparative analysis between experimental groups  

---

# Adaptive Model

The adaptive mechanism computes a performance score based on:

- accuracy of responses  
- response time  

This score is used to update the probability distribution over possible next activities (bandit approach) or to evaluate fuzzy rules for difficulty adjustment.

The system supports configurable weighting parameters, enabling experimental variation across studies.

---

# Data and Reproducibility

KantSpeak ensures reproducibility through:

- structured JSON logging of all interactions  
- configurable experimental parameters  
- separation of experimental logic and UI components  
- exportable datasets for offline analysis  

This allows complete reconstruction of experimental sessions.

---

# State of the Field

Existing adaptive learning systems typically rely on proprietary or non-reproducible algorithms. Educational applications such as Duolingo implement adaptive mechanisms but do not expose their decision models for research replication.

Assistive educational tools for neurodivergent populations often prioritize usability over experimental control, limiting their scientific applicability.

KantSpeak differs by explicitly exposing:

- adaptive decision policies  
- experimental configuration interfaces  
- reproducible data pipelines  

---

# Software Implementation

KantSpeak is implemented using standard web technologies:

- HTML, CSS, JavaScript (frontend)  
- PHP (API layer)  
- Python (offline analysis tools)  

The architecture is modular, allowing independent modification of experimental, interaction, and analysis layers.

---

# Figures

![KantSpeak modular interface showing experimental activities and adaptive system flow.](images/interface.png)

---

# References
