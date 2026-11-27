import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { BandwidthCalculator } from "@/components/tools/NetTools";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/bandwidth-calculator")({
  head: () => seo("Bandwidth Calculator", "Estimate how long a download will take at your internet speed.", "/tools/bandwidth-calculator"),
  component: () => (
    <ToolPage current="/tools/bandwidth-calculator" title="Bandwidth Calculator" sub="Estimate how long a download will take at your internet speed.">
      <BandwidthCalculator />
    </ToolPage>
  ),
});
