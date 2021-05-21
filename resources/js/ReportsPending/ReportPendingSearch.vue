<template>
    <div >
       <div class="">
        <b-row>
            <b-col sm="7" md="6" class="my-1">
                <b-form-group
                    id="input-group-1"
                    label="Pesquisa"
                    label-for="input-1"
                >
                    <b-form-input
                    id="input-1"
                    v-model="teste"
                    type="search"
                    class="mb-2 mr-sm-2 mb-sm-0"
                    placeholder="Procure"
                    ></b-form-input>
                    
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group id="input-group-1" label="Terminado:" class="mt-1" label-for="input-3">
                    <b-form-select v-model="filters.is_finished" :options="options.finished"></b-form-select>
                </b-form-group>
            </b-col>
            <b-col sm="3" class="mt-1">
                <b-form-group id="input-group-2"  class="mt-4" label-for="input-3">
                <b-button @click="search()">Pesquisar</b-button>
                </b-form-group>
            </b-col>
        </b-row>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      filters: {
          is_finished: "",
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
    async search() {
        try{
            const response = await axios.post('http://localhost:8000/api/reports-pending/search', this.filters)
            const data = await response.data

             this.$emit('dataSearched', data)
        } catch (e) {
            console.log(e)
        }
    },
  },
};
</script>
