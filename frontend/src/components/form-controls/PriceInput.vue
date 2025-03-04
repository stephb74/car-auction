<script setup lang="ts">
import { defineProps, defineEmits } from "vue";
import { validatePrice } from "@/utils/validation";

defineProps<{ vehiclePrice: number }>();
const emit = defineEmits(["update:vehiclePrice"]);

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit("update:vehiclePrice", parseFloat(target.value) || 0);
};
</script>

<template>
  <label for="vehicle-price">Vehicle Price:</label>
  <input
    type="number"
    min="0"
    class="form-control"
    id="vehicle-price"
    :value="vehiclePrice"
    @input="handleInput"
    @keydown="validatePrice"
  />
</template>
