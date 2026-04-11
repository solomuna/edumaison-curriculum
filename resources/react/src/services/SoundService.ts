// SoundService.ts — Web Audio API (no files needed) + .wav fallback
class SoundServiceClass {
  private ctx: AudioContext | null = null

  private getCtx(): AudioContext {
    if (!this.ctx) this.ctx = new (window.AudioContext || (window as any).webkitAudioContext)()
    if (this.ctx.state === 'suspended') this.ctx.resume()
    return this.ctx
  }

  private tone(freq: number, duration: number, type: OscillatorType = 'sine', vol = 0.3) {
    try {
      const ctx = this.getCtx()
      const osc = ctx.createOscillator()
      const gain = ctx.createGain()
      osc.connect(gain)
      gain.connect(ctx.destination)
      osc.type = type
      osc.frequency.setValueAtTime(freq, ctx.currentTime)
      gain.gain.setValueAtTime(vol, ctx.currentTime)
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration)
      osc.start(ctx.currentTime)
      osc.stop(ctx.currentTime + duration)
    } catch (e) {}
  }

  // Try .wav first, fallback to Web Audio
  private playWav(file: string, fallback: () => void) {
    try {
      const a = new Audio('/sounds/' + file)
      const p = a.play()
      if (p) p.catch(() => fallback())
    } catch (e) { fallback() }
  }

  correct() {
    this.playWav('sfx_correct.wav', () => {
      this.tone(523, 0.12)
      setTimeout(() => this.tone(659, 0.12), 100)
      setTimeout(() => this.tone(784, 0.22), 200)
    })
  }

  wrong() {
    this.playWav('sfx_wrong.wav', () => {
      this.tone(300, 0.12, 'sawtooth', 0.2)
      setTimeout(() => this.tone(220, 0.25, 'sawtooth', 0.15), 120)
    })
  }

  fanfare() {
    this.playWav('sfx_perfect.wav', () => {
      const notes = [523, 659, 784, 1047]
      notes.forEach((f, i) => setTimeout(() => this.tone(f, 0.25, 'triangle', 0.35), i * 150))
      setTimeout(() => this.tone(1047, 0.5, 'triangle', 0.4), 700)
    })
  }

  applause() {
    this.playWav('sfx_applause.wav', () => {
      for (let i = 0; i < 5; i++) {
        setTimeout(() => this.tone(400 + Math.random() * 200, 0.05, 'square', 0.1), i * 80)
      }
    })
  }

  levelup() {
    this.playWav('sfx_levelup.wav', () => {
      [523, 659, 784, 880, 1047].forEach((f, i) =>
        setTimeout(() => this.tone(f, 0.15, 'triangle', 0.3), i * 100))
    })
  }

  streak() {
    this.playWav('sfx_streak.wav', () => {
      this.tone(880, 0.1, 'sine', 0.25)
      setTimeout(() => this.tone(1047, 0.15, 'sine', 0.25), 100)
    })
  }

  heartLost() {
    this.playWav('sfx_heart_lost.wav', () => {
      this.tone(440, 0.15, 'sawtooth', 0.2)
      setTimeout(() => this.tone(330, 0.15, 'sawtooth', 0.2), 150)
      setTimeout(() => this.tone(220, 0.3,  'sawtooth', 0.15), 300)
    })
  }

  star()  { this.tone(1047, 0.08, 'sine', 0.2); setTimeout(() => this.tone(1319, 0.15, 'sine', 0.2), 80) }
  click() { this.tone(800, 0.06, 'sine', 0.15) }
  unlock() {}
}

export const SoundService = new SoundServiceClass()
