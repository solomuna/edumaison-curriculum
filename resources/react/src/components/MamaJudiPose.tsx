import type { ReactNode } from 'react'

export type MamaJudiPoseName = 'explain' | 'encourage' | 'celebrate'

const poseAssets: Record<MamaJudiPoseName, { src: string; alt: string }> = {
  explain: {
    src: '/images/adventure/characters/mama-judi/explain-v1.webp',
    alt: 'Mama Judi points toward the next lesson',
  },
  encourage: {
    src: '/images/adventure/characters/mama-judi/encourage-v1.webp',
    alt: 'Mama Judi gives an encouraging thumbs-up',
  },
  celebrate: {
    src: '/images/adventure/characters/mama-judi/celebrate-v1.webp',
    alt: 'Mama Judi celebrates a learning success',
  },
}

interface Props {
  pose: MamaJudiPoseName
  children?: ReactNode
  className?: string
  decorative?: boolean
}

export default function MamaJudiPose({ pose, children, className = '', decorative = false }: Props) {
  const asset = poseAssets[pose]

  return (
    <figure className={`mama-judi-pose mama-judi-pose--${pose} ${className}`.trim()}>
      <img src={asset.src} alt={decorative ? '' : asset.alt} width={1024} height={1536} />
      {children && <figcaption>{children}</figcaption>}
    </figure>
  )
}
