<template>
  <CalculatorForm v-model:resultTableData="resultTableData" />
  <ResultTable v-if="resultTableData" :resultTableData="resultTableData" />
</template>

<script setup lang="ts">
import { ref } from "vue";
import CalculatorForm from "@/components/CalculatorForm.vue";
import ResultTable from "@/components/ResultTable.vue";
import { ResultTableData } from "@/types/types";

const resultTableData = ref<ResultTableData | null>(null);
</script>
