import { Link } from "@tanstack/react-router";
import { AnimatePresence, motion } from "framer-motion";
import {
  ArrowRight, Check, CreditCard, Crown, Gauge, Headphones, Infinity as InfinityIcon, MapPin, MessageCircle,
  Phone, PlugZap, Rocket, Search, ArrowUpDown, WifiOff, Layers, Building2, Users, Network, LifeBuoy, Wrench,
} from "lucide-react";
import { useState } from "react";
import { LINKS, PLANS, ext } from "@/lib/links";
import { usePrefs } from "@/lib/prefs";
import { useCms } from "@/lib/cms";
import { cn } from "@/lib/utils";
import { btnClass, Reveal, SectionHeading } from "./primitives";
import { TOOLS } from "./nav-data";
import tech from "@/assets/fibre-technician-kakinada.jpg.asset.json";

export function Benefits() {
  const items = [
    { icon: InfinityIcon, t: "Unlimited Data", d: "No FUP on any plan — stream, study and work without limits." },
    { icon: ArrowUpDown, t: "Symmetric Speeds", d: "Same upload and download speeds for calls, cloud and gaming." },
    { icon: Headphones, t: "Local Support", d: "A Kakinada team that answers the phone and comes to your street." },
    { icon: MapPin, t: "Wide Coverage", d: "Fibre across 40+ localities in Kakinada." },
  ];
  return (
    <section className="container-x py-16 sm:py-20">
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {items.map((x, i) => (
          <Reveal key={x.t} delay={i * 0.08}>
            <motion.div whileHover={{ y: -4 }} className="h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
              <motion.span
                initial={{ scale: 0.6, rotate: -12, opacity: 0 }}
                whileInView={{ scale: 1, rotate: 0, opacity: 1 }}
                viewport={{ once: true }}
                transition={{ type: "spring", delay: 0.15 + i * 0.08 }}
                className="grid h-12 w-12 place-items-center rounded-xl bg-secondary text-accent"
              >
                <x.icon className="h-6 w-6" />
              </motion.span>
              <h3 className="mt-5 text-lg font-bold text-primary">{x.t}</h3>
              <p className="mt-2 text-sm text-muted-foreground">{x.d}</p>
            </motion.div>
          </Reveal>
        ))}
      </div>
    </section>
  );
}

const planIcon = { standard: Gauge, premium: Crown, ultra: Rocket } as const;

export function PlansGrid({ showAll = true }: { showAll?: boolean }) {
  const { t } = usePrefs();
  const { plans } = useCms();
  const activePlans = plans && plans.length > 0 ? plans : PLANS;

  return (
    <>
      <div className="grid min-w-0 gap-8 pt-4 md:grid-cols-3 md:gap-6">
        {activePlans.map((p, i) => {
          const Icon = p.id === "standard" || i === 0 ? Gauge : p.id === "premium" || i === 1 ? Crown : Rocket;
          return (
            <Reveal key={p.id} delay={i * 0.1}>
              <motion.article
                whileHover={{ y: -6 }}
                className={cn(
                  "relative flex min-w-0 h-full flex-col rounded-[22px] p-5 transition-shadow sm:p-7",
                  p.popular ? "bg-hero text-navy-foreground shadow-lift ring-4 ring-accent/30 md:-my-3 md:py-10" : "border border-border bg-card shadow-soft hover:shadow-lift",
                )}
              >
                {p.popular && (
                  <span className="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-accent px-3 py-1 text-xs font-bold text-accent-foreground shadow-orange">★ Most Popular</span>
                )}
                <div className="flex items-center gap-3">
                  <span className={cn("grid h-10 w-10 place-items-center rounded-xl", p.popular ? "bg-navy-foreground/10 text-accent" : "bg-secondary text-accent")}><Icon className="h-5 w-5" /></span>
                  <h3 className={cn("text-xl font-bold", !p.popular && "text-primary")}>{p.name}</h3>
                </div>
                <p className={cn("mt-1 text-sm", p.popular ? "text-navy-foreground/70" : "text-muted-foreground")}>{p.note}</p>
                <p className="mt-6 text-accent"><span className="text-5xl font-extrabold tracking-tight">{p.speed}</span> <span className="text-lg font-bold">Mbps</span></p>
                <ul className={cn("mt-5 space-y-2 text-sm", p.popular ? "text-navy-foreground/85" : "text-foreground/80")}>
                  {(p.features && p.features.length > 0 ? p.features : ["Unlimited data, no FUP", "Same upload & download speed", "Faults attended in 24 hours"]).map((f) => (
                    <li key={f} className="flex gap-2"><Check className="mt-0.5 h-4 w-4 shrink-0 text-accent" />{f}</li>
                  ))}
                </ul>
                <div className={cn("mt-6 border-t pt-5", p.popular ? "border-navy-foreground/15" : "border-border")}>
                  <p className="flex flex-wrap items-baseline gap-x-1"><span className="text-4xl font-extrabold">₹{p.price}</span><span className={cn("text-sm", p.popular ? "text-navy-foreground/70" : "text-muted-foreground")}>/month</span></p>
                  <p className={cn("text-xs", p.popular ? "text-navy-foreground/60" : "text-muted-foreground")}>Including GST · {p.base}</p>
                </div>
                <a href={p.href} {...ext} className={btnClass(p.popular ? "orange" : "outline", "mt-6 w-full")}>
                  {t("getPlan")} <ArrowRight className="h-4 w-4" />
                </a>
              </motion.article>
            </Reveal>
          );
        })}
      </div>
      {showAll && (
        <div className="mt-8 text-center">
          <a href={LINKS.allPlans} {...ext} className="inline-flex items-center gap-2 font-bold text-primary hover:text-accent">
            View all broadband plans <ArrowRight className="h-4 w-4" />
          </a>
        </div>
      )}
    </>
  );
}

export const AREAS = [
  "Sarpavaram", "Ramanayyapeta", "Jagannaickpur", "Suryaraopeta", "Gandhinagar", "Bhanugudi",
  "Madhavapatnam", "Vakalapudi", "Turangi", "Indrapalem", "Ramaraopeta", "Achampeta", "Valasapakala", "Temple Street",
];

export function CoverageChecker() {
  const { t } = usePrefs();
  const { localities, submitEnquiry } = useCms();
  const allAreas = localities && localities.length > 0 ? localities : AREAS;
  const [q, setQ] = useState("");
  const [picked, setPicked] = useState<string | null>(null);
  const [phone, setPhone] = useState("");
  const [name, setName] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [errorMsg, setErrorMsg] = useState("");

  const suggestions = q.length > 0 ? allAreas.filter((a) => a.toLowerCase().includes(q.toLowerCase())).slice(0, 5) : [];
  const submit = (v: string) => {
    if (v.trim()) {
      setPicked(v.trim());
      setSubmitted(false);
      setErrorMsg("");
    }
  };

  const handleQuickEnquiry = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!phone.trim()) {
      setErrorMsg("Please enter your mobile number");
      return;
    }
    setSubmitting(true);
    setErrorMsg("");
    const res = await submitEnquiry({
      customer_name: name.trim() || "Kakinada Resident",
      mobile_number: phone.trim(),
      locality: picked || q,
      enquiry_type: "coverage",
      notes: "Coverage availability enquiry from website",
    });
    setSubmitting(false);
    if (res.success) {
      setSubmitted(true);
    } else {
      setErrorMsg(res.message || "Failed to submit enquiry. Please call us.");
    }
  };

  return (
    <div className="relative overflow-hidden rounded-[22px] border border-border bg-card p-6 shadow-lift sm:p-10">
      <div className="pointer-events-none absolute -right-24 -top-24 h-64 w-64 rounded-full bg-accent/10 blur-3xl" />
      <div className="relative grid gap-8 lg:grid-cols-[1.1fr_1fr] lg:items-center">
        <div className="min-w-0">
          <h2 className="text-3xl font-extrabold text-primary sm:text-4xl">{t("coverageTitle")}</h2>
          <p className="mt-3 text-muted-foreground">We cover 40+ localities across Kakinada. Enter your locality and we'll help you send an enquiry.</p>
          <form className="relative mt-6" onSubmit={(e) => { e.preventDefault(); submit(q); }}>
            <label htmlFor="locality" className="sr-only">Your locality</label>
            <div className="flex flex-col gap-3 sm:flex-row">
              <div className="relative min-w-0 flex-1">
                <Search className="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
                <input id="locality" value={q} autoComplete="off" onChange={(e) => { setQ(e.target.value); setPicked(null); }} placeholder="Enter your area / locality" className="h-12 w-full rounded-xl border border-input bg-background pl-12 pr-4 text-base outline-none focus:border-ring focus:ring-2 focus:ring-ring/30" />
                {suggestions.length > 0 && !picked && (
                  <ul className="absolute inset-x-0 top-full z-10 mt-1 overflow-hidden rounded-xl border border-border bg-popover shadow-lift">
                    {suggestions.map((s) => (
                      <li key={s}><button type="button" onClick={() => { setQ(s); submit(s); }} className="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm hover:bg-secondary"><MapPin className="h-4 w-4 text-accent" />{s}, Kakinada</button></li>
                    ))}
                  </ul>
                )}
              </div>
              <button type="submit" className={btnClass("orange", "h-12")}>Check Availability</button>
            </div>
          </form>
          <div className="mt-4 flex flex-wrap gap-2">
            {allAreas.slice(0, 6).map((a) => (
              <button key={a} onClick={() => { setQ(a); submit(a); }} className={cn("rounded-full border px-3 py-1 text-xs font-semibold transition", picked === a ? "border-accent bg-accent text-accent-foreground" : "border-border text-muted-foreground hover:border-primary hover:text-primary")}>{a}</button>
            ))}
          </div>
        </div>
        <div className="min-w-0 min-h-[220px] rounded-2xl bg-secondary p-4 sm:p-6">
          <AnimatePresence mode="wait">
            {picked ? (
              <motion.div key={picked} initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}>
                <MapPin className="h-8 w-8 text-accent" />
                <h3 className="mt-3 text-xl font-bold text-primary">{picked}, Kakinada</h3>
                <p className="mt-2 text-sm text-secondary-foreground/80">
                  We have high-density fibre coverage in {picked}. Enter your details below for instant verification by our local team:
                </p>

                {submitted ? (
                  <div className="mt-4 rounded-xl bg-card p-4 border border-emerald-200">
                    <p className="font-bold text-emerald-700 text-sm">✓ Enquiry Sent Successfully!</p>
                    <p className="text-xs text-muted-foreground mt-1">Our Kakinada branch has received your request. A technician will contact you on <strong>{phone}</strong> within 2 hours.</p>
                  </div>
                ) : (
                  <form onSubmit={handleQuickEnquiry} className="mt-4 space-y-2.5">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      <input
                        type="text"
                        placeholder="Your Name (optional)"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        className="h-10 rounded-lg border border-input bg-card px-3 text-xs outline-none focus:border-accent"
                      />
                      <input
                        type="tel"
                        required
                        placeholder="Mobile Number *"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        className="h-10 rounded-lg border border-input bg-card px-3 text-xs outline-none focus:border-accent"
                      />
                    </div>
                    {errorMsg && <p className="text-xs text-red-500 font-semibold">{errorMsg}</p>}
                    <div className="flex flex-wrap items-center gap-2 pt-1">
                      <button type="submit" disabled={submitting} className={btnClass("orange", "h-10 text-xs")}>
                        {submitting ? "Sending..." : "Request Call Back"}
                      </button>
                      <a href={LINKS.newConnection} {...ext} className={btnClass("outline", "h-10 text-xs")}>
                        Online Portal <ArrowRight className="h-3.5 w-3.5" />
                      </a>
                    </div>
                  </form>
                )}
                
                <div className="mt-4 pt-3 border-t border-border flex items-center justify-between text-xs text-muted-foreground">
                  <span>Direct booking line:</span>
                  <a href={LINKS.tel.newConnections} className="font-bold text-primary hover:text-accent flex items-center gap-1">
                    <Phone className="h-3 w-3" /> 97009 28928
                  </a>
                </div>
              </motion.div>
            ) : (
              <motion.div key="empty" initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} className="flex h-full flex-col justify-center">
                <Network className="h-8 w-8 text-primary" />
                <p className="mt-3 font-bold text-primary">Fibre across Kakinada</p>
                <p className="mt-1 text-sm text-secondary-foreground/80">Choose or type your locality to see the next step. Final availability is always confirmed by City Online AP.</p>
              </motion.div>
            )}
          </AnimatePresence>
        </div>
      </div>
    </div>
  );
}

export function ToolsGrid() {
  return (
    <div className="grid min-w-0 gap-3 min-[360px]:grid-cols-2 sm:gap-4 lg:grid-cols-3">
      {TOOLS.map((x, i) => (
        <Reveal key={x.to} delay={i * 0.05}>
          <Link to={x.to} className="group flex h-full flex-col rounded-2xl border border-border bg-card p-4 shadow-soft transition hover:-translate-y-1 hover:border-accent/50 hover:shadow-lift sm:p-6">
            <span className="grid h-11 w-11 place-items-center rounded-xl bg-secondary text-accent transition group-hover:bg-accent group-hover:text-accent-foreground"><x.icon className="h-5 w-5" /></span>
            <h3 className="mt-4 font-bold text-primary">{x.label}</h3>
            <p className="mt-1 text-xs text-muted-foreground sm:text-sm">{x.desc}</p>
            <span className="mt-auto inline-flex items-center gap-1 pt-4 text-sm font-bold text-accent">Open <ArrowRight className="h-4 w-4 transition group-hover:translate-x-1" /></span>
          </Link>
        </Reveal>
      ))}
    </div>
  );
}

export function SelfService() {
  const items = [
    { icon: CreditCard, t: "Pay Your Bill", d: "UPI, card, or your usual agent", href: LINKS.payBill },
    { icon: PlugZap, t: "Get New Connection", d: "Book fibre for your home or office", href: LINKS.newConnection },
    { icon: WifiOff, t: "Internet Not Working?", d: "Fix it fast or reach our team", to: "/tools/wifi-troubleshooter" as const },
    { icon: Headphones, t: "Contact Support", d: "Phone, WhatsApp and email", to: "/contact" as const },
    { icon: MapPin, t: "Check Coverage", d: "40+ localities in Kakinada", to: "/coverage" as const },
    { icon: Layers, t: "View Plans", d: "From ₹590/month incl. GST", to: "/plans" as const },
  ];
  return (
    <section className="bg-hero py-16 text-navy-foreground sm:py-20">
      <div className="container-x">
        <Reveal className="mb-10 max-w-2xl">
          <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">Self-service</p>
          <h2 className="mt-3 text-3xl font-extrabold sm:text-4xl">Manage your connection, anytime.</h2>
        </Reveal>
        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          {items.map((x, i) => {
            const inner = (
              <>
                <span className="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-accent text-accent-foreground"><x.icon className="h-5 w-5" /></span>
                <span className="min-w-0 flex-1"><span className="block font-bold">{x.t}</span><span className="block text-sm text-navy-foreground/70">{x.d}</span></span>
                <ArrowRight className="h-5 w-5 shrink-0 text-accent transition group-hover:translate-x-1" />
              </>
            );
            const cls = "group flex items-center gap-4 rounded-2xl border border-navy-foreground/10 bg-navy-foreground/5 p-5 backdrop-blur transition hover:bg-navy-foreground/10";
            return (
              <Reveal key={x.t} delay={i * 0.05}>
                {"href" in x && x.href ? <a href={x.href} {...ext} className={cls}>{inner}</a> : x.to ? <Link to={x.to} className={cls}>{inner}</Link> : null}
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}

export function AboutBlock({ withLink = true }: { withLink?: boolean }) {
  const stats = [
    { n: "10+", l: "years in Kakinada" },
    { n: "40+", l: "localities covered" },
    { n: "24 hrs", l: "fault response" },
  ];
  return (
    <section className="container-x grid gap-10 py-16 sm:py-24 lg:grid-cols-2 lg:items-center">
      <Reveal className="relative">
        <img src={tech.url} alt="A City Online technician running fibre above a Kakinada street" loading="lazy" width={1024} height={576} className="aspect-[4/3] w-full rounded-[22px] object-cover shadow-lift" />
        <div className="absolute -bottom-6 left-4 right-4 grid grid-cols-3 gap-2 rounded-2xl border border-border bg-card p-4 shadow-lift sm:left-8 sm:right-8">
          {stats.map((s) => (
            <div key={s.l} className="text-center"><p className="text-xl font-extrabold text-accent sm:text-2xl">{s.n}</p><p className="text-[11px] text-muted-foreground sm:text-xs">{s.l}</p></div>
          ))}
        </div>
      </Reveal>
      <Reveal delay={0.1} className="pt-6 lg:pt-0">
        <p className="text-xs font-bold uppercase tracking-[0.22em] text-accent">About City Online AP</p>
        <h2 className="mt-3 text-3xl font-extrabold text-primary sm:text-4xl">Our own network, our own people.</h2>
        <p className="mt-4 text-lg text-muted-foreground">When it breaks, we are already on your street. No franchise in between, no call centre in another state. The same team lays the fibre, answers the phone and climbs the pole.</p>
        <ul className="mt-6 grid gap-3 sm:grid-cols-2">
          {[
            { i: Network, t: "Company-owned fibre network" },
            { i: Users, t: "Local Kakinada team" },
            { i: Building2, t: "Homes and businesses" },
            { i: LifeBuoy, t: "Office on Temple Street" },
          ].map((x) => (
            <li key={x.t} className="flex items-center gap-3 rounded-xl bg-secondary p-3 text-sm font-semibold text-secondary-foreground"><x.i className="h-5 w-5 shrink-0 text-accent" />{x.t}</li>
          ))}
        </ul>
        {withLink && <Link to="/about" className={btnClass("blue", "mt-8")}>More about us <ArrowRight className="h-4 w-4" /></Link>}
      </Reveal>
    </section>
  );
}

export function SupportBlock() {
  const items = [
    { icon: Phone, t: "Call support", d: "+91 8844 6668999", href: LINKS.tel.support },
    { icon: MessageCircle, t: "WhatsApp support", d: "+91 97009 28928", href: LINKS.whatsapp },
    { icon: LifeBuoy, t: "Help page", d: "Answers to common questions", href: LINKS.help },
    { icon: Wrench, t: "Troubleshooting guide", d: "Fix Wi-Fi and connection issues", to: "/tools/wifi-troubleshooter" as const },
    { icon: PlugZap, t: "New connection enquiry", d: "+91 97009 28928", href: LINKS.newConnection },
  ];
  return (
    <section className="container-x py-16 sm:py-20">
      <SectionHeading eyebrow="Customer support" title="Real people in Kakinada, ready to help." sub="Faults are attended within 24 hours. Reach us the way that suits you." />
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        {items.map((x, i) => {
          const cls = "group flex h-full flex-col rounded-2xl border border-border bg-card p-5 shadow-soft transition hover:-translate-y-1 hover:shadow-lift";
          const inner = (
            <>
              <span className="grid h-11 w-11 place-items-center rounded-xl bg-secondary text-accent"><x.icon className="h-5 w-5" /></span>
              <h3 className="mt-4 font-bold text-primary">{x.t}</h3>
              <p className="mt-1 text-sm text-muted-foreground">{x.d}</p>
            </>
          );
          return (
            <Reveal key={x.t} delay={i * 0.05}>
              {"href" in x && x.href ? <a href={x.href} {...(x.href.startsWith("http") ? ext : {})} className={cls}>{inner}</a> : x.to ? <Link to={x.to} className={cls}>{inner}</Link> : null}
            </Reveal>
          );
        })}
      </div>
    </section>
  );
}
