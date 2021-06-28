<script>
import { Bar, mixins } from "vue-chartjs";  

export default {
  extends: Bar,
  mixins: [mixins.reactiveProp],
  options: 
  {
    type: Object,
    value: {}
  },
  mounted() {
    this.renderChart(this.chartData, this.options)
  }
};
</script>