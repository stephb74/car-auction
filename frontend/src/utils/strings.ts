export const formatCurrency = (
  value: number,
  currencySymbol: string
): string => {
  return `${currencySymbol}${value.toFixed(2)}`;
};
