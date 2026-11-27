import { createFileRoute } from "@tanstack/react-router";
import { PageHero } from "@/components/site/primitives";
import { CoverageChecker } from "@/components/site/sections";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/coverage")({
  head: () => seo("Coverage — Is Fibre Available in Your Area?", "City Online AP covers Peddapuram, Samalkot and surrounding localities. Check your locality and send a new connection enquiry.", "/coverage"),
  component: () => (
    <>
      <PageHero eyebrow="Coverage" title="Fibre across Peddapuram & Surroundings" sub="Tell us where you are in Peddapuram or nearby areas and our team will confirm availability at your address." />
      <section className="container-x -mt-8 relative pb-16"><CoverageChecker /></section>
    </>
  ),
});
