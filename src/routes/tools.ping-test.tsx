import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { PingTest } from "@/components/tools/NetTools";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/ping-test")({
  head: () => seo("Ping & Latency Test", "Measure approximate request latency from your browser, with average, minimum and maximum.", "/tools/ping-test"),
  component: () => (
    <ToolPage current="/tools/ping-test" title="Ping & Latency Test" sub="Measure approximate request latency from your browser, with average, minimum and maximum.">
      <PingTest />
    </ToolPage>
  ),
});
