// import ReportTable from "../ReportTable.vue";
import BarChart from "../../components/geral/Charts/BarChart";

export default {
  components: {
    BarChart
  },
  data() {
    return {          
      data: {}
    }
  },
  mounted() {
    this.fillData()
  },
  methods: {
    getRandomInt () {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5
    },
    fillData () {
      this.data = {
        labels: [this.getRandomInt(), this.getRandomInt()],
        datasets: [
          {
            label: 'Data One',
            backgroundColor: '#f87979',
            data: [this.getRandomInt(), this.getRandomInt()]
          }, {
            label: 'Data two',
            backgroundColor: '#f87979',
            data: [this.getRandomInt(), this.getRandomInt()]
          }
        ]
      }
    }
  }
}