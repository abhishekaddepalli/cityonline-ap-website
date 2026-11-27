import type { ReactNode } from "react";
import { cn } from "@/lib/utils";
import { ext } from "@/lib/links";
import { useCms } from "@/lib/cms";
import logo from "@/assets/city-online-logo.png.asset.json";

export function Logo({ className, surface = false, type = "header" }: { className?: string; surface?: boolean; type?: "header" | "footer" }) {
  const { settings } = useCms();
  const custom = type === "footer" ? settings?.footer_logo : settings?.header_logo;
  const defaultFallback = logo.url || "/__l5e/assets-v1/9f8be65f-5299-4384-8c94-43db0ccd1033/city-online-logo.png";
  const logoSrc = custom || defaultFallback;

  const img = (
    <img
      src={logoSrc}
      alt="City Online AP Pvt. Ltd."
      width={423}
      height={54}
      decoding="async"
      onError={(e) => {
        if (e.currentTarget.src !== window.location.origin + defaultFallback) {
          e.currentTarget.src = defaultFallback;
        }
      }}
      className={cn("h-auto max-w-full w-auto object-contain", className)}
    />
  );
  // The logo artwork is dark blue; on dark surfaces it sits on a light plate so it stays legible.
  return surface ? <span className="inline-flex rounded-xl bg-logo-surface px-3 py-2 shadow-xs">{img}</span> : img;
}

export function Reveal({ children, className }: { children: ReactNode; delay?: number; className?: string }) {
  return <div className={cn("min-w-0", className)}>{children}</div>;
}

export function Eyebrow({ children, className }: { children: ReactNode; className?: string }) {
  return <p className={cn("text-xs font-bold uppercase tracking-[0.22em] text-accent", className)}>{children}</p>;
}

export function SectionHeading({ eyebrow, title, sub, center = true }: { eyebrow?: string; title: ReactNode; sub?: ReactNode; center?: boolean }) {
  return (
    <Reveal className={cn("mb-10 max-w-2xl", center && "mx-auto text-center")}>
      {eyebrow && <Eyebrow>{eyebrow}</Eyebrow>}
      <h2 className="mt-3 text-3xl font-extrabold text-primary sm:text-4xl">{title}</h2>
      {sub && <p className="mt-3 text-base text-muted-foreground sm:text-lg">{sub}</p>}
    </Reveal>
  );
}

const btn = {
  orange: "bg-accent text-accent-foreground shadow-orange hover:bg-accent-hover",
  blue: "bg-primary text-primary-foreground shadow-soft hover:opacity-90",
  outline: "border-2 border-primary/25 text-primary hover:border-primary hover:bg-secondary",
  ghostLight: "border-2 border-navy-foreground/40 text-navy-foreground hover:bg-navy-foreground/10",
};
export const btnClass = (v: keyof typeof btn = "orange", className?: string) =>
  cn(
    "inline-flex min-h-11 min-w-0 max-w-full items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-center text-sm font-bold whitespace-normal transition-all duration-200 active:scale-[0.97] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&>svg]:shrink-0",
    btn[v],
    className,
  );

export function ExtLink({ href, className, children, ...rest }: { href: string; className?: string; children: ReactNode; "aria-label"?: string }) {
  const isWeb = href.startsWith("http");
  return (
    <a href={href} className={className} {...(isWeb ? ext : {})} {...rest}>
      {children}
    </a>
  );
}

export function PageHero({ eyebrow, title, sub, children }: { eyebrow: string; title: ReactNode; sub?: ReactNode; children?: ReactNode }) {
  return (
    <section className="relative overflow-hidden bg-hero text-navy-foreground">
      <FibreBackdrop />
      <div className="container-x relative py-14 sm:py-20">
        <Reveal>
          <Eyebrow>{eyebrow}</Eyebrow>
          <h1 className="mt-3 max-w-3xl text-4xl font-extrabold sm:text-5xl">{title}</h1>
          {sub && <p className="mt-4 max-w-2xl text-base text-navy-foreground/80 sm:text-lg">{sub}</p>}
          {children}
        </Reveal>
      </div>
    </section>
  );
}

export function FibreBackdrop() {
  return (
    <svg aria-hidden className="pointer-events-none absolute inset-0 h-full w-full opacity-60" viewBox="0 0 1200 600" preserveAspectRatio="none">
      <defs>
        <linearGradient id="fo" x1="0" x2="1">
          <stop offset="0" stopColor="var(--accent)" stopOpacity="0" />
          <stop offset="0.5" stopColor="var(--accent)" stopOpacity="0.9" />
          <stop offset="1" stopColor="var(--accent)" stopOpacity="0" />
        </linearGradient>
        <linearGradient id="fb" x1="0" x2="1">
          <stop offset="0" stopColor="var(--brand-blue)" stopOpacity="0" />
          <stop offset="0.5" stopColor="oklch(0.8 0.12 245)" stopOpacity="0.8" />
          <stop offset="1" stopColor="var(--brand-blue)" stopOpacity="0" />
        </linearGradient>
      </defs>
      <path className="fibre-line" d="M-50 480 C 300 380, 600 600, 1250 340" stroke="url(#fo)" strokeWidth="2.5" fill="none" />
      <path className="fibre-line" style={{ animationDuration: "4.5s" }} d="M-50 540 C 400 420, 700 560, 1250 420" stroke="url(#fb)" strokeWidth="1.5" fill="none" />
      <path className="fibre-line" style={{ animationDuration: "6s" }} d="M-50 120 C 300 220, 800 40, 1250 160" stroke="url(#fb)" strokeWidth="1" fill="none" />
    </svg>
  );
}
