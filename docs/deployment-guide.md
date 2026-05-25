# Zero-Click Deploy: GitHub Actions → VPS (Laravel + Docker)

Push to `main` → auto-deploys to VPS in ~4 minutes. No manual SSH, no babysitting.

---

## How It Works

```
You: git push main
         │
         ▼
  GitHub Actions runner (cloud)
  reads .github/workflows/deploy.yml
         │
         ▼
  SSH into VPS (using private key in GitHub Secrets)
         │
         ▼
  VPS runs deploy script:
  1. git pull origin main
  2. docker compose build app
  3. docker compose down
  4. docker volume rm <project>_app-code
  5. docker compose up -d
  6. docker compose exec app php artisan migrate --force
  7. docker compose exec app php artisan db:seed --force
```

---

## Prerequisites

- A VPS with Docker and Docker Compose (v2) installed
- A GitHub repository containing a Laravel app with a `docker-compose.yml`
- SSH access to the VPS (username + password or existing key)
- Your project uses **named volumes** for the app code (e.g., `app-code:/var/www`)

---

## Step 1: Set Up GitHub Deploy Key (VPS → GitHub)

The VPS needs to `git pull` your private repo without a password. We create an SSH keypair and register the public half as a **deploy key** on GitHub.

### 1a. Generate a keypair on your local machine

```bash
ssh-keygen -t ed25519 -f github_deploy_key -N "" -C "vps-github-deploy"
```

This creates two files:
- `github_deploy_key` — **private key** (keep secret, goes on VPS)
- `github_deploy_key.pub` — **public key** (goes to GitHub)

### 1b. Add the public key to GitHub

1. Open your repo on GitHub.com
2. Go to **Settings > Security > Deploy keys > Add deploy key**
3. Title: `VPS Auto Deploy`
4. Key: paste the contents of `github_deploy_key.pub`
5. **Uncheck** "Allow write access" (read-only is enough)
6. Click **Add key**

### 1c. Copy the private key to the VPS

```bash
scp github_deploy_key root@<YOUR_VPS_IP>:/root/.ssh/github_deploy_key
```

Then SSH into the VPS and set permissions:

```bash
ssh root@<YOUR_VPS_IP>
chmod 600 ~/.ssh/github_deploy_key
```

### 1d. Configure SSH on the VPS to use this key for GitHub

```bash
cat >> ~/.ssh/config << 'EOF'
Host github.com
  HostName github.com
  IdentityFile ~/.ssh/github_deploy_key
  StrictHostKeyChecking accept-new
EOF
chmod 600 ~/.ssh/config
```

### 1e. Change your git remote to SSH

```bash
cd /path/to/your/project
git remote set-url origin git@github.com:<USERNAME>/<REPO>.git
```

### 1f. Test the connection

```bash
ssh -T git@github.com
```

Expected output:
```
Hi <USERNAME>/<REPO>! You've successfully authenticated, but GitHub does not provide shell access.
```

---

## Step 2: Set Up SSH Key for GitHub Actions (GitHub → VPS)

The GitHub Actions runner needs to SSH into your VPS. We create a separate keypair for this.

### 2a. Generate a keypair

```bash
ssh-keygen -t ed25519 -f deploy_key -N "" -C "github-actions-deploy"
```

Files created:
- `deploy_key` — **private key** (goes to GitHub Secrets)
- `deploy_key.pub` — **public key** (goes to VPS)

### 2b. Add the public key to the VPS

```bash
ssh-copy-id -i deploy_key.pub root@<YOUR_VPS_IP>
```

Or manually:

```bash
cat deploy_key.pub | ssh root@<YOUR_VPS_IP> 'cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys'
```

### 2c. Test the connection

```bash
ssh -i deploy_key root@<YOUR_VPS_IP> 'echo "SSH OK"'
```

Expected output: `SSH OK`

---

## Step 3: Store Secrets in GitHub

The workflow needs 3 secrets to authenticate with your VPS.

1. Go to your repo on GitHub.com
2. **Settings > Security > Secrets and variables > Actions**
3. Click **New repository secret** and add these:

| Secret | Value |
|---|---|
| `VPS_HOST` | Your VPS IP address (e.g., `103.55.38.81`) |
| `VPS_USER` | SSH username (e.g., `root` or `tamatopik`) |
| `VPS_SSH_KEY` | The **entire** content of `deploy_key` (private key, including `-----BEGIN ...----` and `-----END ...----`) |

---

## Step 4: Create the Workflow File

Create `.github/workflows/deploy.yml` in your project root:

```yaml
name: Deploy to VPS

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1.1.0
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd /var/www/<YOUR_PROJECT_DIR>

            git pull origin main

            docker compose build app
            docker compose down
            docker volume rm <PROJECT_NAME>_app-code || true
            docker compose up -d

            docker compose exec -T app php artisan migrate --force
            docker compose exec -T app php artisan db:seed --force
```

**Replace:**
- `/var/www/<YOUR_PROJECT_DIR>` — the path to your project on the VPS
- `<PROJECT_NAME>_app-code` — your named Docker volume name. Find it with `docker volume ls`

### Understanding the `docker volume rm` step

This is the most important (and non-obvious) part.

Your `docker-compose.yml` likely has:

```yaml
volumes:
  - app-code:/var/www
```

This mounts a **named volume** at `/var/www`. Docker named volumes **persist** even when containers are recreated. When you rebuild the image with new code and restart the container, the old code in the volume **overrides** the new code in the image.

The fix: delete the code volume after building the image but before starting the container. Docker will create a fresh empty volume and populate it from the new image's `/var/www`.

**Do NOT delete the storage volume** (e.g., `app-storage`) — that would wipe uploaded files.

The `|| true` prevents the script from failing if the volume doesn't exist (e.g., first deploy).

---

## Step 5: Commit and Push

```bash
git add .github/workflows/deploy.yml
git commit -m "ci: auto-deploy to VPS on push to main"
git push
```

Go to your repo on GitHub.com → **Actions** tab. You should see the workflow running within seconds.

---

## Optional: Speed Up the Build

The slowest step is `docker compose build app`, which runs `composer install` and `npm install && npm run build` inside the container.

### Docker layer caching

Docker caches layers by default. The `COPY` of your source code invalidates the layer, but `composer install` and `npm install` are cached as long as `composer.json`/`package.json` haven't changed. This means:

- **First build** after changing `composer.json`: ~4 mins
- **Subsequent builds** with only PHP file changes: ~2 mins
- **Subsequent builds** with no dependency changes: ~1.5 mins

### Commit pre-built assets (skip npm on VPS)

If your CSS/JS don't change often, build them once and commit:

```bash
npm run build
git add public/build
git commit -m "build: assets"
```

Then in your Dockerfile, you can skip `npm install && npm run build` and just copy `public/build` from the repo. This saves ~1-2 minutes per deploy.

---

## Troubleshooting

### Git pull fails: permission denied

The VPS can't authenticate with GitHub. Either:
- The deploy key wasn't added to GitHub (Step 1b)
- The private key path in `~/.ssh/config` is wrong
- The git remote still uses HTTPS instead of SSH

Fix: `git remote set-url origin git@github.com:<USER>/<REPO>.git`

### Container starts with old code

You forgot to remove the `app-code` volume, or the volume name in the workflow is wrong.

Check volume names:
```bash
docker volume ls | grep app-code
```

### App returns 500 after deploy

Check logs:
```bash
docker compose logs app
docker compose exec app php artisan logs:tail  # if using Laravel Pail
```

### Database connection refused

The `db` container might not be ready when the `app` container starts. The compose file should have:

```yaml
depends_on:
  db:
    condition: service_healthy
```

### Migrations fail: table already exists

You're running `migrate --force` on every deploy, which is fine — Laravel tracks which migrations ran in the `migrations` table. If it fails, check if the DB credentials are correct in `.env` on the VPS.

---

## Reuse for Another Project

1. Generate a **new GitHub deploy key** (Step 1) — each project on the same VPS can share the same key, or use separate ones
2. The **same** `deploy_key` (Step 2) can be reused if deploying from the same GitHub account to the same VPS
3. Copy the workflow file, update the paths and volume name
4. Adjust the post-deploy commands (replace Laravel-specific steps with your framework's equivalents)

---

## Reference: Full File List

| File | Where it lives | Purpose |
|---|---|---|
| `deploy_key` | Local machine → GitHub Secrets | Private key for GitHub Actions → VPS SSH |
| `deploy_key.pub` | VPS `~/.ssh/authorized_keys` | Public key allowing GitHub Actions to SSH in |
| `github_deploy_key` | VPS `~/.ssh/github_deploy_key` | Private key for VPS → GitHub git pull |
| `github_deploy_key.pub` | GitHub repo deploy keys | Public key granting read-only git access |
| `.github/workflows/deploy.yml` | Your repo | GitHub Actions workflow definition |
