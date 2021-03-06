
import util from '../../util/index';

export default {
    props: {
        errors: {
            type: Array,
            default: []
        }
    },
  data() {
    return {
      form: {
        id: null,
        site: '',
        tool_name: null,
        checked: []
      },
      hasError: false,
      data: {
        id: null,
        reports: [],
      },
      tools: [
        { text: 'Selecione', value: null },
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
  watch: {
    form: {
      deep: true, // <!-- This is important;
      handler () {
         this.$emit('form', this.form)
      }
    },
    errors: {
        handler() {
            const isValid = (this.errors.length > 0 ) ? false : true
            this.hasError = !isValid
        }
    }
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

        const response = await this.axios.get(`/api/reports/${this.data.id}`)
                                    .then(response => (this.info = response))
        const data = response.data.data
        this.form = util.mapearObjetos(data, this.form)
        this.$emit('form', this.form)
        
      }catch( e){
        console.log(e)
      }
      this.loading = false
    },
    
    setError(message){
      this.errors.push(message);
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
  }
}