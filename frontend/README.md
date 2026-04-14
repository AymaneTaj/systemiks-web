# Systemiks Frontend (React Hero)

React + TypeScript + Tailwind app with **BackgroundGradientAnimation** as the main hero: animated gradient orbs (CSS + blend modes), mouse-follow pointer, and Systemiks branding. Uses `cn()` from `@/lib/utils` (clsx + tailwind-merge) for shadcn-style class merging.

## Why `src/components/ui`?

This folder follows the **shadcn/ui** convention: reusable UI pieces live under `components/ui`. That keeps primitives (hero, background-gradient-animation, buttons, cards) in one place and makes it easy to add more shadcn components later. If you run the [shadcn CLI](https://ui.shadcn.com/docs/cli) in this project, it will install into the same structure. **If your project does not use `src/components/ui`**, create it so all UI components live in one place and the CLI can add components without path mismatches.

## Requirements

- **Node.js** 18+
- **npm** (or pnpm/yarn)

## Setup

```bash
cd frontend
npm install
```

## Run (dev)

```bash
npm run dev
```

Opens at **http://localhost:5173** (Vite). The app renders the hero full-screen.

## Build

```bash
npm run build
```

Output is in `frontend/dist/`. To use this hero on your PHP site:

1. Build once: `npm run build`
2. Copy `dist/*` into your PHP public folder (e.g. `public/hero-app/`) and serve `index.html` at a route like `http://systemiks.local/hero-app/`, **or**
3. Keep the React app on a subdomain/port and link to it from the main site.

## Deploy (embed to PHP)

To build and copy **all** embed assets (embed.js + chunks + CSS) into `public/assets/` so the SEO hero, PPC hero, Connect section, etc. work on PHP pages:

```bash
cd frontend
npm run deploy
```

This runs `npm run build` then copies every file from `dist/assets/` to `public/assets/`. Always use this after changing any React code used by the PHP site, so chunk filenames and embed.js stay in sync.

## Tech stack

| Item | Purpose |
|------|--------|
| **Vite** | Build and dev server |
| **React 18** | UI |
| **TypeScript** | Typing |
| **Tailwind CSS** | Styles + animations (keyframes: moveVertical, moveInCircle, moveHorizontal) |
| **clsx + tailwind-merge** | `cn()` in `src/lib/utils.ts` for class names (shadcn-style) |
| **BackgroundGradientAnimation** | `src/components/ui/background-gradient-animation.tsx` – gradient orbs, optional mouse-follow |

## Brand colors (hero)

Defined in `src/components/ui/hero.tsx` and in `tailwind.config.js`:

- **Yellow:** `#f5d100`
- **Orange:** `#f0a050`
- **Pink:** `#e87a9e`
- **Blue:** `#0026ff`
- **Dark:** `#0a0a0a`

## Adding shadcn/ui later

To add more shadcn components and keep the same structure:

```bash
npx shadcn@latest init
npx shadcn@latest add button
```

Use the default path `src/components/ui` when prompted.
