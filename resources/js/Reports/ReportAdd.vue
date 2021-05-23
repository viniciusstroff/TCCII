<template>
    <div>
      <report-form @sites="getSites($event)" @form="getForm($event)"></report-form>

      <div v-if="!isEditing">
      <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="sites.length"></b-pagination>
        <b-table :items="sites" :per-page="table.perPage"  
          :fields="table.fields"
          striped
          small
          :current-page="table.currentPage">
        </b-table>
      </div>
      
      <b-button type="button" variant="primary" @click="saveOrEditReport()">{{isEditing ? 'Atualizar' : 'Salvar'}}</b-button>
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
        isEditing: false,
        form: {},
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
    created: function(){
      this.isEditing = this.$route.params.id ? true : false
    },
    methods: {
      async saveReports()
      {
        try{
         
          const response = await axios.post('http://localhost:8000/api/reports/save', { sites: this.sites })
          const data = await response.data.data

        } catch (e)
        {
          console.log(e)
        }
         this.$router.push({path: 'reports'})
      },

      async saveOrEditReport() {
  
         if(this.isEditing) {
           console.log('atuaizando')
            this.updateReport()
            return
          }


           console.log('salvando')
          this.saveReports()
      },

      async updateReport()
      {
        try{
          const reportId = this.$route.params.id
          const response = await axios.put(`http://localhost:8000/api/reports/${reportId}/update`, { tool_name: this.form.tool, site: this.form.site })
          const data = await response.data.data
        } catch (e) {
          console.log(e)
        }

        this.$router.back()
      },
      getSites(sites)
      {
        this.sites = sites
      },
      getForm(form){
        this.form = form
      }
    }
  }
</script>
