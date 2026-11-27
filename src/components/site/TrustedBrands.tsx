import { motion } from "framer-motion";
import { useCms } from "@/lib/cms";
import { Reveal } from "./primitives";

export function TrustedBrands() {
  const { brands } = useCms();
  const activeBrands = (brands || []).filter((b) => b.is_active);

  if (activeBrands.length === 0) return null;

  return (
    <section className="border-y border-border/40 bg-secondary/30 py-12 sm:py-16">
      <div className="container-x">
        <Reveal className="mb-8 text-center max-w-2xl mx-auto">
          <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">
            Technology Partners & Enterprise Infrastructure
          </p>
          <h2 className="mt-2 text-2xl font-extrabold text-primary sm:text-3xl">
            Trusted by Businesses, Backed by World-Class Tech
          </h2>
          <p className="mt-2 text-xs sm:text-sm text-muted-foreground">
            Powering homes and institutions in Peddapuram and surrounding areas with carrier-grade optical hardware, high-availability routing, and trusted partners.
          </p>
        </Reveal>

        <div className="grid grid-cols-2 min-[480px]:grid-cols-3 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4 items-center justify-center">
          {activeBrands.map((b, i) => {
            const logoContent = (
              <div className="flex h-full w-full items-center justify-center">
                {b.logo_url ? (
                  <>
                    <img
                      src={b.logo_url}
                      alt={b.name}
                      loading="lazy"
                      className="max-h-10 max-w-[125px] w-auto h-auto object-contain filter grayscale contrast-125 opacity-70 transition-all duration-300 group-hover:grayscale-0 group-hover:opacity-100 dark:brightness-125 dark:invert dark:group-hover:invert-0"
                      onError={(e) => {
                        e.currentTarget.style.display = "none";
                        const fallback = e.currentTarget.nextElementSibling as HTMLElement;
                        if (fallback) fallback.style.display = "block";
                      }}
                    />
                    <span
                      style={{ display: "none" }}
                      className="text-center text-xs font-bold uppercase tracking-wider text-muted-foreground group-hover:text-primary"
                    >
                      {b.name}
                    </span>
                  </>
                ) : (
                  <span className="text-center text-xs font-bold uppercase tracking-wider text-muted-foreground group-hover:text-primary">
                    {b.name}
                  </span>
                )}
              </div>
            );

            return (
              <Reveal key={b.id || b.name} delay={i * 0.05}>
                <motion.div
                  whileHover={{ y: -3 }}
                  className="group relative flex h-20 sm:h-24 w-full items-center justify-center rounded-2xl border border-border/70 bg-card/75 p-3 sm:p-4 shadow-xs backdrop-blur-xs transition-all duration-300 hover:border-accent/40 hover:bg-card hover:shadow-soft"
                >
                  {b.website_url ? (
                    <a
                      href={b.website_url}
                      target="_blank"
                      rel="noopener noreferrer"
                      title={`${b.name}${b.category ? ` · ${b.category}` : ""}`}
                      className="flex h-full w-full items-center justify-center"
                    >
                      {logoContent}
                    </a>
                  ) : (
                    <div title={b.name} className="flex h-full w-full items-center justify-center">
                      {logoContent}
                    </div>
                  )}
                </motion.div>
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
