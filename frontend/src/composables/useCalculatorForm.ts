import { ResultTableData, VehicleType } from "@/types/types";
import { ref, watch } from "vue";
import vehicleService from "@/services/VehicleService";

const useCalculatorForm = (
  emit: (
    event: "update:resultTableData",
    payload: ResultTableData | null
  ) => void
) => {
  const selectedVehicleTypeId = ref<number | null>(null);
  const vehicleTypes = ref<VehicleType[]>([]);
  const vehiclePrice = ref<number | null>(null);
  const errorMessage = ref<string | null>(null);
  const formIsValid = ref<boolean>(false);

  /**
   * Fetch vehicle types and set the first as default
   */
  const fetchVehicleTypes = async (): Promise<void> => {
    try {
      const types: VehicleType[] = await vehicleService.fetchVehicleTypes();
      vehicleTypes.value = types ?? [];

      if (vehicleTypes.value.length > 0) {
        selectedVehicleTypeId.value = vehicleTypes.value[0].id;
      }
    } catch {
      errorMessage.value =
        "Failed to load vehicle types. Please refresh the page.";
      vehicleTypes.value = [];
    }
  };

  /**
   * Validates if form inputs are correct
   */
  const isFormValid = (): boolean => {
    const vehicleTypeExists = vehicleTypes.value.some(
      (type) => type.id === selectedVehicleTypeId.value
    );
    return vehicleTypeExists && !!vehiclePrice.value && vehiclePrice.value > 0;
  };

  /**
   * Fetch car price calculation from API
   */
  const fetchCarPrice = async (vehicleTypeId: number, price: number) => {
    try {
      return await vehicleService.calculateCarPrice(price, vehicleTypeId);
    } catch {
      errorMessage.value = "An error occurred while calculating the price.";
      return null;
    }
  };

  /**
   * Validate the form inputs
   */
  const validateForm = (): boolean => {
    if (!isFormValid() || vehiclePrice.value === null) {
      errorMessage.value =
        "Please select a valid vehicle type and enter a valid price.";
      return false;
    }
    return true;
  };

  /**
   * Get the selected vehicle type
   */
  const getSelectedVehicleType = (): VehicleType | null => {
    return (
      vehicleTypes.value.find(
        (type) => type.id === selectedVehicleTypeId.value
      ) || null
    );
  };

  /**
   * Handles form submission: validates, fetches, and emits results
   */
  const handleFormSubmit = async (): Promise<void> => {
    errorMessage.value = null;

    if (!validateForm()) return;

    const vehicleType = getSelectedVehicleType() as VehicleType; // validated above

    const price: number = vehiclePrice.value as number;

    const carPriceResult = await fetchCarPrice(vehicleType.id, price);
    if (!carPriceResult) {
      errorMessage.value = "Failed to calculate the car price.";
      return;
    }

    const resultData: ResultTableData = {
      vehicleTypeName: vehicleType.name,
      basePrice: price,
      carPriceResult,
    };

    emit("update:resultTableData", resultData);
  };

  /**
   * Reset the form fields
   */
  const resetForm = (): void => {
    selectedVehicleTypeId.value =
      vehicleTypes.value.length > 0 ? vehicleTypes.value[0].id : null;
    vehiclePrice.value = null; // Clears the input field
    errorMessage.value = null;
    formIsValid.value = false;
    emit("update:resultTableData", null);
  };

  /**
   * Watch form fields and update form validity
   */
  watch([selectedVehicleTypeId, vehiclePrice], () => {
    formIsValid.value = isFormValid();
  });

  return {
    vehicleTypes,
    selectedVehicleTypeId,
    vehiclePrice,
    formIsValid,
    errorMessage,
    fetchCarPrice,
    fetchVehicleTypes,
    resetForm,
    handleFormSubmit,
    isFormValid,
    validateForm,
    getSelectedVehicleType,
  };
};

export default useCalculatorForm;
