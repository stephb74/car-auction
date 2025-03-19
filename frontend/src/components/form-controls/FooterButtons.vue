<script setup lang="ts">
import { defineProps } from "vue";

defineProps<{
  formIsValid: boolean;
  handleFormSubmit: () => void;
  resetForm: () => void;
}>();
</script>

<template>
  <button type="reset" class="reset-button" @click="resetForm" id="reset-btn">
    Reset
  </button>
  <button
    type="button"
    class="submit-button"
    id="submit-btn"
    @click="handleFormSubmit"
    :disabled="!formIsValid"
  >
    Calculate
  </button>
</template>

<style scoped lang="scss"></style>
