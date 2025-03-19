<script setup lang="ts">
import { defineProps } from "vue";

defineProps<{
  formIsValid: boolean;
  handleFormSubmit: () => void;
  resetForm: () => void;
}>();
</script>

<template>
  <button type="reset" class="reset-button" @click="resetForm">Reset</button>
  <button
    type="button"
    class="submit-btn"
    @click="handleFormSubmit"
    :disabled="!formIsValid"
  >
    Calculate
  </button>
</template>

<style scoped lang="scss"></style>
