export default {
    data() {
      return {
        filters: {
            site: "",
            is_finished: null
        },
        teste: "",
        selected:"",
        options: {
            finished: [ {value: null, text: 'Ambos'} ,{ value: 1, text: 'Finalizado'}, {value: 0, text: 'Pendente'} ]
        }
      };
    },
    mounted() {
     
    },
    methods: {
      onSubmit(event) {
          event.preventDefault()
      },
      clear() {
          this.filters = {}
      },
      async search() {
          try{
              const response = await axios.post('/api/reports/search/', {filters: this.filters})
              const data = await response.data.data.data
  
              this.$emit('reportsSearched', data)
          } catch (e) {
              console.log(e)
          }
      },
    },
  };