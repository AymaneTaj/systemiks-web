import React from "react"
import { motion } from "framer-motion"
import { cn } from "@/lib/utils"
import {
  Link2,
  GitBranch,
  Search,
  TrendingUp,
  BarChart2,
  FileText,
} from "lucide-react"

/** Random floating shapes for SEO hero background (circles, blobs, rounded rects) */
const RANDOM_SHAPES = [
  { left: "5%", top: "10%", width: 120, height: 120, borderRadius: "60%", color: "rgba(247, 214, 0, 0.08)", duration: 8, delay: 0 },
  { left: "85%", top: "15%", width: 80, height: 80, borderRadius: "50%", color: "rgba(0, 38, 255, 0.06)", duration: 10, delay: 1 },
  { left: "15%", top: "60%", width: 160, height: 100, borderRadius: "24px", color: "rgba(247, 214, 0, 0.06)", duration: 12, delay: 2 },
  { left: "75%", top: "55%", width: 100, height: 100, borderRadius: "50%", color: "rgba(232, 122, 158, 0.07)", duration: 9, delay: 0.5 },
  { left: "50%", top: "20%", width: 60, height: 60, borderRadius: "50%", color: "rgba(247, 214, 0, 0.05)", duration: 7, delay: 3 },
  { left: "8%", top: "75%", width: 90, height: 90, borderRadius: "40%", color: "rgba(0, 38, 255, 0.05)", duration: 11, delay: 1.5 },
  { left: "88%", top: "70%", width: 70, height: 110, borderRadius: "35px", color: "rgba(247, 214, 0, 0.07)", duration: 9, delay: 2 },
  { left: "25%", top: "25%", width: 50, height: 50, borderRadius: "50%", color: "rgba(232, 122, 158, 0.05)", duration: 8, delay: 0.8 },
  { left: "70%", top: "35%", width: 130, height: 80, borderRadius: "20px", color: "rgba(247, 214, 0, 0.05)", duration: 10, delay: 2.2 },
  { left: "40%", top: "65%", width: 100, height: 100, borderRadius: "30%", color: "rgba(0, 38, 255, 0.04)", duration: 11, delay: 1 },
  { left: "92%", top: "40%", width: 55, height: 55, borderRadius: "50%", color: "rgba(247, 214, 0, 0.06)", duration: 7, delay: 1.8 },
  { left: "3%", top: "45%", width: 85, height: 85, borderRadius: "50%", color: "rgba(232, 122, 158, 0.05)", duration: 9, delay: 0.3 },
]

function RandomShapesBackground() {
  return (
    <div className="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
      {RANDOM_SHAPES.map((shape, i) => (
        <motion.div
          key={i}
          className="absolute"
          style={{
            left: shape.left,
            top: shape.top,
            width: shape.width,
            height: shape.height,
            borderRadius: shape.borderRadius,
            background: shape.color,
            filter: "blur(40px)",
          }}
          initial={{ opacity: 0.3, scale: 0.8 }}
          animate={{
            opacity: [0.3, 0.6, 0.3],
            scale: [0.9, 1.1, 0.9],
            x: [0, 15, -10, 0],
            y: [0, -20, 10, 0],
          }}
          transition={{
            duration: shape.duration,
            delay: shape.delay,
            repeat: Infinity,
            repeatType: "reverse",
          }}
        />
      ))}
    </div>
  )
}

/** Systemiks brand colors for SEO hero */
const COLORS = {
  dark: "#0a0a0a",
  darkMid: "#120f0a",
  darkLight: "#1a1508",
  primary: "rgba(247, 214, 0, 0.95)",
  primaryGlow: "rgba(247, 214, 0, 0.25)",
  primaryMuted: "rgba(247, 214, 0, 0.15)",
  white: "#ffffff",
  whiteMuted: "rgba(255, 255, 255, 0.7)",
  whiteSoft: "rgba(255, 255, 255, 0.5)",
  border: "rgba(255, 255, 255, 0.2)",
}

interface SEOFloatingItem {
  icon: React.ReactNode
  label: string
  position: { x: string; y: string }
}

export interface SEOHeroProps {
  title?: string
  highlightedText?: string
  subtitle?: string
  ctaLabel?: string
  ctaHref?: string
  floatingItems?: SEOFloatingItem[]
  className?: string
}

const defaultFloatingItems: SEOFloatingItem[] = [
  {
    icon: <Link2 className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Backlinks",
    position: { x: "10%", y: "20%" },
  },
  {
    icon: <GitBranch className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Internal links",
    position: { x: "12%", y: "55%" },
  },
  {
    icon: <Search className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Keywords",
    position: { x: "78%", y: "22%" },
  },
  {
    icon: <TrendingUp className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Rank",
    position: { x: "82%", y: "56%" },
  },
  {
    icon: <BarChart2 className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Traffic",
    position: { x: "6%", y: "78%" },
  },
  {
    icon: <FileText className="w-6 h-6 md:w-7 md:h-7" strokeWidth={2} />,
    label: "Content",
    position: { x: "86%", y: "78%" },
  },
]

export function SEOHero({
  title = "Search Engine",
  highlightedText = "Optimization",
  subtitle = "Data-driven SEO for brands that refuse to blend in. Custom strategies that capture demand when customers are ready to find you.",
  ctaLabel = "Book a call",
  ctaHref = "/contact.php",
  floatingItems = defaultFloatingItems,
  className,
}: SEOHeroProps) {
  return (
    <section
      className={cn(
        "relative w-full flex flex-col overflow-hidden",
        className
      )}
      style={{
        minHeight: "70vh",
        height: "70vh",
        background: `linear-gradient(180deg, ${COLORS.dark} 0%, ${COLORS.darkMid} 50%, ${COLORS.darkLight} 100%)`,
      }}
      role="banner"
      aria-label="SEO hero section"
    >
      {/* Random floating shapes background */}
      <RandomShapesBackground />

      {/* Radial glow */}
      <div
        className="absolute inset-0 z-0 pointer-events-none"
        aria-hidden="true"
        style={{
          background: `radial-gradient(circle at 50% 50%, ${COLORS.primaryGlow} 0%, transparent 65%)`,
          filter: "blur(80px)",
        }}
      />

      {/* Main content */}
      <div className="relative z-10 flex-1 flex flex-col items-center justify-center px-4 py-8 min-h-0">
        {/* Floating SEO term badges */}
        {floatingItems.map((item, index) => (
          <motion.div
            key={item.label}
            className="absolute flex flex-col items-center gap-2"
            style={{
              left: item.position.x,
              top: item.position.y,
            }}
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{
              opacity: 1,
              scale: 1,
              y: [0, -12, 0],
            }}
            transition={{
              opacity: { duration: 0.5, delay: 0.2 + index * 0.08 },
              scale: { duration: 0.5, delay: 0.2 + index * 0.08 },
              y: {
                duration: 2.5 + index * 0.3,
                repeat: Infinity,
                ease: "easeInOut",
              },
            }}
          >
            <div
              className="flex items-center justify-center rounded-full border w-14 h-14 md:w-16 md:h-16 text-[#f7d600]"
              style={{
                background: "rgba(0, 0, 0, 0.35)",
                borderColor: "rgba(247, 214, 0, 0.4)",
                boxShadow: "0 0 32px rgba(247, 214, 0, 0.25), inset 0 0 20px rgba(247, 214, 0, 0.06)",
              }}
            >
              {item.icon}
            </div>
            <span
              className="text-[10px] md:text-xs font-semibold uppercase tracking-widest mt-1.5"
              style={{ color: "rgba(255, 255, 255, 0.9)" }}
            >
              {item.label}
            </span>
          </motion.div>
        ))}

        <motion.div
          initial={{ opacity: 0, y: 24 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.15 }}
          className="flex flex-col items-center text-center max-w-3xl"
          style={{ gap: "24px" }}
        >
          <h1
            className="font-medium text-white tracking-tight"
            style={{
              fontFamily: "var(--font-head), Poppins, system-ui, sans-serif",
              fontSize: "clamp(2rem, 5vw, 3.25rem)",
              lineHeight: 1.2,
              letterSpacing: "-0.02em",
            }}
          >
            {title}
            <br />
            <span
              style={{
                background: `linear-gradient(90deg, ${COLORS.primary} 0%, #ffb84d 50%, ${COLORS.primary} 100%)`,
                WebkitBackgroundClip: "text",
                WebkitTextFillColor: "transparent",
                backgroundClip: "text",
                fontWeight: 700,
              }}
            >
              {highlightedText}
            </span>
          </h1>

          <p
            className="text-base leading-relaxed max-w-[480px]"
            style={{
              color: COLORS.whiteMuted,
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
            whileHover={{ scale: 1.03 }}
            whileTap={{ scale: 0.98 }}
            className="inline-flex items-center justify-center rounded-full min-w-[180px] px-8 py-4 font-semibold text-[15px] no-underline transition-all border border-[rgba(247,214,0,0.5)]"
            style={{
              background: "rgba(0, 0, 0, 0.25)",
              color: "#f7d600",
              boxShadow: "0 0 28px rgba(247, 214, 0, 0.2)",
            }}
          >
            {ctaLabel}
          </motion.a>
        </motion.div>
      </div>
    </section>
  )
}
