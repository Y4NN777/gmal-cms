import { useI18n } from 'vue-i18n'

export function useStatus() {
  const { t } = useI18n()
  
  const translateStatus = (status) => {
    if (!status) return ''
    
    // Normalize status to lowercase for consistent lookup
    const normalizedStatus = status.toString().toLowerCase()
    const translationKey = `status.${normalizedStatus}`
    const translated = t(translationKey)
    
    // If translation exists, return it; otherwise return original
    if (translated !== translationKey) {
      return translated
    }
    
    return status
  }
  
  return {
    translateStatus
  }
}
