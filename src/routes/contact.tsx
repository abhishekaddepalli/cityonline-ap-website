import { createFileRoute } from "@tanstack/react-router";
import { Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import { PageHero, Reveal } from "@/components/site/primitives";
import { CONTACT, LINKS, ext } from "@/lib/links";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/contact")({
  head: () => seo("Contact Us", "Call, WhatsApp or email City Online AP, or visit our office on Temple Street, Kakinada.", "/contact"),
  component: Contact,
});

function Contact() {
  const card = "flex items-start gap-4 rounded-2xl border border-border bg-card p-6 shadow-soft transition hover:-translate-y-1 hover:shadow-lift";
  const ic = "grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-secondary text-accent";
  return (
    <>
      <PageHero eyebrow="Contact" title="Talk to us" sub="A real person in Kakinada answers. For faults, WhatsApp is often the quickest." />
      <section className="container-x grid gap-4 py-16 md:grid-cols-2 lg:grid-cols-3">
        {CONTACT.phones.map((p, i) => (
          <Reveal key={p.href} delay={i * 0.05}>
            <a href={p.href} className={card}><span className={ic}><Phone className="h-5 w-5" /></span><span className="min-w-0"><span className="block text-sm text-muted-foreground">{p.label}</span><span className="block text-lg font-bold text-primary">{p.display}</span></span></a>
          </Reveal>
        ))}
        <Reveal delay={0.15}><a href={LINKS.whatsapp} {...ext} className={card}><span className={ic}><MessageCircle className="h-5 w-5" /></span><span className="min-w-0"><span className="block text-sm text-muted-foreground">WhatsApp</span><span className="block text-lg font-bold text-primary">+91 97009 28928</span></span></a></Reveal>
        <Reveal delay={0.2}><a href={LINKS.email} className={card}><span className={ic}><Mail className="h-5 w-5" /></span><span className="min-w-0"><span className="block text-sm text-muted-foreground">Email</span><span className="block break-all text-lg font-bold text-primary">{CONTACT.email}</span></span></a></Reveal>
        <Reveal delay={0.25}>
          <div className={card}><span className={ic}><MapPin className="h-5 w-5" /></span><address className="min-w-0 not-italic"><span className="block text-sm text-muted-foreground">Office</span>{CONTACT.address.map((l) => <span key={l} className="block font-semibold text-primary">{l}</span>)}</address></div>
        </Reveal>
      </section>
      <section className="container-x pb-16">
        <iframe title="City Online AP office on Temple Street, Kakinada" loading="lazy" className="h-80 w-full rounded-[22px] border border-border" src="https://www.google.com/maps?q=Temple+Street+Kakinada+533001&output=embed" />
      </section>
    </>
  );
}
