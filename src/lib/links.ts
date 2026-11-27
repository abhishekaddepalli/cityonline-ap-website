// Single source of truth for every existing City Online AP destination.
export const LINKS = {
  mainSite: "https://cityonlineap.com/",
  payBill: "https://customer.cityonlineap.com/",
  newConnection: "https://customer.cityonlineap.com/book-connection?src=site",
  plan: {
    standard: "https://customer.cityonlineap.com/book-connection?plan=standard&src=site",
    premium: "https://customer.cityonlineap.com/book-connection?plan=premium&src=site",
    ultra: "https://customer.cityonlineap.com/book-connection?plan=ultra&src=site",
  },
  allPlans: "https://cityonlineap.com/plans",
  help: "https://cityonlineap.com/help",
  terms: "https://cityonlineap.com/terms-and-conditions",
  refund: "https://cityonlineap.com/return-policy",
  privacy: "https://cityonlineap.com/privacy-policy",
  whatsapp:
    "https://wa.me/919700928928?text=Hi%2C%20my%20internet%20is%20not%20working.%20Name%3A%20___%20Area%3A%20___",
  tel: {
    newConnections: "tel:+919700928928",
    support: "tel:+918844666999",
    office: "tel:+918844238333",
  },
  email: "mailto:citybroadbandkkd@gmail.com",
  traceroute: "https://tools.keycdn.com/traceroute",
  portChecker: "https://www.yougetsignal.com/tools/open-ports/",
} as const;

export const CONTACT = {
  phones: [
    { label: "New Connections", display: "+91 97009 28928", href: LINKS.tel.newConnections },
    { label: "Support", display: "+91 8844 6668999", href: LINKS.tel.support },
    { label: "Office", display: "+91 8844 2383333", href: LINKS.tel.office },
  ],
  email: "citybroadbandkkd@gmail.com",
  address: [
    "City Broadband Services",
    "Main Road, Near Kacheri Center / RTC Complex,",
    "Peddapuram 533437, Andhra Pradesh",
  ],
};

export const PLANS = [
  { id: "standard", name: "Standard", speed: 60, price: "590", base: "₹500 + 18% GST", note: "Two or three phones and a TV", href: LINKS.plan.standard, popular: false },
  { id: "premium", name: "Premium", speed: 100, price: "767", base: "₹650 + 18% GST", note: "Most homes in Peddapuram pick this", href: LINKS.plan.premium, popular: true },
  { id: "ultra", name: "Ultra", speed: 150, price: "1,180", base: "₹1,000 + 18% GST", note: "Work from home, big household", href: LINKS.plan.ultra, popular: false },
] as const;

export const ext = { target: "_blank", rel: "noopener noreferrer" } as const;
