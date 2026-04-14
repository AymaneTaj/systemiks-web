# Website improvements — full site & show our work

> Styled, animated improvements to make the site complete and showcase work.

---

## ✅ Done

- [x] **Work comparison section** — 9 before/after items on all main pages (placeholder images)
- [x] **Service pages** — Web design, Branding, SEO, PPC (Advertising)
- [x] **Menu** — Admin removed; Services (SEO, PPC) and display improved
- [x] **Scroll-reveal animations** — Sections and work items animate in on scroll
- [x] **Featured work strip** — Homepage highlight (animated cards)
- [x] **Portfolio (Work) page** — Filterable project grid
- [x] **About page** — Story, approach, CTA

---

## 📋 Optional next steps

- [ ] **Replace placeholders** — Swap picsum.photos with real project images in `includes/work-comparison.php` and featured work
- [ ] **Case study pages** — Dedicated page per project (e.g. `/work/project-slug.php`) with full before/after and results
- [ ] **Testimonials section** — Homepage or About: short quotes + names (optional carousel)
- [ ] **Footer** — Add sitemap links (Services, Work, About, Contact), social, copyright
- [ ] **Blog or News** — If you want content marketing (optional)

---

## 🎨 Animation & style summary

- **Reveal on scroll:** Sections with `.reveal-on-scroll` fade and slide up when in view.
- **Work comparison:** Each card gets staggered reveal (delay by index).
- **Featured work:** Cards animate in with hover scale and optional link to Work page.
- **Headings:** Existing `.split-heading` line-reveal kept; new sections use reveal class where needed.
