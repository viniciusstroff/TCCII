<template>
    <div>
    
        <div v-show="hasError">
          <b-alert  show fade variant="danger" class="small">
            <h5 v-show="hasError">Probemas nas informações preenchidas no formulário</h5>
            <p v-for="error in errors" :key="error"><strong>*</strong> {{error}}</p>
          </b-alert>
        </div>

        <div class="d-flex justify-content-center">
          <b-spinner v-show="loading" label="Loading..."></b-spinner>
        </div>
        
        <b-form @reset="onReset" v-show="!loading">
            <b-row>
              <b-col sm="6">
              <b-form-group
                  id="input-group-1"
                  label="Site"
                  label-for="input-1"
                  description="https://www.google.com"
                  >
                  <b-form-input
                  id="input-1"
                  v-model="form.site"
                  type="url"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  placeholder="Digite um site"
                  required
                  ></b-form-input>
              </b-form-group>
              </b-col>
              <b-col sm="2" v-if="!isEditing">
                  <br> 
                  <b-button type="button" @click="addReport()" variant="primary">Adicionar2</b-button>
              </b-col>
            </b-row>
            <b-form-group id="input-group-3" label="Ferramenta" label-for="input-3">
                <b-form-select
                id="input-3"
                v-model="form.tool"
                :options="tools"
                required
                ></b-form-select>
            </b-form-group>

            <b-form-group id="input-group-4" v-slot="{ ariaDescribedby }">
                <b-form-checkbox-group
                v-model="form.checked"
                id="checkboxes-4"
                :aria-describedby="ariaDescribedby"
                >
                <b-form-checkbox value="me">Check me out</b-form-checkbox>
                <b-form-checkbox value="that">Check that out</b-form-checkbox>
                </b-form-checkbox-group>
            </b-form-group>

        </b-form>
        <!-- <b-card class="mt-3" header="Form Data Result">
            <pre class="m-0">{{ form }}</pre>
            <pre class="m-0">{{data}} </pre>
        </b-card> -->
    </div>
</template>

<script>
export default {

  data() {
    return {
      form: {
        site: '',
        tool: '',
        checked: []
      },
      errors: [],
      hasError: false,
      data: {
        id: null,
        reports: [],
      },
      tools: [
        { text: 'Select One', value: null },
        { text: 'Lighthouse', value: 'lighthouse' }, 
        { text: 'Wave', value: 'wave' }],
      loading: false,
      isEditing: false
    }
  },
   created(){
     this.setFlagEditing()
     this.getData()
  },
  
  methods: {
    setFlagEditing(){
      this.data.id = this.$route.params.id
      this.isEditing = this.data.id ? true : false
       this.$emit('isEditing', this.isEditing)
    },

    async getData(){
      if(!this.isEditing) return
      this.loading = true
      try{

        const response = await axios.get(`http://localhost:8000/api/reports/${this.data.id}`).then(response => (this.info = response))
        const data = await response.data.data
        this.form.site = data.site
        this.form.tool = data.tool_name 
        
        this.$emit('form', this.form)
        
      }catch( e){
        console.log(e)
      }
      this.loading = false
    },
    
    setError(message){
      this.errors.push(message);
    },
    
    validate(){
      this.errors = [];
       this.hasError = false

      if(!this.form.site)
        this.setError('O campo Site é obrigatório.');
      if(!this.form.tool)
        this.setError('O campo Ferramenta é obrigatória.');
      
      const isValid = (this.errors.length > 0 ) ? false : true
      this.hasError = !isValid
      return isValid
    },

    onReset(event) {
      event.preventDefault()
      this.form = {}
      this.data.reports = []
      this.show = false
      this.$nextTick(() => {
        this.show = true
      })
    },

    addReport() {
      if(!this.validate()){
          return
      }
      
      const report = { url: this.form.site, tool_name: this.form.tool }
      this.data.reports.push(report)
      this.data.reports.reverse()
      this.form = {}

      this.$emit('reports', this.data.reports)
    }
  }
}
</script>
