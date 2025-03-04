import { formatCurrency } from "@/utils/strings";

describe("formatCurrency", () => {
  it("should format the currency value with the given symbol", () => {
    // Act
    const result = formatCurrency(1234.56, "$");

    // Assert
    expect(result).toBe("$1234.56");
  });

  it("should format the currency value with two decimal places", () => {
    // Act
    const result = formatCurrency(1234, "$");

    // Assert
    expect(result).toBe("$1234.00");
  });

  it("should handle negative values correctly", () => {
    // Act
    const result = formatCurrency(-1234.56, "$");

    // Assert
    expect(result).toBe("$-1234.56");
  });

  it("should handle zero value correctly", () => {
    // Act
    const result = formatCurrency(0, "$");

    // Assert
    expect(result).toBe("$0.00");
  });

  it("should format the currency value with a different symbol", () => {
    // Act
    const result = formatCurrency(1234.56, "€");

    // Assert
    expect(result).toBe("€1234.56");
  });
});
