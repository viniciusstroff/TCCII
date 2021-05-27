
import ReportForm from '../ReportForm.vue'
import util  from '../../util/index'

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
            {key: 'site',  label: 'Site', sortable: true },
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
        },
        errors: [],
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

      async saveReport()
      {
        if(!this.validate()){
            return
        }
        
        let  response;
        try{
            if(this.isEditing) {
                response = await this.axios.put(`/api/reports/${this.form.id}/update`, this.form )
            } else{
                response = await this.axios.post('/api/reports/save', this.form )
            }
            this.response = util.mapearObjetos(response, {})
            this.dealWithAfterSavingRecord()
        //   this.response.success = this.response.data.success
        //   this.response.message = response.data.message

        } catch (e)
        {
          util.criarAlertaToastDeErro(this, 'Aviso', this.response.data.message)
        }

      },

      dealWithAfterSavingRecord() {
        if(this.response.data.success === true){
          this.$router.push({ name: 'reports-list' }).then(() => {
            util.criarAlertaToastDeSucesso(this, 'Aviso',  this.response.data.message)
          })
        } else {
          util.criarAlertaToastDeErro(this, 'Aviso', this.response.data.message)
        }
      },

      getReports(reports)
      {
        this.reports = reports
      },
      getForm(form){
        this.form = util.mapearObjetos(form, this.form)
      },
      validate(){
        this.errors = [];
  
        if(!this.form.site)
            this.errors.push('O campo Site é obrigatório.');
        if(!this.form.tool_name)
            this.errors.push('O campo Ferramenta é obrigatória.');
        
        const isValid = (this.errors.length > 0 ) ? false : true
        return isValid
      },
    }
  }