/**
 * Embed entry: mount React hero, Connect section, or Calendar into PHP roots.
 * index.php: #hero-root, #connect-root. contact.php: #calendar-root.
 */
import { createRoot } from "react-dom/client"
import "./index.css"
import { BackgroundGradientAnimation } from "@/components/ui/background-gradient-animation"
import { ConnectSection } from "@/components/ConnectSection"
import { Calendar } from "@/components/ui/calendar"
import { NeoMinimalFooter } from "@/components/ui/neo-minimal-footer"
import { WorkCardsGrid } from "@/components/WorkCardsGrid"
import { SEOHero } from "@/components/ui/seo-hero"
import { PPCHero } from "@/components/ui/ppc-hero"

const systemiksColors = {
  gradientBackgroundStart: "rgb(10, 10, 10)",
  gradientBackgroundEnd: "rgb(108, 0, 82)",
  firstColor: "245, 209, 0",
  secondColor: "232, 122, 158",
  thirdColor: "240, 160, 80",
  fourthColor: "0, 38, 255",
  fifthColor: "245, 209, 0",
  pointerColor: "255, 255, 255",
}

const heroRoot = document.getElementById("hero-root")
if (heroRoot) {
  createRoot(heroRoot).render(
    <BackgroundGradientAnimation
      gradientBackgroundStart={systemiksColors.gradientBackgroundStart}
      gradientBackgroundEnd={systemiksColors.gradientBackgroundEnd}
      firstColor={systemiksColors.firstColor}
      secondColor={systemiksColors.secondColor}
      thirdColor={systemiksColors.thirdColor}
      fourthColor={systemiksColors.fourthColor}
      fifthColor={systemiksColors.fifthColor}
      pointerColor={systemiksColors.pointerColor}
      size="80%"
      blendingValue="hard-light"
      interactive={true}
      containerClassName=""
    >
      <div className="absolute z-50 inset-0 flex items-center justify-center text-white font-bold px-4 pointer-events-none text-center">
        <p className="bg-clip-text text-transparent drop-shadow-2xl bg-gradient-to-b from-white/90 to-white/20 text-5xl md:text-6xl lg:text-8xl tracking-tight uppercase">
          Systemiks
        </p>
      </div>
    </BackgroundGradientAnimation>
  )
}

const connectRoot = document.getElementById("connect-root")
if (connectRoot) {
  createRoot(connectRoot).render(<ConnectSection />)
}

const calendarRoot = document.getElementById("calendar-root")
if (calendarRoot) {
  const bookingUrl = calendarRoot.getAttribute("data-booking-url") || "/contact.php"
  createRoot(calendarRoot).render(
    <Calendar
      bookingLink={bookingUrl}
      title="Book a discovery call"
      description="Pick a time that works for you — we’ll take it from there."
      callDuration="30 min call"
    />
  )
}

const workRoot = document.getElementById("work-root")
if (workRoot) {
  createRoot(workRoot).render(<WorkCardsGrid />)
}

const footerRoot = document.getElementById("footer-root")
if (footerRoot) {
  createRoot(footerRoot).render(<NeoMinimalFooter />)
}

const seoHeroRoot = document.getElementById("seo-hero-root")
if (seoHeroRoot) {
  const ctaUrl = seoHeroRoot.getAttribute("data-cta-url") ?? "/contact.php"
  createRoot(seoHeroRoot).render(
    <SEOHero
      title="Search Engine"
      highlightedText="Optimization"
      subtitle="Data-driven SEO for brands that refuse to blend in. Custom strategies that capture demand when customers are ready to find you."
      ctaLabel="Book a call"
      ctaHref={ctaUrl}
    />
  )
}

const ppcHeroRoot = document.getElementById("ppc-hero-root")
if (ppcHeroRoot) {
  const ctaUrl = ppcHeroRoot.getAttribute("data-cta-url") ?? "/contact.php"
  createRoot(ppcHeroRoot).render(
    <PPCHero
      title="Performance"
      highlightedText="Advertising"
      subtitle="Paid campaigns for brands that refuse to blend in. We run Google, Meta, and TikTok so you capture demand when customers are ready to buy—measurable ROI, scalable acquisition."
      ctaLabel="Book a call"
      ctaHref={ctaUrl}
    />
  )
}
