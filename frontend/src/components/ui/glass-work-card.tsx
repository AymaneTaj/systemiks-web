import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Badge } from "@/components/ui/badge"
import { Card } from "@/components/ui/card"
import { cn } from "@/lib/utils"
import { motion } from "framer-motion"
import { ArrowRight, Briefcase } from "lucide-react"

function thumbMonogram(title: string): string {
  const t = title.trim()
  return t.slice(0, 2).toUpperCase()
}

export interface GlassWorkCardProps {
  title?: string
  excerpt?: string
  image?: string | null
  /** When set and `image` is empty, renders a gradient block instead of a photo. */
  thumbGradient?: string
  client?: {
    name: string
    avatar?: string
  }
  service?: string
  year?: string
  tags?: string[]
  href?: string
  className?: string
}

const defaultWork = {
  title: "E-commerce & brand redesign",
  excerpt:
    "Full website redesign, visual identity, and performance campaigns for measurable growth.",
  image: "",
  client: {
    name: "Systemiks",
  },
  service: "Web + Branding",
  year: "2025",
  tags: ["Web", "Branding"],
  href: "/work.php",
}

export function GlassWorkCard({
  title = defaultWork.title,
  excerpt = defaultWork.excerpt,
  image,
  thumbGradient,
  client = defaultWork.client,
  service = defaultWork.service,
  year = defaultWork.year,
  tags = defaultWork.tags,
  href = defaultWork.href,
  className,
}: GlassWorkCardProps) {
  const resolvedImage =
    image !== undefined && image !== null && String(image).trim() !== ""
      ? String(image).trim()
      : undefined
  const useGradient = Boolean(thumbGradient && !resolvedImage)
  const photoSrc =
    resolvedImage !== undefined && resolvedImage !== ""
      ? resolvedImage
      : defaultWork.image && String(defaultWork.image).trim() !== ""
        ? String(defaultWork.image).trim()
        : undefined

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
      className={cn("w-full max-w-[400px]", className)}
    >
      <Card className="group relative h-full overflow-hidden rounded-2xl border-border/50 bg-card/30 backdrop-blur-md transition-all duration-300 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10">
        {/* Image Section */}
        <div className="relative aspect-[16/9] overflow-hidden">
          {useGradient ? (
            <motion.div
              aria-hidden
              className="flex h-full w-full items-center justify-center transition-transform duration-500 group-hover:scale-110"
              style={{ background: thumbGradient }}
            >
              <span className="select-none text-5xl font-black tracking-tight text-white/[0.15]">
                {thumbMonogram(title ?? "")}
              </span>
            </motion.div>
          ) : photoSrc ? (
            <motion.img
              src={photoSrc}
              alt={title}
              className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
            />
          ) : (
            <div
              aria-hidden
              className="flex h-full w-full items-center justify-center bg-muted transition-transform duration-500 group-hover:scale-105"
            >
              <Briefcase className="h-12 w-12 text-muted-foreground/40" />
            </div>
          )}
          <div className="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent opacity-60 transition-opacity duration-300 group-hover:opacity-40" />

          <div className="absolute bottom-3 left-3 flex gap-2">
            {tags?.map((tag, index) => (
              <Badge
                key={index}
                variant="secondary"
                className="bg-background/50 backdrop-blur-sm hover:bg-background/80"
              >
                {tag}
              </Badge>
            ))}
          </div>

          {/* Hover Overlay Action */}
          <div className="absolute inset-0 flex items-center justify-center bg-background/20 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <motion.a
              href={href}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="flex items-center gap-2 rounded-full bg-primary px-6 py-2.5 text-sm font-medium text-primary-foreground shadow-lg shadow-primary/25 no-underline"
            >
              <ArrowRight className="h-4 w-4" />
              View project
            </motion.a>
          </div>
        </div>

        {/* Content Section */}
        <div className="flex flex-col gap-4 p-5">
          <div className="space-y-2">
            <h3 className="text-xl font-semibold leading-tight tracking-tight text-foreground transition-colors group-hover:text-primary">
              {title}
            </h3>
            <p className="line-clamp-2 text-sm text-muted-foreground">
              {excerpt}
            </p>
          </div>

          <div className="flex items-center justify-between border-t border-border/50 pt-4">
            <div className="flex items-center gap-2">
              <Avatar className="h-8 w-8 border border-border/50">
                <AvatarImage src={client?.avatar} alt={client?.name} />
                <AvatarFallback>
                  {client?.name ? client.name[0] : <Briefcase className="h-4 w-4" />}
                </AvatarFallback>
              </Avatar>
              <div className="flex flex-col text-xs">
                <span className="font-medium text-foreground">
                  {client?.name ?? "Project"}
                </span>
                <span className="text-muted-foreground">{service ?? year}</span>
              </div>
            </div>

            <div className="flex items-center gap-1 text-xs text-muted-foreground">
              <span>{year}</span>
            </div>
          </div>
        </div>
      </Card>
    </motion.div>
  )
}
