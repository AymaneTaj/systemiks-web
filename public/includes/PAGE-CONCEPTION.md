# Page conception – layout & tone (Ofseek-inspired)

Reference: [Ofseek](https://www.ofseek.com/). Same tone across all pages: **selective, strategic, partnership**. Client feels **unique** working with us—we don't work with everyone; we work with brands ready to own their market.

---

## Tone of voice (all pages)

- **Selective:** We choose who we work with. You're not just another client.
- **Strategic:** Everything ties to your goals and market position.
- **Partnership:** Long-term, senior attention, no quick wins only.
- **Results:** Revenue impact, market share, measurable outcomes—not vanity metrics.

---

## 1. About

**Visible:** Breadcrumb → Hero (strong headline + lead) → **Value cards** (3: Selective partnership, Performance obsession, Long-term partnership) → Our story (block) → How we work (block) → Why work with us (list) → **Process strip** (4 steps: Get in touch → Review → Discovery call → Partnership) → CTA bar → Footer.

**Components:** Value proposition cards (styled, with icon or number), entrance animations, process steps strip.

---

## 2. Services

**Visible:** Breadcrumb → Hero → Intro line → **Service cards grid** (4 cards: Web, Branding, SEO, Advertising; each card: title, short copy, link) → Optional tabs or accordion for detail → Work comparison (before/after) → CTA bar → Footer.

**Components:** Styled service cards (hover, border, shadow), optional accordion per service.

---

## 3. Work

**Visible:** Breadcrumb → Hero → Optional **stats strip** (e.g. projects delivered, growth) → Filter tabs → **Glass work cards grid** (React) → CTA bar → Footer.

**Components:** Filter tabs, glass cards (existing), entrance animations.

---

## 4. Contact

**Visible:** Breadcrumb → Hero → Optional **process steps** (Book → We review → Discovery call → Start) → Calendar (React) → Form + direct contact → CTA bar → Footer.

**Components:** Process strip, calendar, form, trust line (“We reply within 24 hours”).

---

## 5–8. Service sub-pages (Web, Branding, SEO, Advertising)

**Visible:** Breadcrumb (Home → Services → [Service]) → **Page hero** → “What we deliver” (copy) → **Accordion** (3–4 items) → Optional **styled cards** (e.g. what’s included) → Work comparison → CTA bar → Footer.

**Components:** Accordion (existing), optional value cards, entrance animations.

---

## 9. Home (last) — DONE

Kept existing structure; refined tagline to selective/strategic tone; added CTA bar before Connect section; credibility strip, services tabs, featured on, who we serve, featured work, work comparison, Connect section. Entrance animations via .reveal-on-scroll + main.js IntersectionObserver.

---

## Shared UI

- **Value cards:** Rounded, border, subtle bg, hover shadow, optional icon.
- **Process strip:** Numbered steps (01, 02, 03, 04), short title + one line.
- **Accordions:** Already in use; ensure consistent style.
- **CTAs:** Primary button “Let’s talk” / “Book a call”; secondary “View work” / “See services”.
- **Entrance:** `reveal-on-scroll` + optional stagger delays.
