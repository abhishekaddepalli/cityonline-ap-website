import { describe, expect, it } from "vitest";
import { LINKS, PLANS } from "./links";

describe("preserved City Online links", () => {
  it("pay bill uses the customer portal", () => {
    expect(LINKS.payBill).toBe("https://customer.cityonlineap.com/");
  });
  it("new connection uses the booking form", () => {
    expect(LINKS.newConnection).toBe("https://customer.cityonlineap.com/book-connection?src=site");
  });
  it("each plan links to its own booking URL and price", () => {
    const m = Object.fromEntries(PLANS.map((p) => [p.id, [p.href, p.price, p.speed]]));
    expect(m['standard']).toEqual(["https://customer.cityonlineap.com/book-connection?plan=standard&src=site", "590", 60]);
    expect(m['premium']).toEqual(["https://customer.cityonlineap.com/book-connection?plan=premium&src=site", "767", 100]);
    expect(m['ultra']).toEqual(["https://customer.cityonlineap.com/book-connection?plan=ultra&src=site", "1,180", 150]);
  });
  it("whatsapp opens 919700928928", () => {
    expect(LINKS.whatsapp.startsWith("https://wa.me/919700928928?")).toBe(true);
  });
});
