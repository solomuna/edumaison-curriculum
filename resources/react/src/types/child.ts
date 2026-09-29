export interface Child {
  id: number
  name: string
  level: string
  level_id?: number
  avatar?: string | null
  national_language?: NationalLanguageProfile
}

export interface NationalLanguageOption {
    language_profile_id: number | null
    language_id: number | null
    source: 'child' | 'household' | 'none'
    code?: string | null
    display_name: string | null
    autonym?: string | null
    family_label?: string | null
    variant_name?: string | null
    is_current?: boolean
    status: 'not_configured' | 'awaiting_catalogue' | 'awaiting_pack' | 'ready'
    pack: { id: number; name: string; content_version?: string | null } | null
}

export interface NationalLanguageProfile extends NationalLanguageOption {
  languages: NationalLanguageOption[]
  can_choose: boolean
}
