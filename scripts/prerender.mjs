import fs from 'node:fs';
import path from 'node:path';
import server from '../.output/server/index.mjs';

const routes = [
  '/',
  '/about',
  '/plans',
  '/coverage',
  '/help',
  '/contact',
  '/tools',
  '/tools/speed-test',
  '/tools/ip-lookup',
  '/tools/dns-lookup',
  '/tools/ping-test',
  '/tools/wifi-troubleshooter',
  '/tools/bandwidth-calculator'
];

const env = {
  ASSETS: {
    fetch: () => new Response(null, { status: 404 })
  }
};

const publicDir = path.resolve('public');

async function prerender() {
  console.log('Starting prerender of all public routes...');
  for (const r of routes) {
    const req = new Request('http://localhost' + r);
    const res = await server.fetch(req, env);
    if (res.status !== 200) {
      console.error(`Failed to prerender ${r}: status ${res.status}`);
      continue;
    }
    const html = await res.text();
    let outPath;
    if (r === '/') {
      outPath = path.join(publicDir, 'index.html');
    } else {
      const dir = path.join(publicDir, r);
      fs.mkdirSync(dir, { recursive: true });
      outPath = path.join(dir, 'index.html');
    }
    fs.writeFileSync(outPath, html, 'utf8');
    console.log(`Prerendered ${r} -> ${path.relative(publicDir, outPath)} (${html.length} bytes)`);
  }
  console.log('Prerender complete!');
}

prerender().catch(console.error);
