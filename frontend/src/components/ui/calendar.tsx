"use client"

import React from "react"
import { Button } from "@/components/ui/button"
import { BentoCard } from "@/components/ui/bento-card"
import { cn } from "@/lib/utils"

const dayNames = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"]

const CalendarDay: React.FC<{ day: number | string; isHeader?: boolean }> = ({
  day,
  isHeader,
}) => {
  const isHighlight =
    !isHeader && typeof day === "number" && day % 5 === 0
  const cellClass = isHighlight
    ? "bg-indigo-500 text-white"
    : "text-slate-500 dark:text-slate-400"

  return (
    <div
      className={cn(
        "col-span-1 row-span-1 flex h-8 w-8 items-center justify-center",
        !isHeader && "rounded-xl",
        cellClass
      )}
    >
      <span className={cn("font-medium", isHeader ? "text-xs" : "text-sm")}>
        {day}
      </span>
    </div>
  )
}

export interface CalendarProps {
  /** Booking URL (e.g. Cal.com or /contact.php) */
  bookingLink?: string
  title?: string
  description?: string
  callDuration?: string
}

export function Calendar({
  bookingLink = "/contact.php",
  title = "Questions about our services?",
  description = "Book a discovery call — we’d love to hear from you.",
  callDuration = "30 min call",
}: CalendarProps) {
  const currentDate = new Date()
  const currentMonth = currentDate.toLocaleString("default", { month: "long" })
  const currentYear = currentDate.getFullYear()
  const firstDayOfMonth = new Date(currentYear, currentDate.getMonth(), 1)
  const firstDayOfWeek = firstDayOfMonth.getDay()
  const daysInMonth = new Date(
    currentYear,
    currentDate.getMonth() + 1,
    0
  ).getDate()

  const renderCalendarDays = () => {
    const emptyDays = Array.from({ length: firstDayOfWeek }, (_, i) => (
      <div key={`empty-${i}`} className="col-span-1 row-span-1 h-8 w-8" />
    ))
    const dateDays = Array.from({ length: daysInMonth }, (_, i) => (
      <CalendarDay key={`date-${i + 1}`} day={i + 1} />
    ))
    return [
      ...dayNames.map((day) => (
        <CalendarDay key={`header-${day}`} day={day} isHeader />
      )),
      ...emptyDays,
      ...dateDays,
    ]
  }

  return (
    <BentoCard height="h-auto" linkTo={bookingLink}>
      <div className="grid h-full gap-5">
        <div>
          <h2 className="mb-4 text-lg font-semibold md:text-3xl">{title}</h2>
          <p className="mb-2 text-xs text-slate-600 dark:text-slate-400 md:text-base">
            {description}
          </p>
          <Button className="mt-3 rounded-2xl">Book now</Button>
        </div>
        <div className="transition-all duration-500 ease-out md:group-hover:-right-12 md:group-hover:top-5">
          <div className="h-full w-full max-w-[550px] rounded-[24px] border border-slate-200 p-2 transition-colors duration-100 group-hover:border-indigo-400 dark:border-slate-700">
            <div
              className="h-full rounded-2xl border-2 border-slate-200/10 p-3 dark:border-slate-600/20"
              style={{ boxShadow: "0px 2px 1.5px 0px rgba(0,0,0,0.08) inset" }}
            >
              <div className="flex items-center space-x-2">
                <p className="text-sm">
                  <span className="font-medium">
                    {currentMonth}, {currentYear}
                  </span>
                </p>
                <span className="h-1 w-1 rounded-full bg-slate-400">&nbsp;</span>
                <p className="text-xs text-slate-500">{callDuration}</p>
              </div>
              <div className="mt-4 grid grid-cols-7 grid-rows-5 gap-2 px-4">
                {renderCalendarDays()}
              </div>
            </div>
          </div>
        </div>
      </div>
    </BentoCard>
  )
}
