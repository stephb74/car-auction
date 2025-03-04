/**
 * Format currency value to include currency symbol
 * @param value
 * @param currencySymbol
 */
export const formatCurrency = (
  value: number,
  currencySymbol: string
): string => {
  return `${currencySymbol}${value.toFixed(2)}`;
};
