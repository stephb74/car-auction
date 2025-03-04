<script setup lang="ts">
import { defineProps } from "vue";
import { formatCurrency } from "@/utils/strings";
import { ResultTableData } from "@/types/types";

const props = defineProps<{
  resultTableData: ResultTableData;
}>();

const feeKeys = [
  ...Object.keys(props.resultTableData.carPriceResult.percentageRate),
  ...Object.keys(props.resultTableData.carPriceResult.fixedTier),
  ...Object.keys(props.resultTableData.carPriceResult.fixedFee),
];
</script>

<template>
  <table id="result-table">
    <thead>
      <tr>
        <th rowspan="2">Price</th>
        <th rowspan="2">Vehicle Type</th>
        <th :colspan="feeKeys.length">Fees</th>
        <th rowspan="2">Total</th>
      </tr>
      <tr>
        <th v-for="key in feeKeys" :key="key">{{ key }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="currency">
          <strong>
            {{ formatCurrency(resultTableData.basePrice, "$") }}
          </strong>
        </td>
        <td>{{ resultTableData.vehicleTypeName }}</td>
        <td
          class="currency"
          v-for="(value, key) in resultTableData.carPriceResult.percentageRate"
          :key="key"
        >
          {{ formatCurrency(value, "$") }}
        </td>
        <td
          class="currency"
          v-for="(value, key) in resultTableData.carPriceResult.fixedTier"
          :key="key"
        >
          {{ formatCurrency(value, "$") }}
        </td>
        <td
          class="currency"
          v-for="(value, key) in resultTableData.carPriceResult.fixedFee"
          :key="key"
        >
          {{ formatCurrency(value, "$") }}
        </td>
        <td class="currency">
          <strong>
            {{ formatCurrency(resultTableData.carPriceResult.total, "$") }}
          </strong>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped lang="scss">
#result-table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  margin-top: 20px;
}

#result-table th {
  white-space: nowrap;
  text-align: center;
  background-color: #1a2530;
}

#result-table th,
#result-table td {
  border: 1px solid #ffffff;
  padding: 8px;
  text-align: center;
}

#result-table .currency {
  text-align: right;
}
</style>
