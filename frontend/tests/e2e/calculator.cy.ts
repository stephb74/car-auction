describe("Home Page", () => {
  it("should load the calculation tool", () => {
    // Act
    cy.visit("/");

    // Assert
    cy.contains("Bid Calculation Tool");
    cy.get("select#vehicle-type").should("exist");
    cy.get("input#vehicle-price").should("exist");
    cy.get("#submit-btn").should("exist");
    cy.get("#reset-btn").should("exist");
    cy.get("#submit-btn").should("be.disabled");
    cy.get("#result-table").should("not.exist");
  });

  it("should enable the submit button when all fields are filled", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("1000");

    // Assert
    cy.get("#submit-btn").should("not.be.disabled");
  });

  it("should not accept a negative vehicle price", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("-1000");

    // Assert
    cy.get("input#vehicle-price").should("have.value", "1000");
  });

  it("should accept a price with 2 decimals", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("1000.01");

    // Assert
    cy.get("input#vehicle-price").should("have.value", "1000.01");
  });

  it("should not allow more than 2 decimal places", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("1000.001");

    // Assert
    cy.get("input#vehicle-price").should("have.value", "1000.00");
  });

  it("should empty the form when the reset button is clicked", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("1000");

    // Act
    cy.get("#reset-btn").click();

    // Assert
    cy.get("input#vehicle-price").should("have.value", "");
    cy.get("#submit-btn").should("be.disabled");
  });

  it("should display the result table when the form is submitted", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Luxury");
    cy.get("input#vehicle-price").type("1800");

    // Act
    cy.get("#submit-btn").click();

    // Assert
    cy.get("#result-table").should("exist");
  });

  it("should hide the result table when the reset button is clicked", () => {
    // Arrange
    cy.visit("/");
    cy.get("select#vehicle-type").select("Common");
    cy.get("input#vehicle-price").type("398");

    // Act
    cy.get("#submit-btn").click();
    cy.get("#reset-btn").click();

    // Assert
    cy.get("#result-table").should("not.exist");
  });
});
