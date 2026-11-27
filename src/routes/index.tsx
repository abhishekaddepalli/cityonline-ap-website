import { createFileRoute, Link } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { ArrowRight, ArrowUpDown, Clock, Infinity as InfinityIcon, MapPin, Wifi } from "lucide-react";
import family from "@/assets/family-home-broadband-1600.webp";
import familyMobile from "@/assets/family-home-broadband-800.webp";
import { btnClass, FibreBackdrop, SectionHeading } from "@/components/site/primitives";
import { AboutBlock, Benefits, CoverageChecker, PlansGrid, SelfService, SupportBlock, ToolsGrid } from "@/components/site/sections";
import { TrustedBrands } from "@/components/site/TrustedBrands";
import { LINKS, ext } from "@/lib/links";
import { usePrefs } from "@/lib/prefs";
import { useCms } from "@/lib/cms";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/")({
  head: () => {
    const h = seo("Unlimited Fibre Broadband in Kakinada", "One connection, the whole house. Unlimited fibre broadband in Kakinada from ₹590/month incl. GST, with the same upload and download speed.", "/");
    h.meta[0] = { title: "City Online AP — Unlimited Fibre Broadband in Kakinada" };
    return h;
  },
  component: Home,
});

function Hero() {
  const { t } = usePrefs();
  const { sections } = useCms();
  const heroSec = sections?.hero;

  const trust = [
    { icon: InfinityIcon, l: t("t1") },
    { icon: ArrowUpDown, l: t("t2") },
    { icon: Clock, l: t("t3") },
  ];

  const heroBg = heroSec?.content?.image_url;
  const primaryCtaLabel = heroSec?.content?.primary_cta_label || t("newConnection");
  const primaryCtaUrl = heroSec?.content?.primary_cta_url || LINKS.newConnection;

  return (
    <section className="relative overflow-hidden bg-hero text-navy-foreground">
      <div className="absolute inset-0">
        <img
          src={heroBg || family}
          srcSet={heroBg ? undefined : `${familyMobile} 800w, ${family} 1600w`}
          sizes="100vw"
          alt="A family in Kakinada enjoying fibre internet together on a laptop at home"
          width={1600}
          height={1008}
          fetchPriority="high"
          decoding="async"
          onError={(e) => {
            if (e.currentTarget.src !== family) {
              e.currentTarget.src = family;
            }
          }}
          className="h-full w-full object-cover object-[70%_center] opacity-40 lg:opacity-100"
        />
        <div className="absolute inset-0 bg-gradient-to-r from-hero-edge from-10% via-hero-edge/70 to-transparent lg:from-25% lg:via-hero-edge/40 lg:via-50%" />
        <div className="absolute inset-0 bg-gradient-to-t from-navy/80 to-transparent lg:hidden" />
      </div>
      <FibreBackdrop />
      <div className="container-x relative grid min-h-[560px] items-center py-14 sm:py-20 lg:min-h-[640px]">
        <motion.div initial={false} animate={{ opacity: 1, y: 0 }} className="min-w-0 max-w-xl">
          <span className="inline-flex items-center gap-2 rounded-full border border-navy-foreground/20 bg-navy-foreground/10 px-3 py-1.5 text-xs font-semibold backdrop-blur sm:text-sm">
            <Wifi className="h-4 w-4 text-accent" /> {heroSec?.badge_text || t("heroBadge")}
          </span>
          <h1 className="mt-5 text-3xl font-extrabold leading-tight sm:text-6xl">
            {heroSec?.title || (
              <>
                {t("heroTitleA")}<br /><span className="text-accent">{t("heroTitleB")}</span>
              </>
            )}
          </h1>
          <p className="mt-5 text-base text-navy-foreground/85 sm:text-lg">{heroSec?.subtitle || t("heroSub")}</p>
          <p className="mt-3 text-sm text-navy-foreground/70">{heroSec?.description || "Plans from ₹590/month · GST included"}</p>
          <div className="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href={primaryCtaUrl} {...(primaryCtaUrl.startsWith("http") ? ext : {})} className={btnClass("orange", "h-12 px-6 text-base")}>
              {primaryCtaLabel} <ArrowRight className="h-4 w-4" />
            </a>
            <Link to="/coverage" className={btnClass("ghostLight", "h-12 px-6 text-base")}>
              <MapPin className="h-4 w-4" /> {t("checkArea")}
            </Link>
          </div>
        </motion.div>
      </div>
      <div className="container-x relative pb-8">
        <motion.ul initial={false} className="grid gap-3 rounded-2xl border border-navy-foreground/15 bg-navy/60 p-4 sm:grid-cols-3 sm:p-5">
          {trust.map((x) => (
            <li key={x.l} className="flex items-center gap-3">
              <span className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-accent text-accent-foreground"><x.icon className="h-5 w-5" /></span>
              <span className="min-w-0 text-sm font-semibold">{x.l}</span>
            </li>
          ))}
        </motion.ul>
      </div>
    </section>
  );
}

function Home() {
  const { t } = usePrefs();
  return (
    <>
      <Hero />
      <TrustedBrands />
      <Benefits />
      <section className="bg-secondary/50 py-16 sm:py-24">
        <div className="container-x">
          <SectionHeading eyebrow="Our broadband plans" title={t("plansTitle")} sub="Every price already has 18% GST in it. The price you see is the price billed." />
          <PlansGrid />
        </div>
      </section>
      <section className="container-x py-16 sm:py-24"><CoverageChecker /></section>
      <section className="container-x pb-16 sm:pb-24">
        <div className="grid min-w-0 gap-2 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end sm:gap-4">
          <SectionHeading center={false} eyebrow="Internet tools" title="Test, check and troubleshoot" sub="Free tools that run in your browser using real public test services." />
          <Link to="/tools" className="mb-10 inline-flex items-center gap-1 font-bold text-primary hover:text-accent">View all tools <ArrowRight className="h-4 w-4" /></Link>
        </div>
        <ToolsGrid />
      </section>
      <SelfService />
      <AboutBlock />
      <SupportBlock />
    </>
  );
}
