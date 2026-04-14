"use client"

import * as React from "react"
import { useAnimate } from "framer-motion"
import { Mail, Calendar, MessageCircle } from "lucide-react"
import { cn } from "@/lib/utils"
import { Button, buttonVariants } from "@/components/ui/button"
import {
  HighlighterItem,
  HighlightGroup,
  Particles,
} from "@/components/ui/highlighter"

export function ConnectSection() {
  const [scope, animate] = useAnimate()

  React.useEffect(() => {
    if (!scope.current) return
    animate(
      [
        ["#pointer", { left: 200, top: 60 }, { duration: 0 }],
        ["#website", { opacity: 1 }, { duration: 0.3 }],
        ["#pointer", { left: 50, top: 102 }, { at: "+0.5", duration: 0.5, ease: "easeInOut" }],
        ["#website", { opacity: 0.4 }, { at: "-0.3", duration: 0.1 }],
        ["#seo", { opacity: 1 }, { duration: 0.3 }],
        ["#pointer", { left: 224, top: 170 }, { at: "+0.5", duration: 0.5, ease: "easeInOut" }],
        ["#seo", { opacity: 0.4 }, { at: "-0.3", duration: 0.1 }],
        ["#advertising", { opacity: 1 }, { duration: 0.3 }],
        ["#pointer", { left: 88, top: 198 }, { at: "+0.5", duration: 0.5, ease: "easeInOut" }],
        ["#advertising", { opacity: 0.4 }, { at: "-0.3", duration: 0.1 }],
        ["#social", { opacity: 1 }, { duration: 0.3 }],
        ["#pointer", { left: 200, top: 60 }, { at: "+0.5", duration: 0.5, ease: "easeInOut" }],
        ["#social", { opacity: 0.5 }, { at: "-0.3", duration: 0.1 }],
      ],
      { repeat: Number.POSITIVE_INFINITY }
    )
  }, [animate, scope])

  return (
    <section className="relative mx-auto max-w-5xl px-4 py-16 md:py-20">
      <HighlightGroup className="group h-full">
        <div className="group/item h-full md:col-span-6 lg:col-span-12">
          <HighlighterItem className="rounded-3xl p-6">
            <div className="relative z-20 h-full overflow-hidden rounded-3xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
              <Particles
                className="absolute inset-0 -z-10 opacity-10 transition-opacity duration-1000 ease-in-out group-hover/item:opacity-100"
                quantity={200}
                color="#555555"
                vy={-0.2}
              />
              <div className="flex justify-center">
                <div className="flex h-full flex-col justify-center gap-10 p-4 md:h-[300px] md:flex-row">
                  <div
                    className="relative mx-auto h-[270px] w-[300px] md:h-[270px] md:w-[300px]"
                    ref={scope}
                  >
                    <div className="absolute left-1/2 top-1/2 h-6 w-6 -translate-x-1/2 -translate-y-1/2 text-slate-400">
                      <MessageCircle className="h-6 w-6" />
                    </div>
                    <div
                      id="website"
                      className="absolute bottom-12 left-14 rounded-3xl border border-slate-400 bg-slate-200 px-2 py-1.5 text-xs opacity-50 dark:border-slate-600 dark:bg-slate-800"
                    >
                      Website design
                    </div>
                    <div
                      id="seo"
                      className="absolute left-2 top-20 rounded-3xl border border-slate-400 bg-slate-200 px-2 py-1.5 text-xs opacity-50 dark:border-slate-600 dark:bg-slate-800"
                    >
                      SEO
                    </div>
                    <div
                      id="advertising"
                      className="absolute bottom-20 right-1 rounded-3xl border border-slate-400 bg-slate-200 px-2 py-1.5 text-xs opacity-50 dark:border-slate-600 dark:bg-slate-800"
                    >
                      Advertising
                    </div>
                    <div
                      id="social"
                      className="absolute right-12 top-10 rounded-3xl border border-slate-400 bg-slate-200 px-2 py-1.5 text-xs opacity-50 dark:border-slate-600 dark:bg-slate-800"
                    >
                      Social media
                    </div>
                    <div id="pointer" className="absolute">
                      <svg
                        width="16.8"
                        height="18.2"
                        viewBox="0 0 12 13"
                        className="fill-amber-500"
                        stroke="white"
                        strokeWidth="1"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fillRule="evenodd"
                          clipRule="evenodd"
                          d="M12 5.50676L0 0L2.83818 13L6.30623 7.86537L12 5.50676V5.50676Z"
                        />
                      </svg>
                      <span className="relative -top-1 left-3 rounded-3xl bg-amber-500 px-2 py-1 text-xs text-white">
                        Systemiks
                      </span>
                    </div>
                  </div>

                  <div className="-mt-20 flex h-full flex-col justify-center p-2 md:-mt-4 md:ml-10 md:w-[400px]">
                    <div className="flex flex-col items-center">
                      <h3 className="mt-6 pb-1 font-bold">
                        <span className="text-2xl md:text-4xl">
                          Questions about our services?
                        </span>
                      </h3>
                    </div>
                    <p className="mb-4 text-slate-400">
                      Get in touch — we’d love to hear from you.
                    </p>
                    <div className="flex flex-wrap gap-2">
                      <a href="/contact.php">
                        <Button>
                          <Calendar className="mr-2 h-4 w-4" />
                          Book a call
                        </Button>
                      </a>
                      <a
                        href="mailto:info@systemiks.ca"
                        target="_blank"
                        rel="noreferrer"
                        className={cn(
                          buttonVariants({ variant: "outline", size: "icon" })
                        )}
                        aria-label="Email"
                      >
                        <Mail className="h-5 w-5" strokeWidth={1} />
                      </a>
                      <a
                        href="/contact.php"
                        className={cn(
                          buttonVariants({ variant: "outline", size: "icon" })
                        )}
                        aria-label="Contact"
                      >
                        <MessageCircle className="h-4 w-4" strokeWidth={1} />
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </HighlighterItem>
        </div>
      </HighlightGroup>
    </section>
  )
}
