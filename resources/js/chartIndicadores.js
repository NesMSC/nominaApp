import { Line } from 'vue-chartjs'

export default {
  extends: Line,
  mounted () {
    this.renderChart({
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
            label: 'Data One',
            backgroundColor: '#f87979',
            data: [40, 39, 10, 40, 39, 80, 40]
        },
        {
            label: 'Data One',
            backgroundColor: '#797979',
            data: [40, 39, 10, 40, 39, 80, 40].reverse()
        }
      ]
    }, {responsive: true, maintainAspectRatio: false})
  }
}
