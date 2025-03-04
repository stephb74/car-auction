/**
 * Validate the input value to allow only numbers and decimal point
 * @param event
 */
export const validatePrice = (event: KeyboardEvent) => {
  const target = event.target as HTMLInputElement;

  // Allow backspace, delete, arrow keys, and tab
  if (
    event.key === "Backspace" ||
    event.key === "Delete" ||
    event.key === "ArrowLeft" ||
    event.key === "ArrowRight" ||
    event.key === "Tab"
  ) {
    return;
  }

  // Prevent negative values
  if (event.key === "-") {
    event.preventDefault();
    return;
  }

  // Allow only one decimal point
  if (event.key === "." && target.value.includes(".")) {
    event.preventDefault();
    return;
  }

  // Allow only up to 2 decimal places
  const decimalIndex = target.value.indexOf(".");
  if (decimalIndex !== -1 && target.value.length - decimalIndex > 2) {
    event.preventDefault();
    return;
  }
};
