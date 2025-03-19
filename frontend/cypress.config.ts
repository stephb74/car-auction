import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
    specPattern: "tests/e2e/**/*.cy.ts",
    baseUrl: "http://localhost:8085",
    setupNodeEvents(on) {
      on("task", {
        logFailedTest(message) {
          console.error("Test failed:", message);
          return null;
        },
      });

      on("after:spec", (spec, results) => {
        if (results.stats.failures > 0) {
          console.log(
            `❌ Tests failed in ${spec.relative}: ${results.stats.failures}`
          );
          process.exit(1);
        }
      });
    },
  },
});
