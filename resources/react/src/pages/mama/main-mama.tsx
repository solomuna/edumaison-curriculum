// main-mama.tsx — Point d'entree Espace Mama Judi
import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import MamaJudiApp from './MamaJudiApp'
import '../../index.css'

createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <MamaJudiApp />
  </StrictMode>
)
