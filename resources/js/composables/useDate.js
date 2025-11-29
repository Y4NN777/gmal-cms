export function useDate() {
  const getLocale = () => {
    return document.documentElement.lang || 'en';
  };

  const formatDate = (date, options = {}) => {
    if (!date) return '';
    
    const defaultOptions = {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      ...options
    };
    
    return new Date(date).toLocaleDateString(getLocale(), defaultOptions);
  };

  const formatDateTime = (date) => {
    if (!date) return '';
    
    return new Date(date).toLocaleDateString(getLocale(), {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const formatShortDate = (date) => {
    if (!date) return '';
    
    return new Date(date).toLocaleDateString(getLocale(), {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  };

  return {
    formatDate,
    formatDateTime,
    formatShortDate,
    getLocale,
  };
}
