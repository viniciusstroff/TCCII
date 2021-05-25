<template>
    <div>
      <report-form @reports="getReports($event)" @form="getForm($event)"></report-form>
      <div v-if="!isEditing">
      <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="reports.length"></b-pagination>
        <b-table :items="reports" :per-page="table.perPage"  
          :fields="table.fields"
          striped
          small
          :current-page="table.currentPage">
          <template #cell(options)="row">
          <b-button variant="danger" @click="remove(row.index)" size="sm"
            >Remover</b-button
          >
        </template>
        </b-table>
      </div>
      <b-button :disabled="!hasReports && !isEditing" type="button" variant="primary" @click="saveOrEditReport()">{{isEditing ? 'Atualizar' : 'Salvar'}}</b-button>
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
import util  from '../util/index'

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
            {key: 'tool_name',  label: 'Ferramenta', sortable: true },
            {key: 'options', label: 'Opções'}
          ]
        },
        reports: [],
        hasReports: false,
        response: {
          success: false,
          message: "",
          errors: []
        }
      }
    },
    watch: {
      reports:  function () {
        this.hasReports = this.reports.length > 0
      }
    },
    created: function(){
      this.isEditing = this.$route.params.id ? true : false
    },
    methods: {
      remove(index) {
        this.reports.splice(index, 1);
      },

      async saveReports()
      {
        try{
         
          const response = await axios.post('http://localhost:8000/api/reports/save', { reports: this.reports })
          this.response.success = response.data.success
          this.response.message = response.data.message

        } catch (e)
        {
          console.log(e)
        }

        this.dealWithAfterSavingRecord()
      },

      dealWithAfterSavingRecord() {
        if(this.response.success){
          this.$router.push({ name: 'reports-list' }).then(() => {
            util.criarAlertaToastDeSucesso(this, 'Aviso', 'Dados salvos com sucesso.')
          })
        } else {
          util.criarAlertaToastDeErro(this, 'Aviso', this.retorno.mensagem)
        }
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
          this.response.success = response.data.success
          this.response.message = response.data.message
        } catch (e) {
          console.log(e)
        }

        this.dealWithAfterSavingRecord()
      },
      getReports(reports)
      {
        this.reports = reports
      },
      getForm(form){
        this.form = form
      }
    }
  }
</script>
