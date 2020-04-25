<template>
  <v-col cols="12" md="6" class="DataCard">
    <cities-bar-chart
      :title="$t('区別 陽性者累計(退院者含む)')"
      :title-id="'cities'"
      :chart-id="'cities'"
      :chart-data="citiesData"
      :date="citiesData.date"
      :unit="$t('人')"
    />
  </v-col>
</template>

<script>
import Data from '@/data/data.json'
import CitiesBarChart from '@/components/CitiesBarChart.vue'

export default {
  components: {
    CitiesBarChart
  },
  data() {
    const cities = [this.$t('陽性患者数')]
    const citiesData = Data.cities.data
    return {
      citiesData,
      cities
    }
  }
}
</script>
