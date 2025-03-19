<script setup lang="ts">
import { defineProps, defineEmits, watch } from "vue";
import type { VehicleType } from "@/types/types"; // Import the type correctly

defineProps<{
  selectedVehicleTypeId: number;
  vehicleTypes: VehicleType[];
}>();

const emit = defineEmits<{
  (event: "update:selectedVehicleTypeId", value: number): void;
}>();

const updateVehicleType = (event: Event) => {
  const target = event.target as HTMLSelectElement;
  emit("update:selectedVehicleTypeId", Number(target.value));
};
</script>

<template>
  <label for="vehicle-type">Vehicle Type:</label>
  <select
    :value="selectedVehicleTypeId"
    @change="updateVehicleType"
    class="form-control"
    id="vehicle-type"
  >
    <option v-for="type in vehicleTypes" :key="type.id" :value="type.id">
      {{ type.name }}
    </option>
  </select>
</template>

<style scoped lang="scss">
.form-control {
  width: 100%;
  padding: 8px;
  font-size: 1rem;
}
</style>
