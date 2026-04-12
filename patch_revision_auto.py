# patch_revision_auto.py — Revision automatique du soir (Scheduler)
# Fichiers touches :
#   C:\laragon\www\edumaison-api\scheduler.py       (nouveau)
#   C:\laragon\www\edumaison-api\main.py            (startup/shutdown)
#   C:\laragon\www\edumaison-api\api\routes\evening.py  (config + trigger)
#   C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx

import os

API   = r"C:\laragon\www\edumaison-api"
TSX   = r"C:\laragon\www\edumaison\resources\react\src\pages\mama\MamaJudiApp.tsx"
MAIN  = os.path.join(API, "main.py")
EVE   = os.path.join(API, "api", "routes", "evening.py")
SCHED = os.path.join(API, "scheduler.py")

# ─────────────────────────────────────────────────────────────────────────────
#  1. scheduler.py — nouveau fichier
# ─────────────────────────────────────────────────────────────────────────────
scheduler_code = '''# scheduler.py -- Job APScheduler : revision automatique du soir
import datetime, json, os
from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.triggers.cron import CronTrigger

CONFIG_FILE = os.path.join(os.path.dirname(os.path.abspath(__file__)), "scheduler_config.json")

def load_config():
    if os.path.exists(CONFIG_FILE):
        with open(CONFIG_FILE, encoding="utf-8") as f:
            return json.load(f)
    return {"hour": 19, "minute": 0, "enabled": True}

def save_config(config):
    with open(CONFIG_FILE, "w", encoding="utf-8") as f:
        json.dump(config, f)

def run_auto_revision():
    """
    Analyse les attempts du jour pour chaque enfant actif.
    Cree une evening_session auto sur le sujet le plus faible.
    Ignore les enfants qui ont deja une session auto aujourd'hui.
    """
    from database import SessionLocal
    from sqlalchemy import text

    db = SessionLocal()
    today = datetime.date.today().isoformat()
    now   = datetime.datetime.utcnow()
    count = 0
    try:
        children = db.execute(text(
            "SELECT id FROM children WHERE is_active = true"
        )).fetchall()

        for child in children:
            cid = child.id

            # Pas de doublon : une seule session auto par enfant par jour
            existing = db.execute(text("""
                SELECT id FROM evening_sessions
                WHERE child_id = :cid
                  AND DATE(triggered_at) = :today
                  AND theme_source = 'auto'
                LIMIT 1
            """), {"cid": cid, "today": today}).scalar()
            if existing:
                continue

            # Sujet le plus faible du jour (avg score le plus bas)
            weak = db.execute(text("""
                SELECT it.subject_id, AVG(ea.score) AS avg_score
                FROM exercise_attempts ea
                JOIN exercises e  ON ea.exercise_id = e.id
                JOIN lessons l    ON e.lesson_id = l.id
                JOIN units u      ON l.unit_id = u.id
                JOIN integrated_themes it ON u.integrated_theme_id = it.id
                WHERE ea.child_id = :cid AND DATE(ea.attempted_at) = :today
                GROUP BY it.subject_id
                ORDER BY avg_score ASC
                LIMIT 1
            """), {"cid": cid, "today": today}).fetchone()

            subject_id = weak.subject_id if weak else None

            db.execute(text("""
                INSERT INTO evening_sessions
                    (child_id, subject_id, unit_id, exam_id, theme_source,
                     mama_judi_message, status, triggered_at, created_at, updated_at)
                VALUES (:cid, :sid, NULL, NULL, 'auto',
                        'Revision automatique EduMaison', 'pending', :now, :now, :now)
            """), {"cid": cid, "sid": subject_id, "now": now})
            count += 1

        db.commit()
        print(f"[Scheduler] {count} session(s) creees a {now.strftime('%H:%M')}")
    except Exception as e:
        db.rollback()
        print(f"[Scheduler] Erreur : {e}")
    finally:
        db.close()

def create_scheduler():
    config  = load_config()
    sched   = BackgroundScheduler(timezone="Africa/Douala")
    if config.get("enabled", True):
        sched.add_job(
            run_auto_revision,
            CronTrigger(hour=config["hour"], minute=config["minute"]),
            id="auto_revision",
            replace_existing=True,
        )
        print(f"[Scheduler] Planifie a {config['hour']:02d}:{config['minute']:02d}")
    else:
        print("[Scheduler] Desactive")
    return sched
'''

with open(SCHED, "w", encoding="utf-8") as f:
    f.write(scheduler_code)
print("OK  scheduler.py")

# ─────────────────────────────────────────────────────────────────────────────
#  2. main.py — import scheduler + startup/shutdown
# ─────────────────────────────────────────────────────────────────────────────
with open(MAIN, "r", encoding="utf-8") as f:
    main = f.read()

# Ajouter import apres core.config
main = main.replace(
    "from core.config import API_PORT",
    "from core.config import API_PORT\nfrom scheduler import create_scheduler, run_auto_revision, load_config, save_config"
)

# Ajouter instance scheduler apres app = FastAPI(...)
main = main.replace(
    "app = FastAPI(\n    title=\"EduMaison API\"",
    "app = FastAPI(\n    title=\"EduMaison API\""
)

# Ajouter startup/shutdown avant @app.get("/health")
main = main.replace(
    '@app.get("/health")',
    'scheduler = create_scheduler()\n\n'
    '@app.on_event("startup")\n'
    'async def startup_event():\n'
    '    scheduler.start()\n\n'
    '@app.on_event("shutdown")\n'
    'async def shutdown_event():\n'
    '    scheduler.shutdown()\n\n'
    '@app.get("/health")'
)

with open(MAIN, "w", encoding="utf-8") as f:
    f.write(main)
print("OK  main.py")

# ─────────────────────────────────────────────────────────────────────────────
#  3. evening.py — config heure + trigger immediat
# ─────────────────────────────────────────────────────────────────────────────
with open(EVE, "r", encoding="utf-8") as f:
    eve = f.read()

eve += '''

@router.get("/evening-sessions/scheduler-config")
def get_scheduler_config():
    """Retourne la configuration du scheduler (heure, actif)"""
    from scheduler import load_config
    return load_config()

@router.post("/evening-sessions/scheduler-config")
def update_scheduler_config(payload: dict):
    """Met a jour la configuration du scheduler et replanifie le job"""
    from scheduler import load_config, save_config, create_scheduler
    config = load_config()
    if "hour" in payload:
        config["hour"] = int(payload["hour"])
    if "minute" in payload:
        config["minute"] = int(payload["minute"])
    if "enabled" in payload:
        config["enabled"] = bool(payload["enabled"])
    save_config(config)
    # Replanifier le job en live
    try:
        from main import scheduler as sched
        from apscheduler.triggers.cron import CronTrigger
        if config["enabled"]:
            sched.reschedule_job(
                "auto_revision",
                trigger=CronTrigger(hour=config["hour"], minute=config["minute"])
            )
        else:
            if sched.get_job("auto_revision"):
                sched.remove_job("auto_revision")
    except Exception as e:
        print(f"[Scheduler reschedule] {e}")
    return config

@router.post("/evening-sessions/trigger-auto")
def trigger_auto_now():
    """Declenche immediatement la revision automatique (sans attendre l'heure)"""
    from scheduler import run_auto_revision
    run_auto_revision()
    return {"success": True, "message": "Revision automatique declenchee"}
'''

with open(EVE, "w", encoding="utf-8") as f:
    f.write(eve)
print("OK  evening.py")

# ─────────────────────────────────────────────────────────────────────────────
#  4. MamaJudiApp.tsx — carte Auto dans RevisionScreen
# ─────────────────────────────────────────────────────────────────────────────
with open(TSX, "r", encoding="utf-8") as f:
    tsx = f.read()

# 4-a. Traductions FR
tsx = tsx.replace(
    "    tableau_exercises_found: 'exercices trouv\u00e9s',",
    "    tableau_exercises_found: 'exercices trouv\u00e9s',"
    "\n    auto_section: 'R\u00e9vision automatique', auto_on: 'Activ\u00e9', auto_off: 'D\u00e9sactiv\u00e9',"
    "\n    auto_time: 'Heure', auto_trigger_now: 'D\u00e9clencher maintenant', auto_triggered: 'Envoy\u00e9 !',"
)

# 4-b. Traductions EN
tsx = tsx.replace(
    "    tableau_exercises_found: 'exercises found',",
    "    tableau_exercises_found: 'exercises found',"
    "\n    auto_section: 'Auto Revision', auto_on: 'On', auto_off: 'Off',"
    "\n    auto_time: 'Time', auto_trigger_now: 'Trigger now', auto_triggered: 'Sent!',"
)

# 4-c. Composant AutoRevisionCard avant RevisionScreen
auto_card = """
// \u2500\u2500 AUTO REVISION CARD \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
function AutoRevisionCard({ t }: { t: typeof T['fr'] }) {
  const [config, setConfig] = useState(null as any)
  const [triggering, setTriggering] = useState(false)
  const [triggered, setTriggered] = useState(false)

  useEffect(() => {
    fetch('/api/evening-sessions/scheduler-config')
      .then(r => r.json())
      .then(setConfig)
      .catch(() => {})
  }, [])

  const saveConfig = async (next: any) => {
    setConfig(next)
    await fetch('/api/evening-sessions/scheduler-config', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(next)
    }).catch(() => {})
  }

  const trigger = async () => {
    setTriggering(true)
    try {
      await fetch('/api/evening-sessions/trigger-auto', { method: 'POST' })
      setTriggered(true)
      setTimeout(() => setTriggered(false), 3000)
    } finally { setTriggering(false) }
  }

  if (!config) return null

  const hour   = config.hour   ?? 19
  const minute = config.minute ?? 0
  const timeStr = String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0')
  const cardBg  = config.enabled ? 'rgba(29,107,42,.07)' : P.card
  const cardBdr = config.enabled ? 'rgba(29,107,42,.22)' : P.border

  return (
    <div style={{ background: cardBg, borderRadius: 18, padding: '14px 16px',
      marginBottom: 20, border: '1.5px solid ' + cardBdr }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 }}>
        <div style={{ fontSize: 13, fontWeight: 900, color: P.dark }}>{t.auto_section}</div>
        <button onClick={() => saveConfig({ ...config, enabled: !config.enabled })}
          style={{ padding: '4px 14px', borderRadius: 20, border: 'none', cursor: 'pointer',
            background: config.enabled ? P.green : P.border,
            color: config.enabled ? 'white' : P.soft,
            fontSize: 12, fontWeight: 800, fontFamily: 'Nunito, sans-serif' }}>
          {config.enabled ? t.auto_on : t.auto_off}
        </button>
      </div>
      {config.enabled && (
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 10 }}>
          <div style={{ fontSize: 12, color: P.soft, flexShrink: 0 }}>{t.auto_time}</div>
          <input type="time" value={timeStr}
            onChange={e => {
              const parts = e.target.value.split(':')
              saveConfig({ ...config, hour: parseInt(parts[0]), minute: parseInt(parts[1]) })
            }}
            style={{ padding: '6px 10px', borderRadius: 10, border: '1.5px solid ' + P.border,
              fontFamily: 'Nunito, sans-serif', fontSize: 14, background: P.white, color: P.dark }} />
        </div>
      )}
      <button onClick={trigger} disabled={triggering}
        style={{ width: '100%', padding: 10, borderRadius: 14, border: 'none',
          background: triggered ? P.green : P.brown, color: 'white',
          fontSize: 13, fontWeight: 800, cursor: 'pointer', fontFamily: 'Nunito, sans-serif' }}>
        {triggered ? t.auto_triggered : triggering ? '...' : t.auto_trigger_now}
      </button>
    </div>
  )
}
"""

tsx = tsx.replace("// \u2500\u2500 REVISION \u2500", auto_card + "\n// \u2500\u2500 REVISION \u2500")

# 4-d. Injecter AutoRevisionCard dans le return de RevisionScreen
tsx = tsx.replace(
    "  if (sent) return (\n    <div style={{ textAlign: 'center', padding: '60px 20px' }}>",
    "  if (sent) return (\n    <div style={{ textAlign: 'center', padding: '60px 20px' }}>"
)

# Ajouter la carte juste apres l'ouverture du return principal de RevisionScreen
tsx = tsx.replace(
    "  return (\n    <div>\n      <div style={{ marginBottom: 20 }}>\n        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_children}</div>",
    "  return (\n    <div>\n      <AutoRevisionCard t={t} />\n      <div style={{ marginBottom: 20 }}>\n        <div style={{ fontSize: 13, fontWeight: 800, color: P.soft, marginBottom: 8 }}>{t.choose_children}</div>"
)

with open(TSX, "w", encoding="utf-8") as f:
    f.write(tsx)
print("OK  MamaJudiApp.tsx")

print("\nTermine. Relancer uvicorn + npm run build pour tester.")
