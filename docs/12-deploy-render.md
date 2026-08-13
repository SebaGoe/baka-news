# 12 · Deploy to Render (today)

Baka News is a Docker web service. No database add-on needed (SQLite is built in).

## Option A — Blueprint (recommended, ~5 min)
1. Push this repo to GitHub.
2. Render dashboard → **New + → Blueprint** → pick your repo. It reads `render.yaml`.
3. Confirm → **Apply**. Render builds the Dockerfile and starts
   `php -S 0.0.0.0:$PORT server.php`.
4. Open the generated `*.onrender.com` URL. 🎉

## Option B — Manual web service
1. **New + → Web Service** → your repo → Runtime **Docker**.
2. Instance type **Free** is fine. No build/start command needed (Dockerfile handles it).
3. Env vars (optional; defaults work): `BAKA_DB_PATH=/app/data/db/baka.sqlite`,
   `BAKA_STORAGE=/app/storage`.
4. **Create Web Service.**

## Persisting the guest book / counter (optional)
The free plan's disk is **ephemeral** — the SQLite file resets on each deploy/restart. For a
joke site that's acceptable (and thematically funny). To keep data:
1. Upgrade the service off Free (Render disks require a paid instance).
2. In `render.yaml`, uncomment the `disk:` block (mounts a 1 GB disk at `/app/data/db`), or
   add a disk in the dashboard with mount path `/app/data/db`.
3. Redeploy. `Db::init()` recreates tables only if missing, so data now survives.

## Custom domain
Render → service → **Settings → Custom Domains** → add yours → set the CNAME at your
registrar. TLS is automatic.

## Health & troubleshooting
- **Blank page / 500:** check **Logs**. Most likely a JSON typo — validate with
  `php -r 'json_decode(file_get_contents("data/content/articles.json"),true) ?: print(json_last_error_msg());'`.
- **Assets 404:** they must live under `public/assets/...`; `server.php` serves existing
  files directly.
- **`mb_substr` undefined:** the Dockerfile installs `mbstring` (needs `oniguruma-dev` on
  Alpine) — don't remove that line; the multilingual content depends on it.
- **Port:** always bind `0.0.0.0:$PORT`. The Dockerfile already does.

## Local dev parity
```bash
php -S localhost:8000 server.php      # identical routing to prod
```
Or build the image: `docker build -t baka . && docker run -p 10000:10000 baka`.
