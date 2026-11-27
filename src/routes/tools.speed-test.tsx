import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { SpeedTest } from "@/components/tools/SpeedTest";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/speed-test")({
  head: () => seo("Internet Speed Test", "Measure your real download speed, upload speed, latency and jitter right from your browser.", "/tools/speed-test"),
  component: () => (
    <ToolPage current="/tools/speed-test" title="Internet Speed Test" sub="Measure your real download speed, upload speed, latency and jitter right from your browser.">
      <SpeedTest />
    </ToolPage>
  ),
});
