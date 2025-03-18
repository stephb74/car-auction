/**
 * Validate the input value to allow only numbers and decimal point
 * @param event
 */
export const validatePrice = (event: KeyboardEvent) => {
  const input = event.target as HTMLInputElement;
  const invalidChars = /[^0-9.]/;

  // Allow only numbers, decimal point, backspace, and delete
  if (
    invalidChars.test(event.key) &&
    event.key !== "Backspace" &&
    event.key !== "Delete"
  ) {
    event.preventDefault();
  }

  // Allow only up to 2 decimal places
  const valueParts = input.value.split(".");
  if (
    valueParts.length > 1 &&
    valueParts[1].length >= 2 &&
    event.key !== "Backspace" &&
    event.key !== "Delete"
  ) {
    event.preventDefault();
  }
};
