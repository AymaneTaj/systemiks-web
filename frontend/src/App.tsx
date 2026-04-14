import { BackgroundGradientAnimation } from "@/components/ui/background-gradient-animation"
import { ConnectSection } from "@/components/ConnectSection"

// Systemiks brand: yellow #f5d100, orange #f0a050, pink #e87a9e, blue #0026ff, dark #0a0a0a
const systemiksColors = {
  gradientStart: "rgb(10, 10, 10)",
  gradientEnd: "rgb(108, 0, 82)",
  firstColor: "245, 209, 0",   // yellow
  secondColor: "232, 122, 158", // pink
  thirdColor: "240, 160, 80",   // orange
  fourthColor: "0, 38, 255",    // blue
  fifthColor: "245, 209, 0",    // yellow
  pointerColor: "255, 255, 255",
}

export default function App() {
  return (
    <div className="min-h-screen">
      <section className="min-h-screen">
        <BackgroundGradientAnimation
          gradientBackgroundStart={systemiksColors.gradientStart}
          gradientBackgroundEnd={systemiksColors.gradientEnd}
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
      </section>
      <ConnectSection />
    </div>
  )
}
