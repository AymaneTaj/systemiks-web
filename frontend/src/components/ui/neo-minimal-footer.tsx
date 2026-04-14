'use client'

import { Github, Linkedin, ArrowRight, Hexagon } from 'lucide-react'

const SITE_NAME = 'Systemiks'
const TAGLINE = 'Full service digital partner for brands seeking exponential growth. Web design, SEO, advertising & social media. Montreal & Laval.'
const EMAIL = 'info@systemiks.ca'
const PHONE_LINK = 'tel:+15147461644'

const SECTIONS = [
  {
    title: 'Product',
    links: [
      { label: 'Website design', href: '/web-design-development.php' },
      { label: 'Services', href: '/services.php' },
      { label: 'Contact', href: '/contact.php' },
    ],
  },
  {
    title: 'Company',
    links: [
      { label: 'About', href: '/#about' },
      { label: 'Contact', href: '/contact.php' },
      { label: 'Home', href: '/' },
    ],
  },
  {
    title: 'Connect',
    links: [
      { label: 'Email', href: `mailto:${EMAIL}` },
      { label: 'Phone', href: PHONE_LINK },
      { label: 'Contact', href: '/contact.php' },
    ],
  },
]

export function NeoMinimalFooter() {
  return (
    <div className="dark bg-[#0a0a0a]">
      <footer className="neo-minimal-footer max-w-7xl mx-auto bg-card/10 border-t rounded-t-lg border-border/20 flex flex-wrap pt-16 pb-8 relative overflow-hidden">
      {/* Background tech pattern */}
      <div
        className="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(circle_at_center,black,transparent_80%)]"
        aria-hidden
      />

      <div className="max-w-6xl mx-auto px-6 relative z-10 w-full">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8 mb-16">
          {/* Brand column */}
          <div className="col-span-1 md:col-span-5 flex flex-col gap-6">
            <div className="flex items-center gap-2">
              <Hexagon
                className="text-systemiks-yellow fill-systemiks-yellow/20 animate-pulse"
                size={24}
              />
              <h2 className="text-2xl font-bold tracking-tighter text-foreground">
                {SITE_NAME}
              </h2>
            </div>
            <p className="text-sm text-muted-foreground leading-relaxed max-w-sm">
              {TAGLINE}
            </p>
            <div className="flex items-center gap-2 mt-2 group">
              <div className="relative flex-1 max-w-xs">
                <a
                  href="/contact.php"
                  className="block w-full bg-white/5 border border-border/40 rounded-lg px-4 py-2.5 text-sm text-foreground hover:border-systemiks-yellow/50 transition-colors text-left"
                >
                  Book a discovery call →
                </a>
              </div>
              <a
                href="/contact.php"
                className="p-2.5 bg-systemiks-yellow rounded-lg text-foreground hover:opacity-90 transition-opacity"
                aria-label="Contact"
              >
                <ArrowRight size={18} />
              </a>
            </div>
          </div>

          {/* Link columns */}
          {SECTIONS.map((section) => (
            <div
              key={section.title}
              className="col-span-6 md:col-span-2 flex flex-col gap-4"
            >
              <h4 className="text-xs font-mono font-semibold text-foreground/70 uppercase tracking-widest">
                {section.title}
              </h4>
              <ul className="flex flex-col gap-3">
                {section.links.map((link) => (
                  <li key={link.label}>
                    <a
                      href={link.href}
                      className="text-sm font-mono text-muted-foreground hover:text-systemiks-yellow transition-colors flex items-center gap-2 group w-fit"
                    >
                      <span
                        className="w-2 h-2 rounded-full bg-muted group-hover:bg-systemiks-yellow transition-all group-hover:w-4 duration-200 shrink-0"
                        aria-hidden
                      />
                      {link.label}
                    </a>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* Bottom bar */}
        <div className="flex flex-col md:flex-row items-center justify-between gap-6 pt-8 border-t border-white/5">
          <p className="text-xs text-muted-foreground font-mono">
            © {new Date().getFullYear()} {SITE_NAME}
          </p>
          <div className="flex items-center gap-6">
            <div className="flex gap-4 border-r border-white/10 pr-6 mr-2">
              <a
                href="https://github.com"
                target="_blank"
                rel="noreferrer noopener"
                className="text-muted-foreground hover:text-white transition-colors"
                aria-label="GitHub"
              >
                <Github size={18} />
              </a>
              <a
                href="https://linkedin.com"
                target="_blank"
                rel="noreferrer noopener"
                className="text-muted-foreground hover:text-white transition-colors"
                aria-label="LinkedIn"
              >
                <Linkedin size={18} />
              </a>
            </div>
            <div className="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20">
              <div
                className="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"
                aria-hidden
              />
              <span className="text-[10px] uppercase font-medium text-green-500/90 tracking-wider">
                All systems normal
              </span>
            </div>
          </div>
        </div>
      </div>
    </footer>
    </div>
  )
}
