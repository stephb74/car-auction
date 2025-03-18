import { mount } from "@vue/test-utils";
import CalculatorForm from "@/components/CalculatorForm.vue";
import vehicleService from "@/services/VehicleService";
import { ref } from "vue";
import { VehicleType, CarPriceResult, ResultTableData } from "@/types/types";

jest.mock("@/services/VehicleService");

describe("CalculatorForm.vue", () => {
  const vehicleTypes: VehicleType[] = [{ id: 1, name: "Sedan" }];
  const carPriceResult: CarPriceResult = {
    percentageRate: { tax: 100 },
    fixedTier: { tier1: 200 },
    fixedFee: { fee1: 50 },
    total: 350,
  };

  let consoleErrorSpy: jest.SpyInstance;

  beforeEach(() => {
    jest.clearAllMocks();
    (vehicleService.fetchVehicleTypes as jest.Mock).mockResolvedValue(
      vehicleTypes
    );
    (vehicleService.calculateCarPrice as jest.Mock).mockResolvedValue(
      carPriceResult
    );
    consoleErrorSpy = jest.spyOn(console, "error").mockImplementation(() => {});
  });

  afterEach(() => {
    consoleErrorSpy.mockRestore();
  });

  it("initializes selectedVehicleTypeId to null", () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    expect(wrapper.vm.selectedVehicleTypeId).toBe(null);
  });

  it("initializes vehicleTypes to an empty array", () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    expect(wrapper.vm.vehicleTypes).toEqual([]);
  });

  it("initializes vehiclePrice to 0", () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    expect(wrapper.vm.vehiclePrice).toBe(0);
  });

  it("fetches vehicle types on mount", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();

    expect(vehicleService.fetchVehicleTypes).toHaveBeenCalled();
    expect(wrapper.vm.vehicleTypes).toEqual(vehicleTypes);
    expect(wrapper.vm.selectedVehicleTypeId).toBe(1);
  });

  it("handles form submission correctly", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();
    wrapper.vm.selectedVehicleTypeId = 1;
    wrapper.vm.vehiclePrice = 10000;
    await wrapper.vm.$nextTick();

    await wrapper.find(".submit-btn").trigger("click");
    await flushPromises();

    expect(vehicleService.calculateCarPrice).toHaveBeenCalledWith(10000, 1);
    const emitted = wrapper.emitted() as {
      "update:resultTableData": (ResultTableData | null)[][];
    };
    expect(emitted["update:resultTableData"][0][0]).toEqual({
      vehicleTypeName: "Sedan",
      basePrice: 10000,
      carPriceResult,
    });
  });

  it("handles form reset correctly", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();
    wrapper.vm.selectedVehicleTypeId = 1;
    wrapper.vm.vehiclePrice = 10000;
    await wrapper.vm.$nextTick();

    await wrapper.find(".reset-button").trigger("click");

    expect(wrapper.vm.selectedVehicleTypeId).toBe(null);
    expect(wrapper.vm.vehiclePrice).toBe(0);
    const emitted = wrapper.emitted() as {
      "update:resultTableData": (ResultTableData | null)[][];
    };
    expect(emitted["update:resultTableData"][0][0]).toBeNull();
  });

  it("validates price input", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    const input = wrapper.find("#vehicle-price");
    await input.trigger("keydown", { key: "-" });
    expect(wrapper.vm.vehiclePrice).toBe(0);

    await input.trigger("keydown", { key: "1" });
    expect(wrapper.vm.vehiclePrice).toBe(0);
  });

  it("handles error when fetching vehicle types", async () => {
    (vehicleService.fetchVehicleTypes as jest.Mock).mockRejectedValueOnce(
      new Error("API Error")
    );

    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();

    expect(wrapper.vm.vehicleTypes).toEqual([]);
    expect(wrapper.vm.selectedVehicleTypeId).toBe(null);
  });

  it("handles error in form submission", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();
    wrapper.vm.selectedVehicleTypeId = 1;
    wrapper.vm.vehiclePrice = 10000;

    (vehicleService.calculateCarPrice as jest.Mock).mockRejectedValueOnce(
      new Error("Calculation Error")
    );

    await wrapper.find(".submit-btn").trigger("click");
    await flushPromises();

    const emitted = wrapper.emitted() as {
      "update:resultTableData": (ResultTableData | null)[][];
    };
    expect(emitted["update:resultTableData"]).toBeUndefined();
  });

  it("handles case when vehicle type is not found", async () => {
    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();
    wrapper.vm.selectedVehicleTypeId = 999; // Non-existent ID
    wrapper.vm.vehiclePrice = 10000;
    await wrapper.vm.$nextTick();

    await wrapper.find(".submit-btn").trigger("click");
    await flushPromises();

    expect(consoleErrorSpy).toHaveBeenCalledWith("Vehicle type not found");
  });

  it("handles case when car price result is not found", async () => {
    (vehicleService.calculateCarPrice as jest.Mock).mockResolvedValueOnce(null);

    const wrapper = mount(CalculatorForm, {
      props: { resultTableData: ref(null) },
    });

    await flushPromises();
    wrapper.vm.selectedVehicleTypeId = 1;
    wrapper.vm.vehiclePrice = 10000;
    await wrapper.vm.$nextTick();

    await wrapper.find(".submit-btn").trigger("click");
    await flushPromises();

    expect(consoleErrorSpy).toHaveBeenCalledWith("Car price result not found");
  });
});

function flushPromises() {
  return new Promise((resolve) => setTimeout(resolve));
}
