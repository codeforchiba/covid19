<template>
  <div class="MainPage">
    <page-header
      :icon="headerItem.icon"
      :title="headerItem.title"
      :date="headerItem.date"
    />
    <v-row>
      <v-col cols="12">
        <information />
      </v-col>
    </v-row>
    <v-row class="DataBlock">
      <v-col cols="12" md="6" class="DataCard">
        <data-view
          class="ConfirmedCaseCard"
          title="検査陽性者の状況"
          title-id="details-of-confirmed-cases"
          :date="Data.inspections.date"
        >
          <template v-slot:button>
            <p class="Graph-Desc">
              （注）市内において疑い例または患者の濃厚接触者として検査を行ったものについて掲載
            </p>
          </template>
          <confirmed-cases-table v-bind="confirmedCases" />
        </data-view>
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <time-bar-chart
          title="陽性患者数"
          :title-id="'number-of-confirmed-cases'"
          :chart-id="'time-bar-chart-patients'"
          :chart-data="patientsGraph"
          :date="Data.patients.date"
          :unit="'人'"
          :url="openDataUrl"
        />
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <data-table
          :title="'陽性患者の属性'"
          :title-id="'attributes-of-confirmed-cases'"
          :chart-data="patientsTable"
          :chart-option="{}"
          :date="Data.patients.date"
          :info="sumInfoOfPatients"
          :url="openDataUrl"
        />
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <time-stacked-bar-chart
          title="検査実施数"
          :title-id="'number-of-tested'"
          :chart-id="'time-stacked-bar-chart-inspections'"
          :chart-data="inspectionsGraph"
          :date="Data.inspections.date"
          :items="inspectionsItems"
          :labels="inspectionsLabels"
          :unit="'件'"
          :url="openDataUrl"
        />
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <time-bar-chart
          title="新型コロナ電話相談窓口件数"
          :title-id="'number-of-reports-to-covid19-telephone-advisory-center'"
          :chart-id="'time-bar-chart-contacts'"
          :chart-data="contactsGraph"
          :date="Data.contacts.date"
          :unit="'件'"
          :url="openDataUrl"
        />
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <time-bar-chart
          title="感染症相談センター件数"
          :title-id="'number-of-reports-to-covid19-consultation-desk'"
          :chart-id="'time-bar-chart-querents'"
          :chart-data="querentsGraph"
          :date="Data.querents.date"
          :unit="'件'"
          :url="openDataUrl"
          description="旧)新型コロナウイルス感染症帰国者・接触者相談センター"
        />
      </v-col>
      <v-col cols="12" md="6" class="DataCard">
        <monorail-bar-chart
          title="千葉都市モノレールの利用者数の推移"
          :title-id="'predicted-number-of-chiba-monorail-passengers'"
          :chart-id="'monorail-bar-chart'"
          :chart-data="monorailGraph"
          :chart-option="monorailGraphOption"
          :date="monorailGraph.date"
        />
      </v-col>
      <v-col cols="12">
        <contact />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import PageHeader from '@/components/PageHeader.vue'
import TimeBarChart from '@/components/TimeBarChart.vue'
import MonorailBarChart from '@/components/MonorailBarChart.vue'
import TimeStackedBarChart from '@/components/TimeStackedBarChart.vue'
import DataTable from '@/components/DataTable.vue'
import DataView from '@/components/DataView.vue'
import ConfirmedCasesTable from '@/components/ConfirmedCasesTable.vue'
import Contact from '@/components/Contact.vue'
import Information from '@/components/Information.vue'

import formatGraph from '@/utils/formatGraph'
import formatTable from '@/utils/formatTable'
import {
  formatInspectionLabel,
  formatInspectionGraph,
} from '@/utils/formatInspection'
import formatConfirmedCases from '@/utils/formatConfirmedCases'

import Data from '@/data/data.json'
import MonorailData from '@/data/monorail.json'
import News from '@/data/news.json'

export default {
  components: {
    PageHeader,
    TimeBarChart,
    MonorailBarChart,
    TimeStackedBarChart,
    DataTable,
    DataView,
    ConfirmedCasesTable,
    Contact,
    Information,
  },
  data() {
    // 感染者数グラフ
    const patientsGraph = formatGraph(Data.patients_summary.data)
    // 感染者数
    const patientsTable = formatTable(Data.patients.data)
    // 相談件数
    const contactsGraph = formatGraph(Data.contacts.data)
    // 帰国者・接触者電話相談センター相談件数
    const querentsGraph = formatGraph(Data.querents.data)
    // 千葉都市モノレールの利用者数の推移
    const monorailGraph = MonorailData
    // 検査実施日別状況
    const inspectionsItems = [
      '市内発生（疑い例・接触者調査）',
      'その他（チャーター便・クルーズ便）',
    ]
    const inspectionsLabels = formatInspectionLabel(Data.inspections.data)
    const inspectionsGraph = formatInspectionGraph(Data.inspections.data)
    // 検査陽性者の状況
    const confirmedCases = formatConfirmedCases(Data.main_summary)

    const sumInfoOfPatients = {
      lText:
        patientsGraph[patientsGraph.length - 1].cumulative.toLocaleString(),
      sText: patientsGraph[patientsGraph.length - 1].label + ' の累計',
      unit: '人',
    }

    return {
      Data,
      patientsTable,
      patientsGraph,
      contactsGraph,
      querentsGraph,
      monorailGraph,
      inspectionsGraph,
      inspectionsItems,
      inspectionsLabels,
      confirmedCases,
      sumInfoOfPatients,
      headerItem: {
        icon: 'mdi-chart-timeline-variant',
        title: '千葉市内の最新感染動向',
        date: Data.lastUpdate,
      },
      newsItems: News.newsItems,
      openDataUrl:
        'https://www.city.chiba.jp/hokenfukushi/iryoeisei/seisaku/covid-19/opendata.html',
      monorailGraphOption: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
        },
        scales: {
          xAxes: [
            {
              position: 'bottom',
              stacked: false,
              gridLines: {
                display: true,
              },
              ticks: {
                fontSize: 10,
                maxTicksLimit: 20,
                fontColor: '#808080',
              },
            },
          ],
          yAxes: [
            {
              stacked: false,
              gridLines: {
                display: true,
              },
              ticks: {
                fontSize: 12,
                maxTicksLimit: 10,
                fontColor: '#808080',
                callback(value) {
                  return value.toFixed(2) + '%'
                },
              },
            },
          ],
        },
        tooltips: {
          displayColors: false,
          callbacks: {
            title(tooltipItems, _) {
              const label = tooltipItems[0].label
              return `期間: ${label}`
            },
            label(tooltipItem, data) {
              const currentData = data.datasets[tooltipItem.datasetIndex]
              const percentage = `${currentData.data[tooltipItem.index]}%`

              return `${monorailGraph.base_period}の利用者数との相対値: ${percentage}`
            },
          },
        },
      },
    }
  },
  head() {
    return {
      title: '千葉市内の最新感染動向',
    }
  },
}
</script>

<style lang="scss" scoped>
.ExternalLink {
  text-decoration: none;

  .ExternalLinkIcon {
    vertical-align: text-bottom;
  }
}

.ConfirmedCaseCard {
  .Graph-Desc {
    margin-top: 10px;
    margin-bottom: 0;
    font-size: 12px;
    color: $gray-3;
  }
}
</style>
