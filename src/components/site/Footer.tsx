import { Link } from "@tanstack/react-router";
import { Mail, MapPin, Phone, MessageCircle, Sparkles, ShieldCheck, Zap } from "lucide-react";
import { CONTACT, LINKS, ext } from "@/lib/links";
import { useCms } from "@/lib/cms";
import { Logo } from "./primitives";

const col = "text-sm text-navy-foreground/75 transition-colors hover:text-accent";

export function Footer() {
  const { settings } = useCms();

  const devText = settings?.developer_credit_text || "Infiniforge Technologies";
  const devUrl = settings?.developer_credit_url || "https://infiniforge.cloud";
  const copyright = settings?.copyright_text || `© ${new Date().getFullYear()} City Online AP Pvt Ltd. All rights reserved.`;

  const addr1 = settings?.address_line1 || CONTACT.address[0] || "City Broadband Services";
  const addr2 = settings?.address_line2 || CONTACT.address[1] || "#34-1-6, Temple Street, Opp. Joyalukas Jewellers";
  const addrCity = settings?.address_city || CONTACT.address[2] || "Peddapuram 533437, Andhra Pradesh";

  const template = settings?.footer_template || "modern-gradient";

  return (
    <footer className="relative mt-12 bg-navy pb-[calc(5rem+env(safe-area-inset-bottom))] text-navy-foreground xl:pb-0">
      {/* Glowing Top Border Accent */}
      <div className="h-1.5 w-full bg-gradient-to-r from-brand-blue via-accent to-orange-400" />

      <div className="container-x grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1fr_1fr]">
        <div className="min-w-0">
          <Logo type="footer" surface className="max-h-8 max-w-[220px]" />
          <p className="mt-5 max-w-xs text-sm text-navy-foreground/75 leading-relaxed">
            City Online AP Pvt Ltd — Peddapuram's own fibre broadband for 10+ years. Our own network, our own local people.
          </p>

          <div className="mt-4 flex items-center gap-2 text-xs font-semibold text-accent">
            <ShieldCheck className="h-4 w-4" />
            <span>Govt. Regd. Telecom Provider in AP</span>
          </div>

          <address className="mt-4 flex gap-3 text-sm not-italic text-navy-foreground/80">
            <MapPin className="mt-0.5 h-4 w-4 shrink-0 text-accent" />
            <span>
              <span className="block font-semibold">{addr1}</span>
              <span className="block">{addr2}</span>
              <span className="block text-navy-foreground/60">{addrCity}</span>
            </span>
          </address>
        </div>

        <FooterCol title="Company">
          <Link to="/" className={col}>Home</Link>
          <Link to="/about" className={col}>About Us</Link>
          <Link to="/plans" className={col}>Broadband Plans</Link>
          <Link to="/coverage" className={col}>Coverage Areas</Link>
          <a href={LINKS.speedtest} {...ext} className={col}>Speed Test</a>
        </FooterCol>

        <FooterCol title="Customer Support">
          <Link to="/help" className={col}>Help &amp; FAQs</Link>
          <Link to="/contact" className={col}>Contact Team</Link>
          <a href={LINKS.payBill} {...ext} className={col}>Pay Bill Online</a>
          <a href={LINKS.newConnection} {...ext} className={col}>New Connection</a>
          <Link to="/tools/wifi-troubleshooter" className={col}>Wi-Fi Diagnostics</Link>
        </FooterCol>

        <FooterCol title="Useful Tools">
          <Link to="/tools/speed-test" className={col}>Speed Test</Link>
          <Link to="/tools/ip-lookup" className={col}>My Public IP</Link>
          <Link to="/tools/dns-lookup" className={col}>DNS Lookup</Link>
          <Link to="/tools/ping-test" className={col}>Ping &amp; Latency</Link>
          <Link to="/tools/bandwidth-calculator" className={col}>Bandwidth Calc</Link>
        </FooterCol>

        <FooterCol title="Legal &amp; Policy">
          <a href={LINKS.terms} {...ext} className={col}>Terms &amp; Conditions</a>
          <a href={LINKS.refund} {...ext} className={col}>Refund Policy</a>
          <a href={LINKS.privacy} {...ext} className={col}>Privacy Policy</a>
          <div className="mt-3 flex items-center gap-1.5 text-xs text-navy-foreground/50">
            <Zap className="h-3.5 w-3.5 text-accent" />
            <span>24-Hour Fault Response</span>
          </div>
        </FooterCol>
      </div>

      <div className="border-t border-navy-foreground/10 bg-navy/60">
        <div className="container-x flex flex-wrap gap-x-6 gap-y-3 py-6 text-sm">
          {CONTACT.phones.map((p) => (
            <a key={p.href} href={p.href} className="grid min-w-0 grid-cols-[auto_minmax(0,1fr)] items-center gap-2 text-navy-foreground/80 hover:text-accent transition-colors">
              <Phone className="h-4 w-4 shrink-0 text-accent" /> <span>{p.display} <span className="text-navy-foreground/50">· {p.label}</span></span>
            </a>
          ))}
          <a href={LINKS.email} className="inline-flex items-center gap-2 text-navy-foreground/80 hover:text-accent transition-colors">
            <Mail className="h-4 w-4 text-accent" /> {settings?.email_support || CONTACT.email}
          </a>
          <a href={LINKS.whatsapp} {...ext} className="inline-flex items-center gap-2 text-navy-foreground/80 hover:text-accent transition-colors">
            <MessageCircle className="h-4 w-4 text-accent" /> WhatsApp Support
          </a>
        </div>

        <div className="container-x grid gap-4 pb-8 text-xs text-navy-foreground/60 sm:flex sm:flex-wrap sm:items-center sm:justify-between xl:pr-28">
          <span>{copyright}</span>

          {/* Developer Credit with Clickable Infiniforge Technologies Link */}
          <span className="group inline-flex w-fit max-w-full items-center gap-2 rounded-full border border-navy-foreground/15 bg-navy-foreground/5 px-4 py-1.5 text-navy-foreground/75 transition-all duration-300 hover:border-accent/50 hover:bg-navy-foreground/10 hover:shadow-orange">
            <Sparkles className="h-3.5 w-3.5 shrink-0 text-accent transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" />
            <span className="min-w-0">
              Designed &amp; Developed by{" "}
              <a
                href={devUrl}
                target="_blank"
                rel="noopener noreferrer"
                title="Infiniforge Technologies — Cloud, Web & Software Engineering"
                className="font-bold text-accent hover:text-white transition-colors underline-offset-4 hover:underline"
              >
                {devText}
              </a>
            </span>
          </span>
        </div>
      </div>
    </footer>
  );
}

function FooterCol({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div>
      <h3 className="text-sm font-bold uppercase tracking-widest text-navy-foreground">{title}</h3>
      <div className="mt-4 flex flex-col gap-3">{children}</div>
    </div>
  );
}
