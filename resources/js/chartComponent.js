import { Doughnut, mixins } from 'vue-chartjs'

export default {
  extends: Doughnut,
  mixins: [mixins.reactiveProp],
  props: {
    chartData:{
      type: Object,
      default:{
        labels: ['label1', 'label2', 'label3'],
        datasets: [
          {
            backgroundColor: [
              '#41B883',
              '#E46651',
              '#00D8FF',
            ],
            data: [10, 10, 10]
          }
        ]
      },
    },
    options:{responsive: true, maintainAspectRatio: false}
  },
  mounted () {

    this.renderChart(this.chartData, this.options)
  }
}
