export type ChildGuideCharacter = 'boy' | 'girl'
export type ChildGuidePoseName = 'explore' | 'think' | 'read' | 'discover'

const poseAssets = {
  boy: {
    explore: {
      src: '/images/adventure/characters/child-guide-boy/explore-v1.webp',
      width: 1024,
      height: 1536,
      alt: 'The boy guide points toward the next learning destination',
    },
    think: {
      src: '/images/adventure/characters/child-guide-boy/think-v1.webp',
      width: 1024,
      height: 1536,
      alt: 'The boy guide thinks while looking at his notebook',
    },
  },
  girl: {
    read: {
      src: '/images/adventure/characters/child-guide-girl/read-v1.webp',
      width: 1122,
      height: 1402,
      alt: 'The girl guide reads an illustrated book',
    },
    discover: {
      src: '/images/adventure/characters/child-guide-girl/discover-v1.webp',
      width: 1122,
      height: 1402,
      alt: 'The girl guide spots the next learning destination',
    },
  },
} as const

type BoyPose = keyof typeof poseAssets.boy
type GirlPose = keyof typeof poseAssets.girl

type Props =
  | { character: 'boy'; pose: BoyPose; className?: string; decorative?: boolean }
  | { character: 'girl'; pose: GirlPose; className?: string; decorative?: boolean }

export default function ChildGuidePose({ character, pose, className = '', decorative = false }: Props) {
  const asset = character === 'boy'
    ? poseAssets.boy[pose as BoyPose]
    : poseAssets.girl[pose as GirlPose]

  return (
    <figure className={`child-guide-pose child-guide-pose--${character}-${pose} ${className}`.trim()}>
      <img
        src={asset.src}
        alt={decorative ? '' : asset.alt}
        width={asset.width}
        height={asset.height}
      />
    </figure>
  )
}
