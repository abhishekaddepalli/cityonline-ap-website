import { Link } from "@tanstack/react-router";
import { Mail, MapPin, Phone, MessageCircle, Sparkles } from "lucide-react";
import { CONTACT, LINKS, ext } from "@/lib/links";
import { Logo } from "./primitives";

const col = "text-sm text-navy-foreground/75 transition-colors hover:text-accent";

export function Footer() {
  return (
    <footer className="relative mt-10 bg-navy pb-[calc(5rem+env(safe-area-inset-bottom))] text-navy-foreground xl:pb-0">
      <div className="h-1 w-full bg-orange-gradient" />
      <div className="container-x grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1fr_1fr]">
        <div className="min-w-0">
          <Logo surface className="max-h-8 max-w-[220px]" />
          <p className="mt-5 max-w-xs text-sm text-navy-foreground/75">
            City Online AP Pvt Ltd — Kakinada's own fibre broadband. Our own network, our own people.
          </p>
          <address className="mt-5 flex gap-3 text-sm not-italic text-navy-foreground/80">
            <MapPin className="mt-0.5 h-4 w-4 shrink-0 text-accent" />
            <span>{CONTACT.address.map((l) => <span key={l} className="block">{l}</span>)}</span>
          </address>
        </div>
        <FooterCol title="Company">
          <Link to="/" className={col}>Home</Link>
          <Link to="/about" className={col}>About</Link>
          <Link to="/plans" className={col}>Broadband Plans</Link>
          <Link to="/coverage" className={col}>Coverage</Link>
        </FooterCol>
        <FooterCol title="Customer Support">
          <Link to="/help" className={col}>Help</Link>
          <Link to="/contact" className={col}>Contact</Link>
          <a href={LINKS.payBill} {...ext} className={col}>Pay Bill</a>
          <a href={LINKS.newConnection} {...ext} className={col}>New Connection</a>
        </FooterCol>
        <FooterCol title="Useful Tools">
          <Link to="/tools/speed-test" className={col}>Speed Test</Link>
          <Link to="/tools/ip-lookup" className={col}>IP Lookup</Link>
          <Link to="/tools/dns-lookup" className={col}>DNS Lookup</Link>
          <Link to="/tools/wifi-troubleshooter" className={col}>Wi-Fi Help</Link>
        </FooterCol>
        <FooterCol title="Legal">
          <a href={LINKS.terms} {...ext} className={col}>Terms & Conditions</a>
          <a href={LINKS.refund} {...ext} className={col}>Refund & Cancellation</a>
          <a href={LINKS.privacy} {...ext} className={col}>Privacy Policy</a>
        </FooterCol>
      </div>
      <div className="border-t border-navy-foreground/10">
        <div className="container-x flex flex-wrap gap-x-6 gap-y-3 py-6 text-sm">
          {CONTACT.phones.map((p) => (
            <a key={p.href} href={p.href} className="grid min-w-0 grid-cols-[auto_minmax(0,1fr)] items-center gap-2 text-navy-foreground/80 hover:text-accent">
              <Phone className="h-4 w-4 shrink-0 text-accent" /> <span>{p.display} <span className="text-navy-foreground/50">· {p.label}</span></span>
            </a>
          ))}
          <a href={LINKS.email} className="inline-flex items-center gap-2 text-navy-foreground/80 hover:text-accent">
            <Mail className="h-4 w-4 text-accent" /> {CONTACT.email}
          </a>
          <a href={LINKS.whatsapp} {...ext} className="inline-flex items-center gap-2 text-navy-foreground/80 hover:text-accent">
            <MessageCircle className="h-4 w-4 text-accent" /> WhatsApp
          </a>
        </div>
        <div className="container-x grid gap-4 pb-8 text-xs text-navy-foreground/50 sm:flex sm:flex-wrap sm:items-center sm:justify-between xl:pr-28">
          <span>© {new Date().getFullYear()} City Online AP Pvt Ltd. All rights reserved.</span>
          <span className="group inline-flex w-fit max-w-full items-center gap-2 rounded-full border border-navy-foreground/15 bg-navy-foreground/5 px-4 py-1.5 text-navy-foreground/70 transition-all duration-300 hover:border-accent/50 hover:bg-navy-foreground/10 hover:shadow-orange">
            <Sparkles className="h-3.5 w-3.5 shrink-0 text-accent transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110" />
            <span className="min-w-0">
              Designed by{" "}
              <span className="font-bold text-accent transition-colors group-hover:text-navy-foreground">
                Infiniforge Technologies
              </span>
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
