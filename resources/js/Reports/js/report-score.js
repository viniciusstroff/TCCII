import BarChart from "../../components/geral/Charts/BarChart";
import util  from '../../util/index'

export default {
    components: {
        BarChart
    },
    data() {
        return {
            data : {
                labels:  ['Performance', 'Acessibilidade'],
                datasets: [
                    {}
                ],
                options: {
                    scales: {
                        y: {
                          beginAtZero: true,
                          max: 100
                        }
                      }
                }
            },
            response: {
                data: [],
                success: false,
                message: "",
                errors: []
            },
            carregando: true
           
        }
    },
    mounted() {
     this.search()
    },
   
    methods: {
        async search() {
            this.carregando = true
            try
            {
                const reportId = this.$route.params.id
                const response = await axios.get(`/api/reports/report/${reportId}/scores`)
                this.response = util.mapearObjetos(response.data, {})
                this.mapDataChart()
            } catch (e) {
                console.log(e)
            }
            this.carregando = false
        },

        mapDataChart() {
            const colors = ['#F51304', '#0D90F1'];
            const scores = this.response.data.map((scores, indice, array) => 
            {
                const obj = {
                    data: [scores.performance * 100,  scores.accessibility * 100],
                    backgroundColor: colors[indice],
                    label: `${scores.site} - ${scores.created_at}` 
                }
                return obj
            }, []);
            this.data.datasets = util.mapearObjetos(scores, [])
        },


    }
}