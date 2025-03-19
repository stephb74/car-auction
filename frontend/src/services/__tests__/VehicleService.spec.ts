import VehicleService from "@/services/VehicleService";
import apiClient from "@/api/apiClient";
import type { AxiosResponse } from "axios";
import { VehicleType, CalculateCarPriceResponse } from "@/types/types";

jest.mock("@/api/apiClient");

describe("VehicleService", () => {
  const mockResponse = {
    status: 200,
    statusText: "OK",
    headers: {},
    config: { headers: {} },
  };

  beforeEach(() => {
    jest.clearAllMocks();
    // eslint-disable-next-line @typescript-eslint/no-empty-function
    jest.spyOn(console, "error").mockImplementation(() => {});
  });

  describe("fetchVehicleTypes", () => {
    it("should fetch vehicle types successfully", async () => {
      // Arrange
      const mockVehicleTypes: VehicleType[] = [
        { id: 1, name: "Common" },
        { id: 2, name: "Luxury" },
      ];

      const response = {
        ...mockResponse,
        data: { types: mockVehicleTypes },
      } as AxiosResponse;

      (apiClient.get as jest.Mock).mockResolvedValue(response);

      // Act
      const result = await VehicleService.fetchVehicleTypes();

      // Assert
      expect(apiClient.get).toHaveBeenCalledWith("/vehicles/types");
      expect(result).toEqual(mockVehicleTypes);
    });

    it("should return an empty array if fetching vehicle types fails", async () => {
      // Arrange
      (apiClient.get as jest.Mock).mockRejectedValue(
        new Error("Network Error")
      );

      // Act
      const result = await VehicleService.fetchVehicleTypes();

      // Assert
      expect(apiClient.get).toHaveBeenCalledWith("/vehicles/types");
      expect(result).toEqual([]);
    });

    it("should return empty array when response is undefined", async () => {
      // Arrange
      (apiClient.get as jest.Mock).mockResolvedValue(undefined);

      // Act
      const result = await VehicleService.fetchVehicleTypes();

      // Assert
      expect(apiClient.get).toHaveBeenCalledWith("/vehicles/types");
      expect(result).toEqual([]);
    });

    it("should return empty array when response.data is undefined", async () => {
      // Arrange
      const response = {
        ...mockResponse,
        data: undefined,
      } as AxiosResponse;

      (apiClient.get as jest.Mock).mockResolvedValue(response);

      // Act
      const result = await VehicleService.fetchVehicleTypes();

      expect(apiClient.get).toHaveBeenCalledWith("/vehicles/types");
      expect(result).toEqual([]);
    });
  });

  describe("calculateCarPrice", () => {
    it("should calculate car price successfully", async () => {
      // Arrange
      const mockCarPriceResult: CalculateCarPriceResponse = {
        percentageRate: { rate1: 1.5 },
        fixedTier: { tier1: 100 },
        fixedFee: { fee1: 50 },
        total: 200,
      };

      const response = {
        ...mockResponse,
        data: mockCarPriceResult,
      } as AxiosResponse;

      (apiClient.post as jest.Mock).mockResolvedValue(response);

      // Act
      const result = await VehicleService.calculateCarPrice(10000, 1);

      // Assert
      expect(apiClient.post).toHaveBeenCalledWith("/calculate-car-price/1", {
        price: 10000,
      });
      expect(result).toEqual(mockCarPriceResult);
    });

    it("should return undefined if calculating car price fails", async () => {
      // Arrange
      (apiClient.post as jest.Mock).mockRejectedValue(
        new Error("Network Error")
      );

      const result = await VehicleService.calculateCarPrice(10000, 1);

      // Assert
      expect(apiClient.post).toHaveBeenCalledWith("/calculate-car-price/1", {
        price: 10000,
      });

      // Assert
      expect(result).toBeUndefined();
    });

    it("should return empty array when response data is incomplete", async () => {
      // Arrange
      const response = {
        ...mockResponse,
        data: {}, // missing 'types' property
      } as AxiosResponse;

      (apiClient.get as jest.Mock).mockResolvedValue(response);

      // Act
      const result = await VehicleService.fetchVehicleTypes();

      // Assert
      expect(apiClient.get).toHaveBeenCalledWith("/vehicles/types");
      expect(result).toEqual([]);
    });

    it("should return undefined when response is undefined", async () => {
      // Arrange
      (apiClient.post as jest.Mock).mockResolvedValue(undefined);

      // Act
      const result = await VehicleService.calculateCarPrice(10000, 1);

      // Assert
      expect(apiClient.post).toHaveBeenCalledWith("/calculate-car-price/1", {
        price: 10000,
      });
      expect(result).toBeUndefined();
    });

    it("should return undefined when response.data is undefined", async () => {
      // Arrange
      const response = {
        ...mockResponse,
        data: undefined,
      } as AxiosResponse;

      (apiClient.post as jest.Mock).mockResolvedValue(response);

      // Act
      const result = await VehicleService.calculateCarPrice(10000, 1);

      // Assert
      expect(apiClient.post).toHaveBeenCalledWith("/calculate-car-price/1", {
        price: 10000,
      });
      expect(result).toBeUndefined();
    });
  });
});
