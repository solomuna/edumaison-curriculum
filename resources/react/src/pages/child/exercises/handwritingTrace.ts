export interface HandwritingPoint {
  x: number
  y: number
  t: number
}

export type HandwritingStroke = HandwritingPoint[]

export interface HandwritingTraceAssessment {
  score: number
  precision: number
  coverage: number
  passed: boolean
  detectedWords?: number
  expectedWords?: number
}

export type HandwritingGuideStyle = 'print' | 'upright_joint_script'
export type HandwritingPracticeMode = 'trace' | 'copy'

interface PromptLine {
  text: string
  x: number
  y: number
}

interface PromptLayout {
  fontSize: number
  lines: PromptLine[]
}

const PRINT_FONT_FAMILY = 'Nunito, Arial, sans-serif'
const SCHOOL_HAND_FONT_FAMILY = '"Schoolbell", sans-serif'

function fontSpec(style: HandwritingGuideStyle, fontSize: number): string {
  const weight = style === 'upright_joint_script' ? 400 : 800
  const family = style === 'upright_joint_script' ? SCHOOL_HAND_FONT_FAMILY : PRINT_FONT_FAMILY
  return `${weight} ${fontSize}px ${family}`
}

function wrapPrompt(context: CanvasRenderingContext2D, prompt: string, maximumWidth: number): string[] {
  const words = prompt.trim().split(/\s+/).filter(Boolean)
  if (words.length <= 1) return [prompt]

  const lines: string[] = []
  let line = words[0]
  for (const word of words.slice(1)) {
    const candidate = `${line} ${word}`
    if (context.measureText(candidate).width <= maximumWidth) line = candidate
    else {
      lines.push(line)
      line = word
    }
  }
  lines.push(line)
  return lines
}

function promptLayout(
  context: CanvasRenderingContext2D,
  prompt: string,
  width: number,
  height: number,
  style: HandwritingGuideStyle,
): PromptLayout {
  const maximumWidth = Math.max(80, width - 36)
  const maximumHeight = Math.max(80, height - 34)
  const startingSize = prompt.length === 1 ? Math.min(150, height * 0.68) : prompt.length <= 12 ? Math.min(92, height * 0.48) : Math.min(54, height * 0.28)

  let fontSize = startingSize
  let lines = [prompt]
  while (fontSize >= 20) {
    context.font = fontSpec(style, fontSize)
    lines = wrapPrompt(context, prompt, maximumWidth)
    const lineHeight = fontSize * 1.18
    const widest = Math.max(...lines.map(line => context.measureText(line).width))
    if (widest <= maximumWidth && lines.length * lineHeight <= maximumHeight) break
    fontSize -= 2
  }

  const lineHeight = fontSize * 1.18
  const blockHeight = lines.length * lineHeight
  const firstBaseline = (height - blockHeight) / 2 + fontSize * 0.88
  return {
    fontSize,
    lines: lines.map((line, index) => ({ text: line, x: width / 2, y: firstBaseline + index * lineHeight })),
  }
}

function paintPrompt(
  context: CanvasRenderingContext2D,
  prompt: string,
  width: number,
  height: number,
  options: {
    fill: string
    style: HandwritingGuideStyle
    stroke?: string
    strokeWidth?: number
    baselines?: boolean
  },
) {
  const layout = promptLayout(context, prompt, width, height, options.style)
  context.save()
  context.font = fontSpec(options.style, layout.fontSize)
  context.textAlign = 'center'
  context.textBaseline = 'alphabetic'
  context.lineJoin = 'round'
  context.lineCap = 'round'
  context.fillStyle = options.fill
  if (options.stroke) context.strokeStyle = options.stroke
  if (options.strokeWidth) context.lineWidth = options.strokeWidth

  for (const line of layout.lines) {
    if (options.baselines) {
      const guides = options.style === 'upright_joint_script'
        ? [
            { y: line.y - layout.fontSize * 0.76, dash: [] as number[], color: '#D7B48C' },
            { y: line.y - layout.fontSize * 0.43, dash: [5, 5], color: '#E2B52C' },
            { y: line.y + 3, dash: [], color: '#C78555' },
            { y: line.y + layout.fontSize * 0.24, dash: [5, 5], color: '#D7B48C' },
          ]
        : [{ y: line.y + 3, dash: [6, 6], color: '#E8B88D' }]
      context.save()
      context.lineWidth = 1.15
      for (const guide of guides) {
        context.strokeStyle = guide.color
        context.setLineDash(guide.dash)
        context.beginPath()
        context.moveTo(18, guide.y)
        context.lineTo(width - 18, guide.y)
        context.stroke()
      }
      context.restore()
    }
    if (options.stroke) context.strokeText(line.text, line.x, line.y)
    context.fillText(line.text, line.x, line.y)
  }
  context.restore()
}

export function renderHandwritingGuide(
  canvas: HTMLCanvasElement,
  prompt: string,
  style: HandwritingGuideStyle = 'print',
  practiceMode: HandwritingPracticeMode = 'trace',
) {
  const context = canvas.getContext('2d')
  if (!context) return
  const width = canvas.clientWidth
  const height = canvas.clientHeight
  context.clearRect(0, 0, width, height)
  paintPrompt(context, prompt, width, height, {
    fill: practiceMode === 'copy' ? 'rgba(0,0,0,0)' : '#D8C9B8',
    style,
    baselines: true,
  })
  if (practiceMode === 'copy') {
    const middle = height / 2
    context.save()
    context.fillStyle = '#176B3A'
    context.beginPath()
    context.arc(18, middle, 4, 0, Math.PI * 2)
    context.fill()
    context.strokeStyle = '#E2B52C'
    context.fillStyle = '#E2B52C'
    context.lineWidth = 2
    context.beginPath()
    context.moveTo(width - 20, middle - 24)
    context.lineTo(width - 20, middle + 24)
    context.stroke()
    context.beginPath()
    context.moveTo(width - 20, middle - 24)
    context.lineTo(width - 8, middle - 18)
    context.lineTo(width - 20, middle - 12)
    context.closePath()
    context.fill()
    context.restore()
  }
}

function drawTrace(context: CanvasRenderingContext2D, strokes: HandwritingStroke[], width: number, height: number, lineWidth: number) {
  context.save()
  context.strokeStyle = '#FFFFFF'
  context.fillStyle = '#FFFFFF'
  context.lineWidth = lineWidth
  context.lineCap = 'round'
  context.lineJoin = 'round'
  for (const stroke of strokes) {
    if (stroke.length === 0) continue
    if (stroke.length === 1) {
      context.beginPath()
      context.arc(stroke[0].x * width, stroke[0].y * height, lineWidth / 2, 0, Math.PI * 2)
      context.fill()
      continue
    }
    context.beginPath()
    context.moveTo(stroke[0].x * width, stroke[0].y * height)
    for (const point of stroke.slice(1)) context.lineTo(point.x * width, point.y * height)
    context.stroke()
  }
  context.restore()
}

function pixels(canvas: HTMLCanvasElement): Uint8ClampedArray {
  return canvas.getContext('2d')!.getImageData(0, 0, canvas.width, canvas.height).data
}

function estimateWrittenWordGroups(strokes: HandwritingStroke[]): number {
  const intervals = strokes
    .filter(stroke => stroke.length >= 2)
    .map(stroke => ({
      start: Math.min(...stroke.map(point => point.x)),
      end: Math.max(...stroke.map(point => point.x)),
      top: Math.min(...stroke.map(point => point.y)),
      bottom: Math.max(...stroke.map(point => point.y)),
      points: stroke.length,
    }))
    .filter(interval => interval.end - interval.start <= 0.45)
    .sort((left, right) => left.start - right.start)

  const groups: Array<{ start: number; end: number; top: number; bottom: number; points: number }> = []
  for (const interval of intervals) {
    const current = groups[groups.length - 1]
    if (!current || interval.start - current.end > 0.035) {
      groups.push({ ...interval })
      continue
    }
    current.end = Math.max(current.end, interval.end)
    current.top = Math.min(current.top, interval.top)
    current.bottom = Math.max(current.bottom, interval.bottom)
    current.points += interval.points
  }

  return groups.filter(group => group.end - group.start >= 0.012 || group.points >= 6).length
}

function assessCopyingTrace(prompt: string, strokes: HandwritingStroke[]): HandwritingTraceAssessment {
  const points = strokes.flat()
  const expectedWords = prompt.trim().split(/\s+/).filter(Boolean).length
  if (points.length === 0) return { score: 0, precision: 0, coverage: 0, passed: false, detectedWords: 0, expectedWords }

  let distance = 0
  for (const stroke of strokes) {
    for (let index = 1; index < stroke.length; index += 1) {
      distance += Math.hypot(stroke[index].x - stroke[index - 1].x, stroke[index].y - stroke[index - 1].y)
    }
  }
  const xValues = points.map(point => point.x)
  const yValues = points.map(point => point.y)
  const spanX = Math.max(...xValues) - Math.min(...xValues)
  const spanY = Math.max(...yValues) - Math.min(...yValues)
  const characterCount = prompt.replace(/\s+/g, '').length
  const minimumPoints = Math.max(24, Math.min(100, characterCount * 3))
  const minimumDistance = Math.max(0.8, Math.min(6, characterCount * 0.14))
  const minimumSpanX = Math.max(0.58, Math.min(0.78, 0.44 + characterCount * 0.015))
  const pageUse = Math.min(100, Math.round(spanX / minimumSpanX * 100))
  const writingEffort = Math.min(100, Math.round(Math.min(points.length / minimumPoints, distance / minimumDistance) * 100))
  const detectedWords = estimateWrittenWordGroups(strokes)
  const wordProgress = expectedWords > 0 ? Math.min(100, Math.round(detectedWords / expectedWords * 100)) : 0
  const score = Math.round(pageUse * 0.35 + writingEffort * 0.25 + wordProgress * 0.4)

  return {
    score,
    precision: pageUse,
    coverage: writingEffort,
    detectedWords,
    expectedWords,
    passed: detectedWords >= expectedWords
      && points.length >= minimumPoints
      && distance >= minimumDistance
      && spanX >= minimumSpanX
      && spanY >= 0.03
      && spanY <= 0.9,
  }
}

export function assessHandwritingTrace(
  prompt: string,
  strokes: HandwritingStroke[],
  style: HandwritingGuideStyle = 'print',
  surfaceAspectRatio = 220 / 360,
  practiceMode: HandwritingPracticeMode = 'trace',
): HandwritingTraceAssessment {
  if (practiceMode === 'copy') return assessCopyingTrace(prompt, strokes)

  const width = 360
  const height = Math.max(150, Math.min(420, Math.round(width * surfaceAspectRatio)))
  const create = () => {
    const canvas = document.createElement('canvas')
    canvas.width = width
    canvas.height = height
    return canvas
  }
  const target = create()
  const targetTolerance = create()
  const ink = create()
  const expandedInk = create()

  paintPrompt(target.getContext('2d')!, prompt, width, height, { fill: '#FFFFFF', style })
  paintPrompt(targetTolerance.getContext('2d')!, prompt, width, height, { fill: '#FFFFFF', style, stroke: '#FFFFFF', strokeWidth: 18 })
  drawTrace(ink.getContext('2d')!, strokes, width, height, 7)
  drawTrace(expandedInk.getContext('2d')!, strokes, width, height, 22)

  const targetPixels = pixels(target)
  const tolerancePixels = pixels(targetTolerance)
  const inkPixels = pixels(ink)
  const expandedPixels = pixels(expandedInk)
  let inkCount = 0
  let inkOnTarget = 0
  let targetCount = 0
  let targetCovered = 0
  for (let index = 3; index < targetPixels.length; index += 4) {
    const hasTarget = targetPixels[index] > 20
    const hasInk = inkPixels[index] > 20
    if (hasInk) {
      inkCount += 1
      if (tolerancePixels[index] > 20) inkOnTarget += 1
    }
    if (hasTarget) {
      targetCount += 1
      if (expandedPixels[index] > 20) targetCovered += 1
    }
  }

  const precision = inkCount > 0 ? Math.round(inkOnTarget / inkCount * 100) : 0
  const coverage = targetCount > 0 ? Math.round(targetCovered / targetCount * 100) : 0
  const score = Math.round(precision * 0.6 + coverage * 0.4)
  const compactLength = prompt.replace(/\s+/g, '').length
  const minimumScore = compactLength <= 1 ? 46 : compactLength <= 10 ? 40 : 34
  const minimumCoverage = compactLength <= 1 ? 16 : compactLength <= 10 ? 12 : 9

  return {
    score,
    precision,
    coverage,
    passed: score >= minimumScore && precision >= 45 && coverage >= minimumCoverage,
  }
}
