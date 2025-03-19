<template>
  <form class="flex" id="calculator-form">
    <h1>Bid Calculation Tool</h1>
    <div class="row">
      <SelectVehicleType
        v-model:selectedVehicleTypeId="selectedVehicleTypeId"
        :vehicleTypes="vehicleTypes"
      />
    </div>
    <div class="row">
      <PriceInput v-model:vehiclePrice="vehiclePrice" />
    </div>
    <div class="row form-footer">
      <FooterButtons
        :formIsValid="formIsValid"
        :handleFormSubmit="handleFormSubmit"
        :resetForm="resetForm"
      />
    </div>
  </form>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, onMounted } from "vue";
import { ResultTableData } from "@/types/types";
import useCalculatorForm from "@/composables/useCalculatorForm";
import FooterButtons from "@/components/form-controls/FooterButtons.vue";
import SelectVehicleType from "@/components/form-controls/SelectVehicleType.vue";
import PriceInput from "@/components/form-controls/PriceInput.vue";

defineProps<{
  resultTableData: ResultTableData | null;
}>();

const emit = defineEmits(["update:resultTableData"]);
const {
  fetchVehicleTypes,
  formIsValid,
  handleFormSubmit,
  resetForm,
  selectedVehicleTypeId,
  vehiclePrice,
  vehicleTypes,
} = useCalculatorForm(emit);

onMounted(fetchVehicleTypes);
</script>
