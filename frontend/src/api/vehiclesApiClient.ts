import {
  CalculateCarPriceResponse,
  CarPriceResult,
  FetchVehicleTypesResponse,
  VehicleType,
} from "@/types/types";
import apiClient from "@/api/apiClient";
import { AxiosResponse } from "axios";

/**
 * Fetch the vehicle types from the API
 */
export const fetchVehicleTypes = async (): Promise<VehicleType[]> => {
  try {
    const response: AxiosResponse<FetchVehicleTypesResponse> =
      await apiClient.get("/vehicles/types");

    return response?.data?.types ?? [];
  } catch (error) {
    console.error("Error fetching vehicle types:", error);
    return [];
  }
};

/**
 * Query the API to calculate the car price
 * @param price
 * @param vehicleTypeId
 * @returns {CarPriceResult|undefined}
 */
export const calculateCarPrice = async (
  price: number,
  vehicleTypeId: number
): Promise<CarPriceResult | undefined> => {
  try {
    const response: AxiosResponse<CalculateCarPriceResponse> =
      await apiClient.post(`/calculate-car-price/${vehicleTypeId}`, {
        price,
      });
    return response?.data ?? [];
  } catch (error) {
    console.error("Error calculating car price:", error);
  }
};
