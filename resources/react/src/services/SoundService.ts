// SoundService.ts — moteur audio à latence minimale.
//
// Les sons sont téléchargés et décodés UNE fois (Web Audio), puis joués
// instantanément au clic. Si un son n'est pas encore prêt (réseau lent),
// on joue tout de suite un son synthétisé plutôt qu'un son en retard :
// un retour sonore décalé par rapport à l'écran est pire qu'un son simple.

type FxName = 'correct' | 'wrong' | 'perfect' | 'applause' | 'levelup' | 'streak' | 'heart_lost'
  | 'tap' | 'pop' | 'mic_on' | 'mic_off' | 'tick'

// Ordre = priorité de préchargement (les sons de la boucle de question d'abord).
const FX: FxName[] = ['tap', 'correct', 'wrong', 'pop', 'streak', 'mic_on', 'mic_off', 'perfect', 'tick', 'heart_lost', 'levelup', 'applause']

class SoundServiceClass {
  private ctx: AudioContext | null = null
  private buffers = new Map<string, { buffer: AudioBuffer; offset: number }>()
  private loading = new Map<string, Promise<void>>()
  private clip: AudioBufferSourceNode | null = null
  private unlockBound = false

  private getCtx(): AudioContext | null {
    try {
      if (!this.ctx) this.ctx = new (window.AudioContext || (window as any).webkitAudioContext)()
      if (this.ctx.state === 'suspended') void this.ctx.resume()
      return this.ctx
    } catch {
      return null
    }
  }

  /** Débloque l'audio au premier geste (exigé par iOS/Android) et précharge les sons. */
  init() {
    if (this.unlockBound) return
    this.unlockBound = true
    const unlock = () => {
      const ctx = this.getCtx()
      if (ctx) {
        // Un tampon silencieux « réveille » la sortie audio sur les tablettes.
        const src = ctx.createBufferSource()
        src.buffer = ctx.createBuffer(1, 1, 22050)
        src.connect(ctx.destination)
        src.start(0)
      }
      window.removeEventListener('pointerdown', unlock, true)
      window.removeEventListener('keydown', unlock, true)
    }
    window.addEventListener('pointerdown', unlock, true)
    window.addEventListener('keydown', unlock, true)
    this.preload()
  }

  /** Précharge les effets (idempotent, séquentiel pour ne pas saturer le réseau). */
  preload(): Promise<void> {
    return FX.reduce((p, name) => p.then(() => this.load(`/sounds/fx/${name}.mp3`)), Promise.resolve())
  }

  /** Télécharge et décode un fichier une seule fois. Échec silencieux. */
  load(url: string): Promise<void> {
    if (this.buffers.has(url)) return Promise.resolve()
    const pending = this.loading.get(url)
    if (pending) return pending
    const ctx = this.getCtx()
    if (!ctx) return Promise.resolve()
    const job = fetch(url)
      .then(r => (r.ok ? r.arrayBuffer() : Promise.reject(new Error(String(r.status)))))
      .then(data => new Promise<AudioBuffer>((resolve, reject) => {
        // Forme à rappels pour les anciens Safari ; la promesse éventuelle est aussi captée.
        const p = ctx.decodeAudioData(data, resolve, reject) as Promise<AudioBuffer> | undefined
        p?.catch?.(reject)
      }))
      .then(buffer => { this.buffers.set(url, { buffer, offset: leadingSilence(buffer) }) })
      .catch(() => {})
    this.loading.set(url, job)
    return job
  }

  isReady(url: string) {
    return this.buffers.has(url)
  }

  /** Durée audible (ms) d'un son décodé, 0 s'il n'est pas prêt. */
  durationMs(url: string): number {
    const entry = this.buffers.get(url)
    return entry ? Math.round((entry.buffer.duration - entry.offset) * 1000) : 0
  }

  /** Joue un son décodé immédiatement. Renvoie false s'il n'est pas prêt. */
  play(url: string, { exclusive = false, volume = 1 } = {}): boolean {
    const entry = this.buffers.get(url)
    const ctx = this.getCtx()
    if (!entry || !ctx) return false
    if (exclusive) this.stopClip()
    const src = ctx.createBufferSource()
    src.buffer = entry.buffer
    const gain = ctx.createGain()
    gain.gain.value = volume
    src.connect(gain).connect(ctx.destination)
    src.start(0, entry.offset)
    if (exclusive) {
      this.clip = src
      src.onended = () => { if (this.clip === src) this.clip = null }
    }
    return true
  }

  /** Coupe la voix (clip exclusif) en cours. */
  stopClip() {
    try { this.clip?.stop() } catch {}
    this.clip = null
  }

  private fx(name: FxName, fallback: () => void, volume = 0.9) {
    if (!this.play(`/sounds/fx/${name}.mp3`, { volume })) fallback()
  }

  private tone(freq: number, duration: number, type: OscillatorType = 'sine', vol = 0.3, delay = 0) {
    const ctx = this.getCtx()
    if (!ctx) return
    try {
      const t = ctx.currentTime + delay
      const osc = ctx.createOscillator()
      const gain = ctx.createGain()
      osc.connect(gain)
      gain.connect(ctx.destination)
      osc.type = type
      osc.frequency.setValueAtTime(freq, t)
      gain.gain.setValueAtTime(vol, t)
      gain.gain.exponentialRampToValueAtTime(0.001, t + duration)
      osc.start(t)
      osc.stop(t + duration)
    } catch {}
  }

  correct() {
    this.fx('correct', () => { this.tone(523, 0.12); this.tone(659, 0.12, 'sine', 0.3, 0.1); this.tone(784, 0.22, 'sine', 0.3, 0.2) })
  }
  wrong() {
    this.fx('wrong', () => { this.tone(300, 0.12, 'sawtooth', 0.2); this.tone(220, 0.25, 'sawtooth', 0.15, 0.12) })
  }
  fanfare() {
    this.fx('perfect', () => {
      [523, 659, 784, 1047].forEach((f, i) => this.tone(f, 0.25, 'triangle', 0.35, i * 0.15))
      this.tone(1047, 0.5, 'triangle', 0.4, 0.7)
    })
  }
  applause() {
    this.fx('applause', () => { for (let i = 0; i < 5; i++) this.tone(400 + Math.random() * 200, 0.05, 'square', 0.1, i * 0.08) })
  }
  levelup() {
    this.fx('levelup', () => [523, 659, 784, 880, 1047].forEach((f, i) => this.tone(f, 0.15, 'triangle', 0.3, i * 0.1)))
  }
  streak() {
    this.fx('streak', () => { this.tone(880, 0.1, 'sine', 0.25); this.tone(1047, 0.15, 'sine', 0.25, 0.1) })
  }
  heartLost() {
    this.fx('heart_lost', () => {
      this.tone(440, 0.15, 'sawtooth', 0.2); this.tone(330, 0.15, 'sawtooth', 0.2, 0.15); this.tone(220, 0.3, 'sawtooth', 0.15, 0.3)
    })
  }
  // Sons de geste (style Duolingo) : plus discrets que le verdict pour ne pas fatiguer.
  /** Toucher un choix, une tuile, un mot. */
  tap() {
    this.fx('tap', () => this.tone(660, 0.04, 'sine', 0.12), 0.45)
  }
  /** Une paire associée se forme. */
  pop() {
    this.fx('pop', () => { this.tone(740, 0.06, 'sine', 0.2); this.tone(988, 0.1, 'sine', 0.2, 0.05) }, 0.7)
  }
  /** Le micro commence à écouter. */
  micOn() {
    this.fx('mic_on', () => { this.tone(587, 0.08, 'sine', 0.22); this.tone(880, 0.12, 'sine', 0.22, 0.08) }, 0.7)
  }
  /** Le micro s'arrête. */
  micOff() {
    this.fx('mic_off', () => { this.tone(880, 0.08, 'sine', 0.2); this.tone(587, 0.12, 'sine', 0.2, 0.08) }, 0.6)
  }
  private lastTick = 0
  /** Compteur qui monte (XP en fin de leçon) : au plus un tic toutes les 70 ms. */
  tick() {
    const now = performance.now()
    if (now - this.lastTick < 70) return
    this.lastTick = now
    this.fx('tick', () => this.tone(1568, 0.03, 'sine', 0.08), 0.3)
  }
  star()  { this.tone(1047, 0.08, 'sine', 0.2); this.tone(1319, 0.15, 'sine', 0.2, 0.08) }
  click() { this.tone(800, 0.05, 'sine', 0.12) }
  unlock() {}
}

/** Position (s) du premier échantillon audible : on saute le silence de tête. */
function leadingSilence(buffer: AudioBuffer, threshold = 0.01): number {
  const data = buffer.getChannelData(0)
  const max = Math.min(data.length, Math.floor(buffer.sampleRate * 0.4))
  for (let i = 0; i < max; i++) {
    if (Math.abs(data[i]) > threshold) return Math.max(0, i / buffer.sampleRate - 0.005)
  }
  return 0
}

export const SoundService = new SoundServiceClass()
