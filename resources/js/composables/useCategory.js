import { useI18n } from 'vue-i18n';

export function useCategory() {
  const { t } = useI18n();

  const translateCategory = (category) => {
    if (!category) return '';
    
    // Try to translate by slug first
    if (category.slug) {
      const translationKey = `categories.${category.slug}`;
      const translated = t(translationKey);
      
      // If translation exists (doesn't return the key), use it
      if (translated !== translationKey) {
        return translated;
      }
    }
    
    // Fallback to the original name
    return category.name;
  };

  return {
    translateCategory
  };
}
