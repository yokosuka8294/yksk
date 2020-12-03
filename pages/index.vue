<template>
  <div class="MainPage">
    <div class="Header mb-3">
      <page-header :icon="headerItem.icon">{{ headerItem.title }}</page-header>
      <div class="UpdatedAt">
        <span>{{ $t('最終更新') }}</span>
        <time :datetime="updatedAt">{{ Data.lastUpdate }}</time>
      </div>
      <div
        v-show="!['ja', 'ja-basic'].includes($i18n.locale)"
        class="Annotation"
      />
    </div>
    <static-info
      class="mb-4"
      url="https://www.city.yokohama.lg.jp/city-info/koho-kocho/koho/topics/covid-19/allNewsList.html"
      target="_blank"
      rel="noopener"
      :text="$t('横浜市発表の新型コロナウイルス感染症に関する最新情報はこちら')"
    />
    <card-row class="DataBlock">
      <!-- 陽性患者状況(累計) -->
      <agency-card2 />

      <!-- 陽性患者状況(日ごと) -->
      <agency-card5 />

      <!-- 陽性患者状況(7日移動平均) -->
      <agency-card6 />

      <!-- 年齢 -->
      <agency-card3 />

      <!-- 区別マップ表示 -->
      <ibaraki-graphical-map-card />

      <!-- 区別の検査陽性者 -->
      <cities-card />

      <!-- 区別の検査陽性者 -->
      <cities-card2 />

      <!-- PCR検査数 -->
      <agency-card4 />

      <!-- 都庁来庁者数の推移 -->
      <agency-card />

      <!-- 検査陽性者の状況 -->
      <!--       <confirmed-cases-details-card /> -->
      <!--       <confirmed-cases-details-card /> -->
      <!-- 陽性患者数 -->
      <!--       <confirmed-cases-number-card /> -->
      <!-- 陽性患者の属性 -->
      <!--       <confirmed-cases-attributes-card /> -->
      <!-- 区市町村別患者数 -->
      <!--       <confirmed-cases-by-municipalities-card /> -->
      <!-- 検査実施状況 -->
      <!--       <tested-cases-details-card /> -->
      <!-- 検査実施人数 -->
      <!--       <inspection-persons-number-card /> -->
      <!-- 検査実施件数 -->
      <!--       <tested-number-card /> -->
      <!-- 新型コロナコールセンター相談件数 -->
      <!--       <telephone-advisory-reports-number-card /> -->
      <!-- 新型コロナ受診相談窓口相談件数 -->
      <!--       <consultation-desk-reports-number-card /> -->
      <!-- 都営地下鉄の利用者数の推移 -->
      <!--       <metro-card /> -->
    </card-row>
    <v-divider />
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import { MetaInfo } from 'vue-meta'
import Data from '@/data/data.json'

import PageHeader from '@/components/PageHeader.vue'
import StaticInfo from '@/components/StaticInfo.vue'

import CardRow from '@/components/cards/CardRow.vue'
import CitiesCard from '@/components/cards/CitiesCard.vue'
import CitiesCard2 from '@/components/cards/CitiesCard2.vue'
import IbarakiGraphicalMapCard from '@/components/cards/IbarakiGraphicalMapCard.vue'
// import ConfirmedCasesDetailsCard from '@/components/cards/ConfirmedCasesDetailsCard.vue'

// import AgeCard from '@/components/cards/AgeCard.vue'
// import ConfirmedCasesNumberCard from '@/components/cards/ConfirmedCasesNumberCard.vue'
// import ConfirmedCasesAttributesCard from '@/components/cards/ConfirmedCasesAttributesCard.vue'
// import ConfirmedCasesByMunicipalitiesCard from '@/components/cards/ConfirmedCasesByMunicipalitiesCard.vue'
// import TestedCasesDetailsCard from '@/components/cards/TestedCasesDetailsCard.vue'
// import InspectionPersonsNumberCard from '@/components/cards/InspectionPersonsNumberCard.vue'
// import TestedNumberCard from '@/components/cards/TestedNumberCard.vue'
// import TelephoneAdvisoryReportsNumberCard from '@/components/cards/TelephoneAdvisoryReportsNumberCard.vue'
// import ConsultationDeskReportsNumberCard from '@/components/cards/ConsultationDeskReportsNumberCard.vue'
// import MetroCard from '@/components/cards/MetroCard.vue'
import AgencyCard from '@/components/cards/AgencyCard.vue'
import AgencyCard2 from '@/components/cards/AgencyCard2.vue'
import AgencyCard3 from '@/components/cards/AgencyCard3.vue'
import AgencyCard4 from '@/components/cards/AgencyCard4.vue'
import AgencyCard5 from '@/components/cards/AgencyCard5.vue'
import AgencyCard6 from '@/components/cards/AgencyCard6.vue'
import { convertDatetimeToISO8601Format } from '@/utils/formatDate'

export default Vue.extend({
  components: {
    PageHeader,
    StaticInfo,
    CardRow,
    CitiesCard,
    CitiesCard2,
    IbarakiGraphicalMapCard,
    //     ConfirmedCasesDetailsCard,
    //     AgeCard,
    //     WhatsNew,
    //     ConfirmedCasesNumberCard,
    //     ConfirmedCasesAttributesCard,
    //     ConfirmedCasesByMunicipalitiesCard,
    //     TestedCasesDetailsCard,
    //     InspectionPersonsNumberCard,
    //     TestedNumberCard,
    //     TelephoneAdvisoryReportsNumberCard,
    //     ConsultationDeskReportsNumberCard,
    //     MetroCard,
    AgencyCard2,
    AgencyCard3,
    AgencyCard4,
    AgencyCard5,
    AgencyCard6,
    AgencyCard
  },
  data() {
    const data = {
      Data,
      headerItem: {
        icon: 'mdi-chart-timeline-variant',
        title: this.$t('市内の最新感染動向')
      }
    }
    return data
  },
  computed: {
    updatedAt() {
      return convertDatetimeToISO8601Format(this.$data.Data.lastUpdate)
    }
  },
  head(): MetaInfo {
    return {
      title: this.$t('市内の最新感染動向') as string
    }
  }
})
</script>

<style lang="scss" scoped>
.MainPage {
  .Header {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;

    @include lessThan($small) {
      flex-direction: column;
      align-items: baseline;
    }
  }

  .UpdatedAt {
    @include font-size(14);

    color: $gray-3;
    margin-bottom: 0.2rem;
  }

  .Annotation {
    @include font-size(12);

    color: $gray-3;
    @include largerThan($small) {
      margin: 0 0 0 auto;
    }
  }
  .DataBlock {
    margin: 20px -8px;

    .DataCard {
      @include largerThan($medium) {
        padding: 10px;
      }

      @include lessThan($small) {
        padding: 4px 8px;
      }
    }
  }
}
</style>
