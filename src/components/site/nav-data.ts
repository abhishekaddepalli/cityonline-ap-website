import { Activity, Calculator, Gauge, Globe, MapPin, Server, Wifi, LifeBuoy, Phone, Info, MessageCircle } from "lucide-react";
import { LINKS } from "@/lib/links";

export const TOOLS = [
  { to: "/tools/speed-test", label: "Speed Test", desc: "Download, upload, latency & jitter", icon: Gauge },
  { to: "/tools/ip-lookup", label: "IP Lookup", desc: "See your public IP address", icon: MapPin },
  { to: "/tools/dns-lookup", label: "DNS Lookup", desc: "Query DNS records for any domain", icon: Server },
  { to: "/tools/ping-test", label: "Ping Test", desc: "Approximate request latency", icon: Activity },
  { to: "/tools/wifi-troubleshooter", label: "Wi-Fi Troubleshooter", desc: "Fix common Wi-Fi problems", icon: Wifi },
  { to: "/tools/bandwidth-calculator", label: "Bandwidth Calculator", desc: "Estimate download times", icon: Calculator },
] as const;

export const SUPPORT_INTERNAL = [
  { to: "/help", label: "Help & Support", desc: "Guides and troubleshooting", icon: LifeBuoy },
  { to: "/contact", label: "Contact Us", desc: "Phone, email & office", icon: Phone },
  { to: "/about", label: "About Us", desc: "Our own network, our own people", icon: Info },
] as const;

export const SUPPORT_EXTERNAL = [
  { href: LINKS.whatsapp, label: "WhatsApp Support", icon: MessageCircle },
  { href: LINKS.help, label: "Help on cityonlineap.com", icon: Globe },
] as const;
