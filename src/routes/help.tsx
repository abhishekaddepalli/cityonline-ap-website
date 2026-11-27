import { createFileRoute, Link } from "@tanstack/react-router";
import { ChevronDown, ExternalLink } from "lucide-react";
import { useState } from "react";
import { btnClass, PageHero, Reveal } from "@/components/site/primitives";
import { SupportBlock } from "@/components/site/sections";
import { FAQS } from "@/components/site/chat-faq";
import { LINKS, ext } from "@/lib/links";
import { usePrefs } from "@/lib/prefs";
import { seo } from "@/lib/seo";
import { cn } from "@/lib/utils";

export const Route = createFileRoute("/help")({
  head: () => seo("Help & Support", "Get help with your City Online broadband: call, WhatsApp, troubleshooting guides and answers to common questions.", "/help"),
  component: Help,
});

function Help() {
  const { lang } = usePrefs();
  const [open, setOpen] = useState<string | null>(FAQS[0]?.id ?? null);
  return (
    <>
      <PageHero eyebrow="Support" title="How can we help?" sub="Faults are attended within 24 hours by our own team in Peddapuram & surroundings.">
        <div className="mt-6 flex flex-wrap gap-3">
          <Link to="/tools/wifi-troubleshooter" className={btnClass("orange")}>Internet not working?</Link>
          <a href={LINKS.help} {...ext} className={btnClass("ghostLight")}>Help on cityonlineap.com <ExternalLink className="h-4 w-4" /></a>
        </div>
      </PageHero>
      <SupportBlock />
      <section className="container-x max-w-3xl pb-16">
        <Reveal><h2 className="mb-6 text-2xl font-extrabold text-primary">Common questions</h2></Reveal>
        <div className="space-y-3">
          {FAQS.map((f) => (
            <div key={f.id} className="rounded-2xl border border-border bg-card shadow-soft">
              <button onClick={() => setOpen(open === f.id ? null : f.id)} aria-expanded={open === f.id} className="flex w-full items-center justify-between gap-4 p-5 text-left font-bold text-primary">
                {f.q[lang]} <ChevronDown className={cn("h-5 w-5 shrink-0 transition", open === f.id && "rotate-180")} />
              </button>
              {open === f.id && (
                <div className="space-y-1 px-5 pb-5 text-sm text-muted-foreground">
                  {f.a[lang].split("\n").map((l, i) => <p key={i}>{l.replace(/\*\*/g, "")}</p>)}
                  {f.links && <div className="flex flex-wrap gap-2 pt-3">{f.links.map((l) => <a key={l.href} href={l.href} {...(l.href.startsWith("http") ? ext : {})} className="rounded-full border border-accent/40 px-3 py-1 text-xs font-bold text-accent hover:bg-accent hover:text-accent-foreground">{l.label}</a>)}</div>}
                </div>
              )}
            </div>
          ))}
        </div>
      </section>
    </>
  );
}
