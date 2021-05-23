<template>
    <div class="p-3 bg-light mb-4">
       <div class="">
           <h5>Filtro de Relatorios</h5>
            <b-form @submit.stop.prevent>  
                <b-row>
                    <b-col sm="7" md="4">
                        <b-form-group
                            label="Site"
                            label-for="input-1">
                            
                            <b-form-input
                            v-model="filters.site"
                            type="search"
                            class="mb-1 mr-sm-2 mb-sm-0"
                            placeholder="Digite..."
                            ></b-form-input>
                            
                        </b-form-group>
                    </b-col>
                    <b-col sm="6" md="4">
                        <b-form-group
                            label="Ferramenta"
                            label-for="input-1"
                        >
                            <b-form-input
                            v-model="filters.tool_name"
                            type="search"
                            class="mb-1 mr-sm-1 mb-sm-0"
                            placeholder="Digite..."
                            ></b-form-input>
                            
                        </b-form-group>
                    </b-col>
                    <b-col sm="4" md="4">
                        <b-form-group id="input-group-1" label="Status" label-for="input-3">
                            <b-form-select v-model="filters.is_finished" :options="options.finished"></b-form-select>
                        </b-form-group>
                    </b-col>
                    
                </b-row>
                <b-row  align-h="end">
                    <b-col sm="12" md="4" lg="4" xl="4">
                        <b-form-group id="input-group-4"  class="mt-4 text-right" label-for="input-4">
                            <b-button @click="search()">Pesquisar</b-button>
                            <b-button variant="danger" @click="clear()">Limpar</b-button>
                        </b-form-group>
                        
                    </b-col>
                </b-row>
            </b-form>
        </div>
    </div>
</template>

<script>
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
            const response = await axios.post('http://localhost:8000/api/reports/search', {filters: this.filters})
            const data = await response.data.data.data

            this.$emit('reportsSearched', data)
        } catch (e) {
            console.log(e)
        }
    },
  },
};
</script>
