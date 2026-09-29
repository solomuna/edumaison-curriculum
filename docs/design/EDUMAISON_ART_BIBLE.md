# EduMaison Art Bible

Status: draft v1 for visual approval. The images in `docs/design/characters` are identity masters, not runtime assets.

## Product direction

EduMaison is a Cameroonian learning adventure grounded in real school subjects, family support and visible progress. The visual language is warm, energetic and polished without introducing fantasy combat, shops, weapons or unrelated mascots.

The illustrated world must remain secondary to learning actions. Text, scores, buttons and exercise content are always rendered by the application, never baked into an illustration.

## Character canon

### Mama Judi

Reference files:

- `public/images/default-companion.png`: canonical portrait and face.
- `docs/design/characters/mama-judi-master-v1.png`: full-body identity and outfit draft.

Invariants:

- Adult Cameroonian woman with warm brown skin.
- Short natural coiled hair, no headwrap in the primary outfit.
- Green stud earrings.
- Forest-green contemporary dress with restrained coral, gold and teal woven trim.
- Calm, encouraging and competent; never childish or clownish.
- Primary props are a lesson notebook, pointer or progress card.

Core poses to produce after approval: neutral, guide, explain, listen, encourage, celebrate and concerned.

### Child guide, boy

Reference files:

- `public/images/default-child.png`: canonical portrait and face.
- `docs/design/characters/child-guide-boy-master-v1.png`: full-body identity and outfit draft.

Invariants:

- Primary-school age, natural coiled hair and expressive brown eyes.
- Yellow polo with forest-green details, teal shorts and blue backpack.
- Curious and active, never presented as a prize mascot.
- Primary props are a map, book, pencil or school bag.

### Child guide, girl

Reference file:

- `docs/design/characters/child-guide-girl-master-v1.png`.

Invariants:

- Primary-school age with two round natural-hair buns.
- Coral top with yellow and teal woven trim, navy skirt and purple backpack.
- Confident, curious and welcoming.
- Primary props are a picture book, pencil or school bag.

The guide children are fictional product characters. A real child's profile photograph must not be transformed into a generated full-body character without separate explicit consent.

## Environment canon

Primary environment: a welcoming Cameroonian school campus on a green hill, connected by a visible learning path. Mountains, palms, flowering plants, local architecture and the Cameroon flag can establish place without turning the interface into tourism imagery.

Environment families:

- School campus: home, missions and general progress.
- Library: reading, French and English.
- Science garden: science, nature and observation.
- Creative workshop: arts, handwriting and making.
- Community square: citizenship, cultures and social studies.
- Sports field: physical education and movement.

Every environment requires a desktop composition, a mobile composition and declared safe zones for interface content.

## Asset layers

Runtime scenes must be assembled from independent layers:

1. Background environment without people or UI.
2. Character cutouts with genuine alpha transparency.
3. Subject markers and contextual props.
4. Optional lightweight animation layer.
5. React interface and accessible text.

Characters baked into a background are allowed for concept validation only.

## Technical contract

- Identity masters live in `docs/design/characters` and are never shipped to the browser.
- Runtime characters live in `public/images/adventure/characters/<character>/<pose>.webp` or `.png` when alpha is required.
- Backgrounds live in `public/images/adventure/environments/<environment>/<viewport>.webp`.
- All runtime images need explicit width and height metadata in the asset manifest.
- Desktop backgrounds target 1600 to 1920 pixels wide; mobile compositions target 720 to 900 pixels wide.
- Runtime background budget: 450 KB preferred, 700 KB maximum.
- Runtime character budget: 180 KB preferred per pose, 300 KB maximum.
- Do not ship a simulated checkerboard or white background as transparency.
- UI icons remain code or approved icon-library assets; generated text and generated logos are prohibited.

Example manifest entry:

```json
{
  "mamaJudi.guide": {
    "src": "/images/adventure/characters/mama-judi/guide.webp",
    "width": 720,
    "height": 1080,
    "focalPoint": [0.5, 0.35],
    "alt": "Mama Judi points toward the next lesson"
  }
}
```

## Responsive composition

- Desktop may show the full environment, two characters and path markers simultaneously.
- Tablet keeps one guide character and reduces decorative markers.
- Mobile uses a dedicated crop or composition; it must not simply shrink the desktop scene.
- Interactive controls must never be part of the bitmap.
- Safe zones are measured at 390 x 844, 768 x 1024, 1280 x 800 and 1536 x 864 before release.

## Production workflow

1. Approve the identity master before generating poses.
2. Generate new poses only with the approved master as an image reference.
3. Compare face, hair, clothing, age, hands and proportions against the canon.
4. Remove backgrounds and verify the alpha channel programmatically.
5. Optimize the approved asset and record dimensions, byte size and hash.
6. Register the asset in the manifest.
7. Test it in desktop and mobile compositions before deployment.

No unreviewed generated image may be copied directly into a production runtime directory.

## Tenant policy

The character and environment library is shared across tenants. Tenant customization may change school name, logo, uniform accent or a limited palette through configuration. It must not fork the full illustration set unless the tenant funds and owns a separate approved art pack.

## Approval gate for v1

Before producing the full pose library, confirm:

- Mama Judi's face, dress and professional age.
- Boy guide's age, outfit and backpack colors.
- Girl guide's hairstyle, outfit and overall expression.
- Whether these two guide children remain generic or receive stable fictional names.

Known limitation: `mama-judi-master-v1.png` contains a simulated checkerboard background and is therefore reference-only. It requires professional background removal before runtime use.

## Approved runtime batch 1

The first Mama Judi pose batch is registered in `public/images/adventure/assets-v1.json`:

- `explain-v1.webp`: points toward a lesson while holding a notebook.
- `encourage-v1.webp`: supportive hand-on-heart and thumbs-up pose.
- `celebrate-v1.webp`: open-handed success celebration.

All three runtime files are 1024 x 1536 WebP images with a verified `yuva420p` alpha channel. They were composited over a contrasting green test background to verify that the chroma background was removed. The identity master remains reference-only; the runtime poses do not depend on its simulated checkerboard.

## Approved runtime batch 2

The first fictional child-guide pose batch is registered in `public/images/adventure/assets-v1.json`:

- Boy `explore-v1.webp`: points toward a destination while holding a map.
- Boy `think-v1.webp`: studies a blank notebook with a pencil.
- Girl `read-v1.webp`: reads an illustrated book.
- Girl `discover-v1.webp`: looks ahead and reaches toward a destination.

The boy files are 1024 x 1536 and the girl files are 1122 x 1402. All four use WebP `yuva420p`, have verified alpha transparency and remain below 67 KB. They are fictional guides shared by the product and must not be presented as the authenticated child.

## Approved environment batch 1

The school-campus environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/school-campus/desktop-v1.webp`: 1672 x 941, with safe zones for the left mission panel, upper-right progress and central learning path.
- `environments/school-campus/mobile-v1.webp`: 853 x 1844, with a dedicated portrait composition and safe zones for progress, path markers and one guide character.

Both files are people-free, text-free WebP backgrounds. The desktop file is 217,220 bytes and the mobile file is 197,508 bytes, below the preferred 450 KB background budget. The former concept image with baked-in characters is no longer referenced by `AdventureDashboard`.

The local layered composition uses Mama Judi and the girl guide on desktop, and the boy guide on mobile. All mission, progress, subject and navigation controls remain React elements above the illustration layers.

## Approved environment batch 2

The science-garden environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/science-garden/desktop-v1.webp`: 1672 x 941, with a broad left content zone, an upper-right progress zone and a central discovery path.
- `environments/science-garden/mobile-v1.webp`: 853 x 1844, composed specifically for portrait screens with a clear central path and room for one separate guide character.

The environment combines a Cameroonian village-and-forest setting with a stream, footbridge, food and medicinal gardens, an observation shelter and weather instruments. Both backgrounds are people-free, text-free and UI-free. They are associated locally with `Science and Technology` unit and activity lists; all educational content and controls remain independent React layers.

## Approved environment batch 3

The library environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/library/desktop-v1.webp`: 1672 x 941, with a broad left content zone and the reading environment concentrated to the right.
- `environments/library/mobile-v1.webp`: 853 x 1844, composed specifically for portrait screens with a central reading route and room for interface overlays.

The library uses Cameroonian architectural cues, patterned ventilation blocks, tropical courtyard views, colorful blank-spine books, storytelling alcoves and a balanced teal, coral, yellow, green and white palette. Both backgrounds are people-free, text-free and UI-free. They are associated locally with `Reading`, `French` and `English`; all units, exercise cards, progress and actions remain independent React layers.

## Approved environment batch 4

The creative-workshop environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/creative-workshop/desktop-v1.webp`: 1672 x 941, with a broad left content zone and organized making stations on the right.
- `environments/creative-workshop/mobile-v1.webp`: 853 x 1844, composed specifically for portrait screens with a central route and a calm upper content area.

The workshop combines Cameroonian architectural cues with pottery, abstract weaving, blank canvases, colored pencils, paintbrushes, yarn, child-safe craft materials and a tropical courtyard. Both backgrounds are people-free, text-free and UI-free. They are associated locally with `Arts and Crafts`, `Artistic Activities`, `Handwriting`, `Home Economics and Vocational Skills` and `Vocational Studies`; all educational content and controls remain independent React layers.

## Approved environment batch 5

The community-square environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/community-square/desktop-v1.webp`: 1672 x 941, with an open left plaza for content and civic landmarks concentrated on the right.
- `environments/community-square/mobile-v1.webp`: 853 x 1844, composed specifically for portrait screens with a central civic route and calm upper content zone.

The square combines a community hall, meeting pavilion, blank exhibition and notice panels, empty learning canopies, an abstract map table, solar lighting, gardens and distant Cameroonian hills. Both backgrounds are people-free, text-free and UI-free. They are associated locally with `Citizenship`, `Social Studies` and `National Languages and Cultures`; all educational content and controls remain independent React layers.

## Approved environment batch 6

The sports-field environment is registered in `public/images/adventure/assets-v1.json` as two independent runtime compositions:

- `environments/sports-field/desktop-v1.webp`: 1672 x 941, with open track and grass on the left and movement stations concentrated on the right.
- `environments/sports-field/mobile-v1.webp`: 853 x 1844, composed specifically for portrait screens with open sky behind content and a central running route.

The field combines a compact track, grass field, primary-school agility stations, balance beams, hoops, cones, stored skipping ropes, hydration pavilion, tropical landscaping and Cameroonian hills. Both backgrounds are people-free, text-free and UI-free. They are associated locally with `Physical Education`; all educational content and controls remain independent React layers.
