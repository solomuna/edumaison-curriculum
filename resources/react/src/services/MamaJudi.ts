// MamaJudi.ts - TTS service: Web Speech API (browser) + Capacitor TTS (Android)
import { TextToSpeech } from '@capacitor-community/text-to-speech'
import { SoundService } from './SoundService'

const CHILD_KEY: Record<string, string> = {
  Irma: 'gabi',
  Mark: 'mark',
  Ruth: 'carla',
  Carla: 'carla',
  Julia: '',
}

type MamaEvent = 'greeting' | 'correct' | 'wrong' | 'session_good' | 'session_perfect' | 'session_retry' | 'streak3' | 'streak5'

const isCapacitor = () => typeof (window as any).Capacitor !== 'undefined' && (window as any).Capacitor.isNativePlatform()

class MamaJudiClass {
  private currentAudio: HTMLAudioElement | null = null
  private currentUtterance: SpeechSynthesisUtterance | null = null
  private scheduledSpeech: number | null = null
  private childName = ''

  setChild(name: string) {
    this.childName = name.split(' ')[0]
    localStorage.setItem('edumaison_child', this.childName)
  }

  private resolveChild(): string {
    if (this.childName) return this.childName
    return localStorage.getItem('edumaison_child') || ''
  }

  private getKey(): string {
    const name = this.resolveChild()
    return CHILD_KEY[name] ?? ''
  }

  private clipUrl(event: MamaEvent): string | null {
    const key = this.getKey()
    return key ? `/sounds/mama/${event}_${key}.mp3` : null
  }

  private playMp3(event: MamaEvent): boolean {
    const src = this.clipUrl(event)
    if (!src) return false
    this.stopAudio()
    // Clip déjà décodé : lecture instantanée. Sinon lecture classique (moins réactive).
    if (SoundService.play(src, { exclusive: true })) return true
    this.currentAudio = new Audio(src)
    this.currentAudio.play().catch(() => {})
    return true
  }

  /** Précharge les voix enregistrées de l'enfant courant (lecture instantanée ensuite). */
  preloadVoices(events: MamaEvent[] = ['correct', 'wrong', 'streak3', 'streak5', 'session_good', 'session_perfect', 'session_retry']) {
    events.reduce<Promise<void>>((p, event) => {
      const src = this.clipUrl(event)
      return src ? p.then(() => SoundService.load(src)) : p
    }, Promise.resolve())
  }

  /**
   * Réaction immédiate (bonne réponse, erreur, série) : joue la voix enregistrée
   * seulement si elle est prête. Jamais de synthèse vocale ici : elle démarre
   * trop tard et arriverait décalée par rapport à l'écran.
   */
  react(event: MamaEvent): boolean {
    const src = this.clipUrl(event)
    if (!src || !SoundService.isReady(src)) return false
    this.stopAudio()
    return SoundService.play(src, { exclusive: true })
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
