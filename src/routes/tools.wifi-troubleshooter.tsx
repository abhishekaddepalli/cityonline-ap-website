import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { WifiTroubleshooter } from "@/components/tools/WifiTroubleshooter";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/wifi-troubleshooter")({
  head: () => seo("Wi-Fi Troubleshooter", "Step-by-step help to fix slow or dropping Wi-Fi and get your connection back.", "/tools/wifi-troubleshooter"),
  component: () => (
    <ToolPage current="/tools/wifi-troubleshooter" title="Wi-Fi Troubleshooter" sub="Step-by-step help to fix slow or dropping Wi-Fi and get your connection back.">
      <WifiTroubleshooter />
    </ToolPage>
  ),
});
