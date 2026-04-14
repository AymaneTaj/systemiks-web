import { useState } from "react"
import { GlassWorkCard } from "@/components/ui/glass-work-card"
import { cn } from "@/lib/utils"

export interface WorkItem {
  title: string
  category?: string
  result?: string
  tags?: string[]
  color?: string
  image?: string | null
  /** Legacy fields from older `__WORK_DATA__` payloads */
  tag?: string
  img?: string
  service?: string
  excerpt?: string
  href?: string
  client?: { name: string; avatar?: string }
  year?: string
}

declare global {
  interface Window {
    __WORK_DATA__?: WorkItem[]
  }
}

const FILTERS = [
  { id: "all", label: "All" },
  { id: "web", label: "Web" },
  { id: "branding", label: "Branding" },
  { id: "seo", label: "SEO" },
  { id: "ppc", label: "PPC" },
] as const

type WorkFilterId = (typeof FILTERS)[number]["id"]

function projectMatchesFilter(p: WorkItem, filter: WorkFilterId): boolean {
  const rawTags = p.tags?.length ? p.tags : p.tag ? [p.tag] : []
  const tags = rawTags.map((t) => t.toLowerCase())
  const cat = (p.category ?? "").toLowerCase()
  switch (filter) {
    case "all":
      return true
    case "web":
      return (
        tags.some((t) => t.includes("web") || t.includes("e-commerce") || t.includes("ecommerce")) ||
        cat.includes("e-commerce")
      )
    case "seo":
      return tags.some((t) => t.includes("seo")) || cat.includes("seo")
    case "branding":
      return tags.some((t) => t.includes("branding")) || cat.includes("branding")
    case "ppc":
      return tags.some((t) => t.includes("ads")) || cat.includes("ads")
  }
}

export function WorkCardsGrid() {
  const raw = typeof window !== "undefined" ? window.__WORK_DATA__ : undefined
  const items: WorkItem[] = Array.isArray(raw) ? raw : []
  const [filter, setFilter] = useState<WorkFilterId>("all")

  const filtered = items.filter((p) => projectMatchesFilter(p, filter))

  return (
    <div className="work-cards-grid-wrap">
      <div className="work-filter-wrap mb-6 flex flex-wrap items-center gap-2">
        <span className="text-sm font-medium text-muted-foreground">Filter:</span>
        <div className="flex flex-wrap gap-2" role="tablist">
          {FILTERS.map((f) => (
            <button
              key={f.id}
              type="button"
              role="tab"
              onClick={() => setFilter(f.id)}
              className={cn(
                "rounded-full border px-4 py-2 text-sm font-medium transition-colors",
                filter === f.id
                  ? "border-primary bg-primary text-primary-foreground"
                  : "border-border bg-background text-foreground hover:bg-muted"
              )}
            >
              {f.label}
            </button>
          ))}
        </div>
      </div>
      <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        {filtered.map((p, i) => {
          const displayTags = p.tags?.length ? p.tags : p.tag ? [p.tag] : []
          const excerpt = p.result ?? p.excerpt
          const imageSrc = p.img ?? p.image ?? undefined
          return (
            <GlassWorkCard
              key={`${p.title}-${i}`}
              title={p.title}
              excerpt={excerpt}
              image={imageSrc}
              thumbGradient={p.color}
              tags={displayTags}
              service={p.category ?? p.tag}
              year={p.year}
              href={p.href ?? "/work.php"}
              client={p.client}
            />
          )
        })}
      </div>
      {filtered.length === 0 && (
        <p className="py-8 text-center text-muted-foreground">
          No projects in this category.
        </p>
      )}
    </div>
  )
}
