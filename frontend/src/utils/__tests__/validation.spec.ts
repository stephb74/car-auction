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

  it("should prevent negative values", () => {
    // Arrange
    const event = {
      key: "-",
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

  it("should allow the backspace key", () => {
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

  it("should prevent entering multiple decimal points", () => {
    // Arrange
    const event = {
      key: ".",
      target: {
        value: "1.0",
        includes: jest.fn().mockReturnValue(true),
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
        value: "1.00",
        indexOf: jest.fn().mockReturnValue(1),
      },
      preventDefault: jest.fn(),
    } as unknown as KeyboardEvent;

    // Act
    validatePrice(event);

    // Assert
    expect(event.preventDefault).toHaveBeenCalled();
  });
});
