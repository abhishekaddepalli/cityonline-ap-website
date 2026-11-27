import { LINKS } from "@/lib/links";
import type { Lang } from "@/lib/prefs";

export type Faq = { id: string; q: Record<Lang, string>; a: Record<Lang, string>; links?: { label: string; href: string }[]; keys: string[] };

export const FAQS: Faq[] = [
  {
    id: "plans",
    keys: ["plan", "price", "cost", "mbps", "speed", "ప్లాన్", "ధర"],
    q: { en: "What broadband plans are available?", te: "ఏ బ్రాడ్‌బ్యాండ్ ప్లాన్లు ఉన్నాయి?" },
    a: {
      en: "All plans are unlimited with no FUP, and prices include 18% GST:\n\n• **Standard** — 60 Mbps — ₹590/month\n• **Premium** — 100 Mbps — ₹767/month (most popular)\n• **Ultra** — 150 Mbps — ₹1,180/month\n\nUpload and download speeds are the same.",
      te: "అన్ని ప్లాన్లు అపరిమితం (FUP లేదు), ధరల్లో 18% GST కలిపి ఉంది:\n\n• **Standard** — 60 Mbps — ₹590/నెల\n• **Premium** — 100 Mbps — ₹767/నెల\n• **Ultra** — 150 Mbps — ₹1,180/నెల",
    },
    links: [
      { label: "Standard", href: LINKS.plan.standard },
      { label: "Premium", href: LINKS.plan.premium },
      { label: "Ultra", href: LINKS.plan.ultra },
    ],
  },
  {
    id: "new",
    keys: ["new", "connection", "install", "book", "కొత్త", "కనెక్షన్"],
    q: { en: "How do I get a new connection?", te: "కొత్త కనెక్షన్ ఎలా పొందాలి?" },
    a: {
      en: "Book online using the new connection form, or call **+91 97009 28928**. Our team will confirm availability at your address and arrange installation.",
      te: "ఆన్‌లైన్ ఫారమ్ ద్వారా బుక్ చేయండి లేదా **+91 97009 28928** కు కాల్ చేయండి. మా బృందం మీ చిరునామాలో లభ్యతను నిర్ధారించి ఇన్‌స్టాల్ చేస్తుంది.",
    },
    links: [
      { label: "Book new connection", href: LINKS.newConnection },
      { label: "Call 97009 28928", href: LINKS.tel.newConnections },
    ],
  },
  {
    id: "bill",
    keys: ["bill", "pay", "payment", "upi", "recharge", "బిల్", "చెల్లి"],
    q: { en: "How can I pay my bill?", te: "నా బిల్ ఎలా చెల్లించాలి?" },
    a: {
      en: "Pay online on the customer portal using UPI or card, or through your usual agent.",
      te: "కస్టమర్ పోర్టల్‌లో UPI లేదా కార్డ్ ద్వారా, లేదా మీ ఏజెంట్ ద్వారా చెల్లించవచ్చు.",
    },
    links: [{ label: "Open customer portal", href: LINKS.payBill }],
  },
  {
    id: "down",
    keys: ["not working", "down", "slow", "wifi", "wi-fi", "router", "no internet", "పని", "నెమ్మది"],
    q: { en: "My internet is not working.", te: "నా ఇంటర్నెట్ పని చేయడం లేదు." },
    a: {
      en: "Try these steps:\n\n1. Check the router lights — the PON/LOS light should not be red.\n2. Switch the router off, wait 30 seconds, and switch it on again.\n3. Check the fibre cable is not bent or loose.\n4. Test with a cable or stand closer to the router.\n\nStill down? Contact us — faults are attended within 24 hours.",
      te: "ఇవి ప్రయత్నించండి:\n\n1. రౌటర్ లైట్లు చూడండి — LOS లైట్ ఎరుపుగా ఉండకూడదు.\n2. రౌటర్ ఆఫ్ చేసి 30 సెకన్ల తర్వాత ఆన్ చేయండి.\n3. ఫైబర్ కేబుల్ వంగలేదని చూడండి.\n\nఇంకా పని చేయకపోతే మమ్మల్ని సంప్రదించండి — 24 గంటల్లో సమస్య పరిష్కరిస్తాం.",
    },
    links: [
      { label: "WhatsApp support", href: LINKS.whatsapp },
      { label: "Call support", href: LINKS.tel.support },
    ],
  },
  {
    id: "area",
    keys: ["area", "available", "coverage", "locality", "ప్రాంతం", "కవరేజ్"],
    q: { en: "Is broadband available in my area?", te: "నా ప్రాంతంలో బ్రాడ్‌బ్యాండ్ ఉందా?" },
    a: {
      en: "We cover Peddapuram, Samalkot and surrounding areas. Final availability depends on your exact address, so please send an enquiry and our team will confirm.",
      te: "పెద్దాపురం, సామర్లకోట మరియు పరిసర ప్రాంతాలలో సేవ అందిస్తున్నాం. మీ చిరునామాను బట్టి లభ్యతను మా బృందం నిర్ధారిస్తుంది.",
    },
    links: [{ label: "Send area enquiry", href: LINKS.newConnection }],
  },
];

export const CONTACT_ANSWER: Record<Lang, string> = {
  en: "I'm a FAQ assistant with set answers, so I may not cover everything. You can reach our team directly:",
  te: "నేను నిర్దిష్ట సమాధానాలతో ఉన్న FAQ సహాయకుడిని. మా బృందాన్ని నేరుగా సంప్రదించండి:",
};

export function matchFaq(text: string): Faq | undefined {
  const t = text.toLowerCase();
  return FAQS.find((f) => f.keys.some((k) => t.includes(k)));
}
