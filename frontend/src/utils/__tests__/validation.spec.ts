import { validatePrice } from "@/utils/validation";

describe("validatePrice", () => {
  it("should allow only numbers and decimal point", () => {
    // Arrange
    const event = {
      key: "1",
      target: {
        value: "1",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).not.toHaveBeenCalled();
  });

  it("should allow backspace and delete", () => {
    // Arrange
    const event = {
      key: "Backspace",
      target: {
        value: "1",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).not.toHaveBeenCalled();
  });

  it("should prevent invalid characters", () => {
    // Arrange
    const event = {
      key: "a",
      target: {
        value: "1",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).toHaveBeenCalled();
  });

  it("should allow only up to 2 decimal places", () => {
    // Arrange
    const event = {
      key: "1",
      target: {
        value: "1.23",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).toHaveBeenCalled();
  });

  it("should prevent more than 2 decimal places", () => {
    // Arrange
    const event = {
      key: "4",
      target: {
        value: "1.23",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).toHaveBeenCalled();
  });

  it("should allow backspace and delete when more than 2 decimal places", () => {
    // Arrange
    const event = {
      key: "Backspace",
      target: {
        value: "1.234",
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).not.toHaveBeenCalled();
  });
});
