import { createFileRoute } from "@tanstack/react-router";
import { PageHero } from "@/components/site/primitives";
import { CoverageChecker } from "@/components/site/sections";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/coverage")({
  head: () => seo("Coverage — Is Fibre Available in Your Area?", "City Online AP covers 40+ localities in Kakinada. Check your locality and send a new connection enquiry.", "/coverage"),
  component: () => (
    <>
      <PageHero eyebrow="Coverage" title="Fibre across 40+ localities in Kakinada" sub="Tell us where you are and our team will confirm availability at your address." />
      <section className="container-x -mt-8 relative pb-16"><CoverageChecker /></section>
    </>
  ),
});
