<template>
  <v-col cols="12" md="6" class="DataCard">
    <agency-bar-chart
      :title="$t('陽性患者の年齢と状況')"
      :title-id="'agency'"
      :chart-id="'agency'"
      :chart-data="agencyData"
      :date="agencyData.date"
      :unit="$t('人')"
    />
  </v-col>
</template>

<script>
import agencyData from '@/data/agency3.json'
import AgencyBarChart from '@/components/AgencyBarChart3.vue'

export default {
  components: {
    AgencyBarChart
  },
  data() {
    return {
      agencyData
    }
  }
}
</script>
