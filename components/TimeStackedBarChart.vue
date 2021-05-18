<template>
  <data-view :title="title" :title-id="titleId" :date="date" :url="url">
    <template v-slot:button>
      <p class="Graph-Desc">
        （注）同一の対象者について複数の検体を調査する場合あり
      </p>
      <data-selector v-model="dataKind" />
    </template>
    <bar
      :chart-id="chartId"
      :chart-data="displayData"
      :options="options"
      :height="280"
    />
    <template v-slot:infoPanel>
      <data-view-basic-info-panel
        :l-text="displayInfo.lText"
        :s-text="displayInfo.sText"
        :unit="displayInfo.unit"
      />
    </template>
  </data-view>
</template>

<script>
import dayjs from 'dayjs'

import DataView from '@/components/DataView.vue'
import DataSelector from '@/components/DataSelector.vue'
import DataViewBasicInfoPanel from '@/components/DataViewBasicInfoPanel.vue'

export default {
  components: { DataView, DataSelector, DataViewBasicInfoPanel },
  props: {
    title: {
      type: String,
      required: false,
      default: '',
    },
    titleId: {
      type: String,
      required: false,
      default: '',
    },
    chartId: {
      type: String,
      required: false,
      default: 'time-stacked-bar-chart',
    },
    chartData: {
      type: Array,
      required: false,
      default: () => [],
    },
    date: {
      type: String,
      required: true,
      default: '',
    },
    items: {
      type: Array,
      required: false,
      default: () => [],
    },
    labels: {
      type: Array,
      required: false,
      default: () => [],
    },
    unit: {
      type: String,
      required: false,
      default: '',
    },
    url: {
      type: String,
      required: false,
      default: '',
    },
  },
  data() {
    return {
      dataKind: 'transition',
    }
  },
  computed: {
    hasOtherData() {
      return this.sum(this.chartData.map((d) => d.other)) > 0
    },

    graphData() {
      const data = [this.chartData.map((d) => d.city)]

      if (this.hasOtherData) {
        data.push(this.chartData.map((d) => d.other))
      }

      return data
    },
    totalGraphData() {
      return this.chartData.map((d) => d.city + d.other)
    },
    cumulativeGraphData() {
      const data = [this.chartData.map((d) => d.cumCity)]

      if (this.hasOtherData) {
        data.push(this.chartData.map((d) => d.cumOther))
      }

      return data
    },
    totalCumulativeGraphData() {
      return this.chartData.map((d) => d.cumCity + d.cumOther)
    },
    displayInfo() {
      const lastUpdate = this.labels[this.labels.length - 1]
      if (this.dataKind === 'transition') {
        return {
          lText: this.sum(this.pickLastNumber(this.graphData)).toLocaleString(),
          sText: `${lastUpdate} の合計`,
          unit: this.unit,
        }
      }
      return {
        lText: this.sum(
          this.pickLastNumber(this.cumulativeGraphData)
        ).toLocaleString(),
        sText: `${lastUpdate} の全体累計`,
        unit: this.unit,
      }
    },
    displayData() {
      const colorArray = ['#00A040', '#00D154']
      if (this.dataKind === 'transition') {
        return {
          labels: this.chartData.map((d) => {
            return d.label
          }),
          datasets: this.graphData.map((d, index) => {
            return {
              label: this.items[index],
              data: d,
              backgroundColor: colorArray[index],
              borderWidth: 0,
            }
          }),
        }
      }
      return {
        labels: this.chartData.map((d) => {
          return d.label
        }),
        datasets: this.cumulativeGraphData.map((d, index) => {
          return {
            label: this.items[index],
            data: d,
            backgroundColor: colorArray[index],
            borderWidth: 0,
          }
        }),
      }
    },
    options() {
      const unit = this.unit
      return {
        tooltips: {
          displayColors: false,
          callbacks: {
            label: (tooltipItem) => {
              const index = tooltipItem.index
              const totalRef =
                this.dataKind === 'transition'
                  ? this.totalGraphData
                  : this.totalCumulativeGraphData
              let withBreakdown = ''

              if (this.hasOtherData) {
                const cityRef =
                  this.dataKind === 'transition'
                    ? this.graphData[0]
                    : this.cumulativeGraphData[0]
                const otherRef =
                  this.dataKind === 'transition'
                    ? this.graphData[1]
                    : this.cumulativeGraphData[1]
                withBreakdown = ` (市内: ${cityRef[index]} / その他: ${otherRef[index]})`
              }

              return `${totalRef[index]}${unit}${withBreakdown}`
            },
            title(tooltipItem, data) {
              return data.labels[tooltipItem[0].index]
            },
          },
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: this.hasOtherData,
        },
        scales: {
          xAxes: [
            {
              id: 'day',
              stacked: true,
              gridLines: {
                display: false,
              },
              ticks: {
                fontSize: 9,
                maxTicksLimit: 20,
                fontColor: '#808080',
                maxRotation: 0,
                minRotation: 0,
                callback: (label) => {
                  return dayjs(label).date()
                },
              },
            },
            {
              id: 'month',
              stacked: true,
              gridLines: {
                drawOnChartArea: false,
                drawTicks: true,
                drawBorder: false,
                tickMarkLength: 10,
              },
              ticks: {
                fontSize: 11,
                fontColor: '#808080',
                padding: 3,
                fontStyle: 'bold',
                callback: (label) => {
                  const monthStringArry = [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                  ]
                  const month = monthStringArry.indexOf(label.split(' ')[0]) + 1
                  return month + '月'
                },
              },
              type: 'time',
              time: {
                unit: 'month',
              },
            },
          ],
          yAxes: [
            {
              location: 'bottom',
              stacked: true,
              gridLines: {
                display: true,
                color: '#E5E5E5',
              },
              ticks: {
                suggestedMin: 0,
                maxTicksLimit: 8,
                fontColor: '#808080',
              },
            },
          ],
        },
      }
    },
  },
  methods: {
    sum(array) {
      return array.reduce((acc, cur) => acc + cur)
    },
    pickLastNumber(chartDataArray) {
      return chartDataArray.map((array) => array[array.length - 1])
    },
  },
}
</script>

<style lang="scss" scoped>
.Graph-Desc {
  margin: 10px 0;
  font-size: 12px;
  color: $gray-3;
}
</style>
