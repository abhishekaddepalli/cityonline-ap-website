import { createFileRoute } from "@tanstack/react-router";
import { Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import { PageHero, Reveal } from "@/components/site/primitives";
import { CONTACT, LINKS, ext } from "@/lib/links";
import { useCms } from "@/lib/cms";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/contact")({
  head: () => seo("Contact Us", "Call, WhatsApp or email City Online AP, or visit our office in Peddapuram, Andhra Pradesh.", "/contact"),
  component: Contact,
});

function Contact() {
  const { settings } = useCms();
  const card = "flex items-start gap-4 rounded-2xl border border-border bg-card p-6 shadow-soft transition hover:-translate-y-1 hover:shadow-lift";
  const ic = "grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-secondary text-accent";

  const targetRegion = settings?.target_location || "Peddapuram & Surroundings";
  const mapQuery = encodeURIComponent(settings?.office_address_city || "Peddapuram Andhra Pradesh 533437");

  const phones = [
    { label: "New Connections", display: settings?.phone_new_connections || "+91 97009 28928", href: `tel:${(settings?.phone_new_connections || "+919700928928").replace(/\s/g, "")}` },
    { label: "Support", display: settings?.phone_support || "+91 8844 6668999", href: `tel:${(settings?.phone_support || "+9188446668999").replace(/\s/g, "")}` },
    { label: "Office", display: settings?.phone_office || "+91 8844 2383333", href: `tel:${(settings?.phone_office || "+918844238333").replace(/\s/g, "")}` },
  ];

  const waLeadNumber = (settings?.whatsapp_enquiries_number || settings?.whatsapp_number || "919700928928").replace(/\D/g, "");
  const waUrl = `https://wa.me/${waLeadNumber}?text=${encodeURIComponent(`Hello City Online AP! I would like to get broadband in ${targetRegion}.`)}`;

  return (
    <>
      <PageHero eyebrow="Contact" title="Talk to us" sub={`A real person in ${targetRegion} answers. For new connections & faults, WhatsApp is often the quickest.`} />
      <section className="container-x grid gap-4 py-16 md:grid-cols-2 lg:grid-cols-3">
        {phones.map((p, i) => (
          <Reveal key={p.href} delay={i * 0.05}>
            <a href={p.href} className={card}><span className={ic}><Phone className="h-5 w-5" /></span><span className="min-w-0"><span className="block text-sm text-muted-foreground">{p.label}</span><span className="block text-lg font-bold text-primary">{p.display}</span></span></a>
          </Reveal>
        ))}
        <Reveal delay={0.15}>
          <a href={waUrl} {...ext} className={card}>
            <span className={ic}><MessageCircle className="h-5 w-5 text-emerald-600" /></span>
            <span className="min-w-0"><span className="block text-sm text-muted-foreground">WhatsApp Direct</span><span className="block text-lg font-bold text-emerald-600">+{waLeadNumber}</span></span>
          </a>
        </Reveal>
        <Reveal delay={0.2}>
          <a href={`mailto:${settings?.email_support || CONTACT.email}`} className={card}>
            <span className={ic}><Mail className="h-5 w-5" /></span>
            <span className="min-w-0"><span className="block text-sm text-muted-foreground">Email</span><span className="block break-all text-lg font-bold text-primary">{settings?.email_support || CONTACT.email}</span></span>
          </a>
        </Reveal>
        <Reveal delay={0.25}>
          <div className={card}>
            <span className={ic}><MapPin className="h-5 w-5" /></span>
            <address className="min-w-0 not-italic">
              <span className="block text-sm text-muted-foreground">Local Office</span>
              <span className="block font-semibold text-primary">{settings?.office_address_line1 || "Main Road, Near Kacheri Center / RTC Complex"}</span>
              <span className="block font-semibold text-primary">{settings?.office_address_city || "Peddapuram 533437, Andhra Pradesh"}</span>
            </address>
          </div>
        </Reveal>
      </section>
      <section className="container-x pb-16">
        <iframe title={`City Online AP office in ${targetRegion}`} loading="lazy" className="h-80 w-full rounded-[22px] border border-border" src={`https://www.google.com/maps?q=${mapQuery}&output=embed`} />
      </section>
    </>
  );
}
