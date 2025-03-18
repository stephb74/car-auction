<template>
  <form class="flex" id="calculator-form">
    <h1>Bid Calculation Tool</h1>
    <div class="row">
      <label for="vehicle-type">Vehicle Type:</label>
      <select
        v-model="selectedVehicleTypeId"
        class="form-control"
        id="vehicle-type"
      >
        <option v-for="type in vehicleTypes" :key="type.id" :value="type.id">
          {{ type.name }}
        </option>
      </select>
    </div>
    <div class="row">
      <label for="vehicle-price">Vehicle Price:</label>
      <input
        type="number"
        min="0"
        class="form-control"
        id="vehicle-price"
        value="0"
        v-model="vehiclePrice"
        @keydown="validatePrice"
      />
    </div>
    <div class="row form-footer">
      <button type="reset" class="reset-button" @click="reset">Reset</button>
      <button type="button" class="submit-btn" @click="handleFormSubmit">
        Calculate
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, onMounted } from "vue";
import { calculateCarPrice, fetchVehicleTypes } from "@/api/vehiclesApiClient";
import { validatePrice } from "@/utils/validation";
import { ResultTableData, VehicleType } from "@/types/types";

const props = defineProps<{
  resultTableData: ResultTableData | null;
}>();

const emit = defineEmits(["update:resultTableData"]);

// Local state
const selectedVehicleTypeId = ref();
const vehicleTypes = ref<VehicleType[] | undefined>([]);
const vehiclePrice = ref(0);

/**
 * Handle form submission
 */
const handleFormSubmit = async () => {
  const vehicleType = vehicleTypes.value?.find(
    (type) => type.id === selectedVehicleTypeId.value
  );

  if (vehicleType) {
    const carPriceResult = await calculateCarPrice(
      vehiclePrice.value,
      vehicleType.id
    );

    if (carPriceResult) {
      const resultTableData: ResultTableData = {
        vehicleTypeName: vehicleType.name,
        basePrice: vehiclePrice.value,
        carPriceResult,
      };

      emit("update:resultTableData", resultTableData);
    }
  }
};

/**
 * Reset the form fields
 */
const reset = () => {
  selectedVehicleTypeId.value = "";
  vehiclePrice.value = 0;
  emit("update:resultTableData", null);
};

onMounted(async () => {
  vehicleTypes.value = await fetchVehicleTypes();

  if (vehicleTypes.value && vehicleTypes.value.length > 0) {
    selectedVehicleTypeId.value = vehicleTypes.value[0].id;
  }
});
</script>
