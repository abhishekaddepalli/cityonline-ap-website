<!-- LOVABLE:BEGIN -->
> [!IMPORTANT]
> This project is connected to [Lovable](https://lovable.dev). Avoid rewriting
> published git history — force pushing, or rebasing/amending/squashing commits
> that are already pushed — as it rewrites history on Lovable's side and the
> user will likely lose their project history.
>
> Commits you push to the connected branch sync back to Lovable and show up in
> the editor, so keep the branch in a working state.

## What Lovable manages

Rules outside this block are the project's own. Change the pieces below in
Lovable, not by hand. With the [Lovable MCP](https://mcp.lovable.dev)
connected, `send_message` asks Lovable to do it; otherwise ask the user to
use the editor.

- **Database.** Do not add or edit `supabase/migrations/` or `drizzle/`.
  Ask Lovable to change the schema.
- **Generated files.** Do not edit a file whose header says it is automatically
  generated.
- **AI gateway.** Keep model calls on Lovable's gateway, and keep
  `LOVABLE_API_KEY` out of browser code.
- **Connectors.** Lovable provides native connectors. `list_connectors` shows
  which are enabled; `add_connector` only returns the dashboard link. When a
  connector is enabled, calls go through the connector gateway and need
  `LOVABLE_API_KEY`. When Lovable does not provide one, users can bring their
  own key and store it in Secrets.
- **Secrets.** Lovable manages them. Do not put secret values in the repo.
<!-- LOVABLE:END -->

## Project rules
- Frontend-only site: no backend, database or auth. Why: the client asked for a pure frontend redesign.
- All existing City Online URLs, phone numbers and plan data live in `src/lib/links.ts`; never hardcode them elsewhere. Why: links must stay identical to the live portal.
- Internet tools call public CORS services directly (Cloudflare speed, ipify, Google/Cloudflare DNS-over-HTTPS); never show simulated results. Why: honesty requirement.
- Speed-test server is overridable via `VITE_SPEEDTEST_BASE`. Why: allows a self-hosted LibreSpeed-compatible endpoint later.
- Help chat is a fixed FAQ (`chat-faq.ts`), not an AI service. Why: no AI/backend in scope.

- Shared root state controls the help assistant; mobile opens it from the bottom navigation instead of floating content-covering controls. Why: keep small-screen actions unobstructed.
- Keep primary content visible on first render; do not gate it behind opacity/reveal animations. Why: fast initial paint and readable pre-hydration pages.
- Use bundled responsive WebP variants for the homepage photo. Why: reduce image transfer on phones without a server transformer.
