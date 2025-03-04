export type VehicleType = {
  id: number;
  name: string;
};

type FeeType = {
  [key: string]: number;
};

export type CalculateCarPriceResponse = {
  percentageRate: FeeType;
  fixedTier: FeeType;
  fixedFee: FeeType;
  total: number;
};

export type CarPriceResult = {
  percentageRate: FeeType;
  fixedTier: FeeType;
  fixedFee: FeeType;
  total: number;
};

export type FetchVehicleTypesResponse = {
  types: VehicleType[];
};

export type ResultTableData = {
  vehicleTypeName: string;
  basePrice: number;
  carPriceResult: CarPriceResult;
};
