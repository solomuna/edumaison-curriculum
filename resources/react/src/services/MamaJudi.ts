// MamaJudi.ts — TTS service : Web Speech API (browser) + Capacitor TTS (Android)
import { TextToSpeech } from '@capacitor-community/text-to-speech'

const CHILD_KEY: Record<string, string> = {
  'Irma':  'gabi',
  'Mark':  'mark',
  'Ruth':  'carla',
  'Carla': 'carla',
  'Julia': '',
}
type MamaEvent = 'greeting' | 'correct' | 'wrong' | 'session_good' | 'session_perfect' | 'session_retry' | 'streak3' | 'streak5'

// Détecte si on tourne dans Capacitor (Android/iOS)
const isCapacitor = () => typeof (window as any).Capacitor !== 'undefined' && (window as any).Capacitor.isNativePlatform()

class MamaJudiClass {
  private currentAudio: HTMLAudioElement | null = null
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

  private playMp3(event: MamaEvent): boolean {
    const key = this.getKey()
    if (!key) return false
    const src = `/sounds/mama/${event}_${key}.mp3`
    this.stopAudio()
    this.currentAudio = new Audio(src)
    this.currentAudio.play().catch(() => {})
    return true
  }

  private async ttsCapacitor(text: string, lang = 'fr-FR') {
    try {
      await TextToSpeech.stop()
      await TextToSpeech.speak({
        text,
        lang,
        rate: 0.9,
        pitch: 1.0,
        volume: 1.0,
        category: 'ambient',
      })
    } catch (e) {
      console.warn('Capacitor TTS error:', e)
    }
  }

  private ttsWeb(text: string, lang = 'fr-FR') {
    if (!('speechSynthesis' in window)) return
    window.speechSynthesis.cancel()
    const utter = new SpeechSynthesisUtterance(text)
    utter.lang = lang
    utter.rate = 0.9
    utter.pitch = 1.0
    const voices = window.speechSynthesis.getVoices()
    if (voices.length > 0) {
      const match = voices.find(v => v.lang.startsWith(lang.split('-')[0]))
      if (match) utter.voice = match
    }
    window.speechSynthesis.speak(utter)
  }

  private tts(text: string, lang = 'fr-FR') {
    if (isCapacitor()) {
      this.ttsCapacitor(text, lang)
    } else {
      this.ttsWeb(text, lang)
    }
  }

  // ── Messages Mama Judi ────────────────────────────────────────────────────
  greeting() {
    if (!this.playMp3('greeting')) this.tts(`Bonjour ${this.childName} ! Bienvenue dans EduMaison !`)
  }
  correct() {
    if (!this.playMp3('correct')) this.tts('Excellent ! Tres bien !')
  }
  wrong() {
    if (!this.playMp3('wrong')) this.tts('Pas tout a fait. Essaie encore !')
  }
  sessionGood() {
    if (!this.playMp3('session_good')) this.tts('Bien joue ! Continue comme ca !')
  }
  sessionPerfect() {
    if (!this.playMp3('session_perfect')) this.tts('Parfait ! Tu es fantastique !')
  }
  sessionRetry() {
    if (!this.playMp3('session_retry')) this.tts('Courage ! Tu peux faire mieux !')
  }
  streak3() {
    if (!this.playMp3('streak3')) this.tts('Trois de suite ! Bravo !')
  }
  streak5() {
    if (!this.playMp3('streak5')) this.tts('Cinq de suite ! Incroyable !')
  }

  // ── TTS exercices ─────────────────────────────────────────────────────────
  speak(text: string) {
    this.stop()
    this.tts(text, 'en-GB')
  }

  speakLang(text: string, lang: string) {
    this.stop()
    this.tts(text, lang)
  }

  private stopAudio() {
    if (this.currentAudio) {
      this.currentAudio.pause()
      this.currentAudio.currentTime = 0
      this.currentAudio = null
    }
  }

  stop() {
    this.stopAudio()
    if (isCapacitor()) {
      TextToSpeech.stop().catch(() => {})
    } else if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel()
    }
  }
}

export const MamaJudi = new MamaJudiClass()
