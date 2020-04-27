<template>
  <v-range-slider
    v-model="sliderValue"
    :value="sliderValue"
    :label="'表示期間'"
    :min="min"
    :max="max"
    thumb-label="always"
    style="padding-top: 35px;"
    color="rgba(189, 63, 76, 1)"
    track-color="rgba(189, 63, 76, 0.3)"
  >
    <template v-slot:thumb-label="props">
      {{ getSliderLabels(props.value) }}
    </template>
  </v-range-slider>
</template>

<script>
export default {
  name: 'DateSelectSlider',
  props: {
    chartData: {
      type: Array,
      required: true
    },
    value: {
      type: Array,
      required: true
    },
    min: {
      type: Number,
      required: true,
      default: 0
    },
    max: {
      type: Number,
      required: true,
      default: 1
    }
  },
  data() {
    return {
      sliderValue: this.value
    }
  },
  watch: {
    sliderValue(newValue, _oldValue) {
      this.$emit('sliderInput', newValue)
    }
  },
  methods: {
    getSliderLabels(index) {
      if (index < this.chartData.length) {
        return this.chartData[index].label
      }
      return this.chartData[this.chartData.length - 1].label
    }
  }
}
</script>
