<template>
    <div>
      <report-form @sites="getSites($event)" @isEditing="isEditing($event)"></report-form>

      <div v-if="!isEditing">
      <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="sites.length"></b-pagination>
        <b-table :items="sites" :per-page="table.perPage"  
          :fields="table.fields"
          striped
          small
          :current-page="table.currentPage">
        </b-table>
      </div>
      
      <b-button type="button" variant="primary" @click="saveReports()">Salvar</b-button>
      <b-button type="reset" variant="danger">Limpar</b-button>
      <router-link data-toggle="collapse" :to="{ path: '/reports' }" back>
        <b-button variant="primary">
            Voltar
        </b-button>
      </router-link>    
    </div>
</template>

<script>
import ReportForm from './ReportForm.vue'
  export default {
  components: { ReportForm },
    data() {
      return {
        loading: false,
        table: {
          perPage: 5,
          currentPage: 1,
          fields: [
            {key: 'url',  label: 'Site', sortable: true },
            {key: 'tool_name',  label: 'Ferramenta', sortable: true }
          ]
        },
        sites: [],
      }
    },
    methods: {
      saveReports()
      {
        axios.post('http://localhost:8000/api/reports/', { sites: this.sites })
        .then(response => (
            console.log(response.data)
        ))
        .catch(error => console.log(error))
        .finally(() =>
         this.loading = false)
        //  this.$router.push({path: 'reports'})
      },
      isEditing(bool){
        console.log(bool)
        this.isEditing = bool
      },
      getSites(sites)
      {
        this.sites = sites
      }
    }
  }
</script>
