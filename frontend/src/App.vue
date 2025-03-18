<template>
  <div id="content-container"><BidCalculationTool /></div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import BidCalculationTool from "@/components/BidCalculationTool.vue";

export default defineComponent({
  name: "App",
  components: {
    BidCalculationTool,
  },
  setup() {
    onMounted(() => {
      document.title = "Bid Calculation Tool";
    });
  },
});
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #fff;
  height: 100vh;
}

#content-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  width: 100%;
  max-width: 350px;
  margin: 0 auto;
}
</style>
