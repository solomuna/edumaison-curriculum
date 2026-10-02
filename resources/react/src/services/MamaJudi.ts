// MamaJudi.ts - TTS service: Web Speech API (browser) + Capacitor TTS (Android)
import { TextToSpeech } from '@capacitor-community/text-to-speech'
import { SoundService } from './SoundService'

export type MamaEvent = 'greeting' | 'correct' | 'wrong' | 'session_good' | 'session_perfect' | 'session_retry' | 'streak3' | 'streak5'
  | 'retry_correct' | 'lesson_start' | 'oral_louder' | 'oral_listen_again' | 'idle'
type VoiceLang = 'en' | 'fr'

// Pack de voix commun à toutes les familles (aucun prénom), généré par
// scripts/generate-mama-voice.mjs : plusieurs variantes par moment et par langue.
const VOICE_BASE = '/sounds/mama/v2'
interface VoiceManifest { clips: Partial<Record<VoiceLang, Partial<Record<MamaEvent, { file: string }[]>>>> }

const isCapacitor = () => typeof (window as any).Capacitor !== 'undefined' && (window as any).Capacitor.isNativePlatform()

class MamaJudiClass {
  private currentAudio: HTMLAudioElement | null = null
  private currentUtterance: SpeechSynthesisUtterance | null = null
  private scheduledSpeech: number | null = null
  private childName = ''
  private lang: VoiceLang = 'en'
  private manifest: VoiceManifest | null = null
  private manifestLoading: Promise<VoiceManifest | null> | null = null

  setChild(name: string) {
    this.childName = name.split(' ')[0]
    localStorage.setItem('edumaison_child', this.childName)
  }

  /** Langue des répliques (celle de l'exercice en cours). */
  setLanguage(lang: VoiceLang) {
    this.lang = lang
  }

  private loadManifest(): Promise<VoiceManifest | null> {
    if (this.manifest) return Promise.resolve(this.manifest)
    if (!this.manifestLoading) {
      this.manifestLoading = fetch(`${VOICE_BASE}/manifest.json`)
        .then(r => (r.ok ? r.json() : null))
        .then(data => { this.manifest = data?.clips ? data : null; return this.manifest })
        .catch(() => null)
    }
    return this.manifestLoading
  }

  /** Toutes les variantes d'un moment, dans la langue courante (pack pas encore généré : aucune). */
  private clipUrls(event: MamaEvent, lang: VoiceLang = this.lang): string[] {
    return (this.manifest?.clips[lang]?.[event] ?? []).map(clip => `${VOICE_BASE}/${clip.file}`)
  }

  private pick(urls: string[]): string | null {
    return urls.length ? urls[Math.floor(Math.random() * urls.length)] : null
  }

  private playMp3(event: MamaEvent): boolean {
    const src = this.pick(this.clipUrls(event))
    if (!src) return false
    this.stopAudio()
    // Clip déjà décodé : lecture instantanée. Sinon lecture classique (moins réactive).
    if (SoundService.play(src, { exclusive: true })) return true
    this.currentAudio = new Audio(src)
    this.currentAudio.play().catch(() => {})
    return true
  }

  /** Précharge les répliques de la langue courante (lecture instantanée ensuite). */
  preloadVoices(events: MamaEvent[] = ['correct', 'wrong', 'retry_correct', 'streak3', 'streak5', 'idle', 'oral_louder', 'oral_listen_again', 'session_good', 'session_perfect', 'session_retry']) {
    const lang = this.lang
    void this.loadManifest().then(() => events
      .flatMap(event => this.clipUrls(event, lang))
      .reduce<Promise<void>>((p, src) => p.then(() => SoundService.load(src)), Promise.resolve()))
  }

  /**
   * Réaction immédiate (bonne réponse, erreur, série) : joue une variante déjà
   * décodée, au hasard. Jamais de synthèse vocale ici : elle démarre trop tard
   * et arriverait décalée par rapport à l'écran.
   */
  react(event: MamaEvent): boolean {
    const src = this.pick(this.clipUrls(event).filter(url => SoundService.isReady(url)))
    if (!src) return false
    this.stopAudio()
    return SoundService.play(src, { exclusive: true })
  }

  private introPending: Promise<number> | null = null

  /**
   * Encouragement de début de leçon (« C'est parti ! »), au plus une fois toutes
   * les 10 minutes pour ne pas lasser. Renvoie la durée jouée en ms (0 si rien),
   * pour que la lecture de la première question attende la fin.
   */
  intro(): Promise<number> {
    if (this.introPending) return this.introPending
    this.introPending = (async () => {
      try {
        if (Date.now() - Number(sessionStorage.getItem('mama_intro_at') || 0) < 10 * 60_000) return 0
      } catch { /* stockage indisponible : on joue quand même */ }
      await this.loadManifest()
      const src = this.pick(this.clipUrls('lesson_start'))
      if (!src) return 0
      // Réseau lent : pas d'encouragement en retard sur l'écran.
      await Promise.race([SoundService.load(src), new Promise(r => setTimeout(r, 1500))])
      const ms = SoundService.durationMs(src)
      if (!ms || !SoundService.play(src, { exclusive: true })) return 0
      try { sessionStorage.setItem('mama_intro_at', String(Date.now())) } catch { /* idem */ }
      return ms
    })().finally(() => { this.introPending = null })
    return this.introPending
  }

  private async ttsCapacitor(text: string, lang = 'fr-FR', rate = 0.9): Promise<boolean> {
    try {
      await TextToSpeech.stop()
      await TextToSpeech.speak({ text, lang, rate, pitch: 1.0, volume: 1.0, category: 'ambient' })
      return true
    } catch (error) {
      console.warn('Capacitor TTS error:', error)
      return false
    }
  }

  private ttsWeb(text: string, lang = 'fr-FR', rate = 0.9): Promise<boolean> {
    if (!('speechSynthesis' in window)) return Promise.resolve(false)

    window.speechSynthesis.cancel()
    return new Promise(resolve => {
      const utterance = new SpeechSynthesisUtterance(text)
      this.currentUtterance = utterance
      utterance.lang = lang
      utterance.rate = rate
      utterance.pitch = 1.0

      const voices = window.speechSynthesis.getVoices()
      const match = voices.find(voice => voice.lang.startsWith(lang.split('-')[0]))
      if (match) utterance.voice = match

      let reported = false
      const watchdog = window.setTimeout(() => {
        if (window.speechSynthesis.speaking || window.speechSynthesis.pending) report(true)
      }, 1200)
      const failureTimeout = window.setTimeout(() => report(false), 5000)
      const report = (success: boolean) => {
        if (reported) return
        reported = true
        window.clearTimeout(watchdog)
        window.clearTimeout(failureTimeout)
        resolve(success)
      }
      utterance.onstart = () => report(true)
      utterance.onend = () => {
        if (this.currentUtterance === utterance) this.currentUtterance = null
        report(true)
      }
      utterance.onerror = () => {
        if (this.currentUtterance === utterance) this.currentUtterance = null
        report(false)
      }

      try {
        window.speechSynthesis.resume()
        window.speechSynthesis.speak(utterance)
      } catch {
        report(false)
      }
    })
  }

  private async tts(text: string, lang = 'fr-FR', rate = 0.9): Promise<boolean> {
    if (isCapacitor()) {
      const spoken = await this.ttsCapacitor(text, lang, rate)
      if (spoken) return true
    }
    return this.ttsWeb(text, lang, rate)
  }

  greeting() {
    if (!this.playMp3('greeting')) void this.tts(`Bonjour ${this.childName} ! Bienvenue dans EduMaison !`)
  }

  scheduleGreeting(delay = 500) {
    this.cancelScheduledSpeech()
    this.scheduledSpeech = window.setTimeout(() => {
      this.scheduledSpeech = null
      this.greeting()
    }, delay)
  }

  correct() {
    if (!this.playMp3('correct')) void this.tts('Excellent ! Tres bien !')
  }

  wrong() {
    if (!this.playMp3('wrong')) void this.tts('Pas tout a fait. Essaie encore !')
  }

  sessionGood() {
    if (!this.playMp3('session_good')) void this.tts('Bien joue ! Continue comme ca !')
  }

  sessionPerfect() {
    if (!this.playMp3('session_perfect')) void this.tts('Parfait ! Tu es fantastique !')
  }

  sessionRetry() {
    if (!this.playMp3('session_retry')) void this.tts('Courage ! Tu peux faire mieux !')
  }

  streak3() {
    if (!this.playMp3('streak3')) void this.tts('Trois de suite ! Bravo !')
  }

  streak5() {
    if (!this.playMp3('streak5')) void this.tts('Cinq de suite ! Incroyable !')
  }

  speak(text: string, rate = 0.9): Promise<boolean> {
    this.stop()
    return this.tts(text, 'en-GB', rate)
  }

  speakLang(text: string, lang: string, rate = 0.9): Promise<boolean> {
    this.stop()
    return this.tts(text, lang, rate)
  }

  speakLangAfter(text: string, lang: string, delay: number, rate = 0.9) {
    this.stop()
    this.scheduledSpeech = window.setTimeout(() => {
      this.scheduledSpeech = null
      void this.speakLang(text, lang, rate)
    }, delay)
  }

  private cancelScheduledSpeech() {
    if (this.scheduledSpeech === null) return
    window.clearTimeout(this.scheduledSpeech)
    this.scheduledSpeech = null
  }

  private stopAudio() {
    if (!this.currentAudio) return
    this.currentAudio.pause()
    this.currentAudio.currentTime = 0
    this.currentAudio = null
  }

  stop() {
    this.cancelScheduledSpeech()
    this.stopAudio()
    SoundService.stopClip()
    this.currentUtterance = null
    if (isCapacitor()) TextToSpeech.stop().catch(() => {})
    if ('speechSynthesis' in window) window.speechSynthesis.cancel()
  }
}

export const MamaJudi = new MamaJudiClass()
