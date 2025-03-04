import { describe, it, expect, jest, beforeEach } from "@jest/globals";
import vehicleService from "@/services/VehicleService";
import useCalculatorForm from "@/composables/useCalculatorForm";

jest.mock("@/services/VehicleService", () => ({
  fetchVehicleTypes: jest.fn(),
  calculateCarPrice: jest.fn(), // Ensure this is included
}));

describe("useCalculatorForm", () => {
  let emitMock: ReturnType<typeof jest.fn>;
  let calculatorForm: ReturnType<typeof useCalculatorForm>;

  beforeEach(() => {
    emitMock = jest.fn();
    calculatorForm = useCalculatorForm(emitMock);
  });

  describe("fetchVehicleTypes", () => {
    it("fetches vehicle types and sets the first one as default", async () => {
      const mockVehicleTypes = [
        { id: 1, name: "Common" },
        { id: 2, name: "Luxury" },
      ];

      (vehicleService.fetchVehicleTypes as jest.Mock).mockResolvedValue(
        mockVehicleTypes
      );

      await calculatorForm.fetchVehicleTypes();

      expect(calculatorForm.vehicleTypes.value).toEqual(mockVehicleTypes);
      expect(calculatorForm.selectedVehicleTypeId.value).toBe(1);
    });

    it("sets an error message when fetchVehicleTypes fails", async () => {
      (vehicleService.fetchVehicleTypes as jest.Mock).mockRejectedValue(
        new Error("Network error")
      );

      await calculatorForm.fetchVehicleTypes();

      expect(calculatorForm.errorMessage.value).toBe(
        "Failed to load vehicle types. Please refresh the page."
      );
    });

    it("does not set selectedVehicleTypeId if no vehicle types are returned", async () => {
      (vehicleService.fetchVehicleTypes as jest.Mock).mockResolvedValue([]);

      await calculatorForm.fetchVehicleTypes();

      expect(calculatorForm.vehicleTypes.value).toEqual([]);
      expect(calculatorForm.selectedVehicleTypeId.value).toBeNull();
    });

    it("sets vehicleTypes to an empty array when fetchVehicleTypes returns null or undefined", async () => {
      (vehicleService.fetchVehicleTypes as jest.Mock).mockResolvedValue(null);

      await calculatorForm.fetchVehicleTypes();

      expect(calculatorForm.vehicleTypes.value).toEqual([]);
    });
  });

  describe("isFormValid", () => {
    it("returns true when the form is valid", () => {
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 20000;

      calculatorForm.formIsValid.value = true;

      expect(calculatorForm.formIsValid.value).toBe(true);
    });

    it("returns false when vehiclePrice is null", () => {
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = null;

      calculatorForm.formIsValid.value = false;

      expect(calculatorForm.formIsValid.value).toBe(false);
    });

    it("returns false when vehiclePrice is zero", () => {
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 0;

      calculatorForm.formIsValid.value = false;

      expect(calculatorForm.formIsValid.value).toBe(false);
    });

    it("returns true when vehiclePrice is greater than 0", () => {
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehicleTypes.value = [{ id: 1, name: "Common" }];
      calculatorForm.vehiclePrice.value = 10000;

      calculatorForm.formIsValid.value = calculatorForm.isFormValid();

      expect(calculatorForm.formIsValid.value).toBe(true);
    });
  });

  describe("fetchCarPrice", () => {
    it("fetches car price from the API successfully", async () => {
      (vehicleService.calculateCarPrice as jest.Mock).mockResolvedValue(15000);

      const price = await calculatorForm.fetchCarPrice(1, 20000);

      expect(price).toBe(15000);
    });

    it("sets an error message and returns null when fetching car price fails", async () => {
      (vehicleService.calculateCarPrice as jest.Mock).mockRejectedValue(
        new Error("API Error")
      );

      const price = await calculatorForm.fetchCarPrice(1, 20000);

      expect(calculatorForm.errorMessage.value).toBe(
        "An error occurred while calculating the price."
      );
      expect(price).toBeNull();
    });
  });

  describe("resetForm", () => {
    it("resets all form values to their initial state", () => {
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 25000;
      calculatorForm.errorMessage.value = "Some error";
      calculatorForm.formIsValid.value = true;

      calculatorForm.resetForm();

      expect(calculatorForm.selectedVehicleTypeId.value).toBeNull();
      expect(calculatorForm.vehiclePrice.value).toBeNull();
      expect(calculatorForm.errorMessage.value).toBeNull();
      expect(calculatorForm.formIsValid.value).toBe(false);
    });

    it("resets selectedVehicleTypeId to the first vehicle type's ID if available", () => {
      calculatorForm.vehicleTypes.value = [
        { id: 5, name: "Common" },
        { id: 10, name: "Luxury" },
      ];
      calculatorForm.selectedVehicleTypeId.value = 3;
      calculatorForm.vehiclePrice.value = 25000;
      calculatorForm.errorMessage.value = "Some error";
      calculatorForm.formIsValid.value = true;

      calculatorForm.resetForm();

      expect(calculatorForm.selectedVehicleTypeId.value).toBe(5); // First vehicle type's ID
      expect(calculatorForm.vehiclePrice.value).toBeNull();
      expect(calculatorForm.errorMessage.value).toBeNull();
      expect(calculatorForm.formIsValid.value).toBe(false);
    });
  });

  describe("validateForm", () => {
    it("returns true when the form is valid", () => {
      calculatorForm.vehicleTypes.value = [{ id: 1, name: "Common" }]; // Ensure vehicle type exists
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 20000;
      calculatorForm.formIsValid.value = true; // Ensure form is considered valid

      const result = calculatorForm.validateForm();

      expect(result).toBe(true);
    });

    it("returns false and sets an error message when the form is invalid", () => {
      calculatorForm.selectedVehicleTypeId.value = null;
      calculatorForm.vehiclePrice.value = null;

      const result = calculatorForm.validateForm();

      expect(result).toBe(false);
      expect(calculatorForm.errorMessage.value).toBe(
        "Please select a valid vehicle type and enter a valid price."
      );
    });
  });

  describe("getSelectedVehicleType", () => {
    it("returns the correct vehicle type when a valid ID is selected", () => {
      calculatorForm.vehicleTypes.value = [
        { id: 1, name: "Sedan" },
        { id: 2, name: "SUV" },
      ];
      calculatorForm.selectedVehicleTypeId.value = 2;

      const result = calculatorForm.getSelectedVehicleType();

      expect(result).toEqual({ id: 2, name: "SUV" });
    });

    it("returns null when selectedVehicleTypeId is null", () => {
      calculatorForm.vehicleTypes.value = [
        { id: 1, name: "Sedan" },
        { id: 2, name: "SUV" },
      ];
      calculatorForm.selectedVehicleTypeId.value = null;

      const result = calculatorForm.getSelectedVehicleType();

      expect(result).toBeNull();
    });
  });

  describe("handleFormSubmit", () => {
    it("submits the form successfully and emits the correct data", async () => {
      calculatorForm.vehicleTypes.value = [{ id: 1, name: "Sedan" }];
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 20000;
      calculatorForm.formIsValid.value = true;
      (vehicleService.calculateCarPrice as jest.Mock).mockResolvedValue(25000);

      await calculatorForm.handleFormSubmit();

      expect(emitMock).toHaveBeenCalledWith("update:resultTableData", {
        vehicleTypeName: "Sedan",
        basePrice: 20000,
        carPriceResult: 25000,
      });
      expect(calculatorForm.errorMessage.value).toBeNull();
    });

    it("sets an error message when the form is invalid", async () => {
      calculatorForm.selectedVehicleTypeId.value = null;
      calculatorForm.vehiclePrice.value = null;

      await calculatorForm.handleFormSubmit();

      expect(calculatorForm.errorMessage.value).toBe(
        "Please select a valid vehicle type and enter a valid price."
      );
      expect(emitMock).not.toHaveBeenCalled();
    });

    it("sets an error message when the vehicle type is not found", async () => {
      calculatorForm.vehicleTypes.value = [{ id: 2, name: "SUV" }];
      calculatorForm.selectedVehicleTypeId.value = 2; // Exists initially
      calculatorForm.vehiclePrice.value = 20000;

      // Mock the scenario explicitly by altering the selectedVehicleTypeId after initial validation passes
      calculatorForm.selectedVehicleTypeId.value = 99; // Non-existent ID to trigger lookup failure

      await calculatorForm.handleFormSubmit();

      expect(calculatorForm.errorMessage.value).toBe(
        "Please select a valid vehicle type and enter a valid price."
      );
      expect(emitMock).not.toHaveBeenCalled();
    });

    it("sets an error message when car price calculation fails", async () => {
      calculatorForm.vehicleTypes.value = [{ id: 1, name: "Sedan" }];
      calculatorForm.selectedVehicleTypeId.value = 1;
      calculatorForm.vehiclePrice.value = 20000;
      calculatorForm.formIsValid.value = true;

      (vehicleService.calculateCarPrice as jest.Mock).mockResolvedValue(null); // simulate failed calculation

      await calculatorForm.handleFormSubmit();

      expect(calculatorForm.errorMessage.value).toBe(
        "Failed to calculate the car price."
      );
      expect(emitMock).not.toHaveBeenCalled();
    });

    it("returns early without emitting when selected vehicle type is null", async () => {
      calculatorForm.vehicleTypes.value = [{ id: 1, name: "Sedan" }];
      calculatorForm.selectedVehicleTypeId.value = null; // This will cause validation to fail
      calculatorForm.vehiclePrice.value = 20000;

      await calculatorForm.handleFormSubmit();

      expect(emitMock).not.toHaveBeenCalled();
      expect(calculatorForm.errorMessage.value).toBe(
        "Please select a valid vehicle type and enter a valid price."
      );
    });
  });
});
