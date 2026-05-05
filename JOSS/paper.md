---
title: "KantSpeak: A Modular and Adaptive Platform for Language Learning in Children on the Autism Spectrum"

tags:
  - autism
  - adaptive systems
  - human-computer interaction
  - language learning
  - assistive technology

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

KantSpeak is an open-source, web-based platform designed to support English language learning for children on the autism spectrum (ASD), particularly those classified within support levels 1 and 2. The system provides a modular and extensible architecture that integrates multimodal interaction mechanisms, including visual prompts, auditory feedback, and gesture-based input.

In addition to its educational application, KantSpeak is designed as a reusable software framework for the development and evaluation of adaptive learning interfaces tailored to neurodivergent users. The platform emphasizes controlled interaction patterns, reduced cognitive load, and flexible activity composition, enabling experimentation with different instructional strategies and interaction modalities.

# Research Impact Statement

KantSpeak contributes to research in human-computer interaction, assistive technologies, and adaptive learning systems by providing a modular platform for the development and evaluation of multimodal interaction strategies. 

The system enables researchers to prototype and analyze alternative input mechanisms, such as gesture-based interaction, and to explore structured learning environments tailored to neurodivergent users. 

By supporting extensibility and reproducibility, KantSpeak can be used as a testbed for studying adaptive educational interfaces, user interaction patterns, and inclusive design approaches in language learning contexts.

# Statement of Need

Language learning in children on the autism spectrum involves specific cognitive and behavioral constraints, including variability in attention, sensory sensitivity, and differences in information processing. Existing digital language learning tools are generally optimized for neurotypical users and provide limited support for controlled interaction design and adaptive learning tailored to neurodivergent populations.

Although assistive technologies have been proposed to support communication and learning in ASD, there remains a lack of software platforms that simultaneously:

- provide structured and predictable interaction environments  
- support multimodal and alternative input mechanisms  
- enable extensibility for experimentation and reproducibility  

KantSpeak addresses this gap by offering a modular platform that allows researchers and developers to explore interaction strategies and adaptive learning approaches in the context of autism-focused language education.

# State of the Field

Digital platforms for language learning rely on gamification and repetition-based strategies to promote engagement and retention. While effective for large-scale use, these systems typically follow standardized interaction models and offer limited adaptability to specific cognitive profiles.

In parallel, assistive technologies for autism often focus on isolated functionalities, such as visual schedules or augmentative communication tools, rather than integrated environments for structured language acquisition.

KantSpeak situates itself at the intersection of these domains by combining:

- structured language learning activities  
- multimodal interaction design  
- support for alternative input mechanisms  

The platform is intended not only as an application but also as an experimental environment for studying adaptive interaction and learning processes in neurodivergent users.

# Software Design

KantSpeak is implemented as a web-based system with a modular architecture that separates interaction, content delivery, and user progression components. This design supports extensibility and facilitates integration with additional computational modules.

The system includes the following components:

- **Interaction Layer**: Provides multimodal input/output, including visual, auditory, and gesture-based interaction through a browser-supported Air Canvas mechanism.  
- **Content Module**: Organizes learning activities into structured units targeting vocabulary acquisition, phonetic recognition, and listening comprehension.  
- **Progression Mechanism**: Supports controlled sequencing of activities, enabling adaptive learning paths based on user interaction patterns.  
- **Session Tracking**: Records user interactions, allowing analysis and reproducibility of learning behavior.

This architecture enables the system to function both as an educational tool and as a platform for prototyping and evaluating adaptive learning strategies.

# Figures

![Main interface of KantSpeak showing available modules.](images/interface.png)

# AI Usage Disclosure

AI-based tools were used to generate visual assets for specific interface components (e.g., karaoke module). All generated content was reviewed and validated by the authors before integration into the system.

# References
