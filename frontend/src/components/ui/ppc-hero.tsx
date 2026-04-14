import { motion } from "framer-motion"
import { BackgroundLines } from "@/components/ui/animated-svg-background"
import { cn } from "@/lib/utils"

export interface PPCHeroProps {
  title?: string
  highlightedText?: string
  subtitle?: string
  ctaLabel?: string
  ctaHref?: string
  svgDuration?: number
  className?: string
}

export function PPCHero({
  title = "Performance",
  highlightedText = "Advertising",
  subtitle = "Paid campaigns for brands that refuse to blend in. We run Google, Meta, and TikTok so you capture demand when customers are ready to buy—measurable ROI, scalable acquisition.",
  ctaLabel = "Book a call",
  ctaHref = "/contact.php",
  svgDuration = 10,
  className,
}: PPCHeroProps) {
  return (
    <BackgroundLines
      className={cn("flex items-center justify-center w-full flex-col px-4 relative", className)}
      svgOptions={{ duration: svgDuration }}
    >
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, delay: 0.2 }}
        className="relative z-20 flex flex-1 flex-col items-center justify-center text-center max-w-3xl gap-6 py-16"
      >
        <h1
          className="font-medium tracking-tight text-white"
          style={{
            fontFamily: "var(--font-head), Poppins, system-ui, sans-serif",
            fontSize: "clamp(2rem, 5vw, 3.25rem)",
            lineHeight: 1.2,
            letterSpacing: "-0.02em",
          }}
        >
          {title}{" "}
          <span
            className="bg-clip-text text-transparent font-bold"
            style={{
              backgroundImage: "linear-gradient(90deg, #f7d600 0%, #ffb84d 50%, #f7d600 100%)",
              WebkitBackgroundClip: "text",
              WebkitTextFillColor: "transparent",
              backgroundClip: "text",
            }}
          >
            {highlightedText}
          </span>
        </h1>

        <p
          className="max-w-xl mx-auto text-sm md:text-base text-white/80 leading-relaxed"
          style={{
            fontFamily: "var(--font-body), Public Sans, system-ui, sans-serif",
          }}
        >
          {subtitle}
        </p>

        <motion.a
          href={ctaHref}
          initial={{ opacity: 0, scale: 0.96 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.5, delay: 0.4 }}
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.98 }}
          className="inline-flex items-center justify-center rounded-full px-8 py-3.5 font-semibold text-[15px] no-underline border-2 border-[rgba(247,214,0,0.5)] text-white transition-all"
          style={{
            background: "rgba(247, 214, 0, 0.12)",
            boxShadow: "0 4px 24px rgba(247, 214, 0, 0.2)",
          }}
        >
          {ctaLabel}
        </motion.a>
      </motion.div>
    </BackgroundLines>
  )
}
