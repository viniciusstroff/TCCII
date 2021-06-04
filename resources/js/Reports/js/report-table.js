import PerPage from "../../components/geral/Table/PerPage.vue";

export default {
    components: {
        PerPage
    },
    props: {
        filterIncludedFields: {
            type: Array|Object,
        },
        reports: Array,

        pageOptions: {
            type: Array,
            default: () => [2, 10, 15, 50]
        },

        currentSort: { 
            type: String,
            default: 'site'
        },

        currentSortDir: {
            type: String,
            default: 'asc'
        },

        filter: {
            type: String
        },
        
        isBusy: {
            type: Boolean,
            defult: false
        }

    },
    data() {
        return {
            search: '',
            perPage: 2,
            fields:[
                {key: 'selectedReports', label: 'Selecionados'},
                {key: 'id', label: 'ID', sortable: true },
                {key: 'file_fake_name', label: '    Arquivo', sortable: true },
                {key: 'site',  label: 'Site', sortable: true },
                {key: 'tool_name', label: 'Ferramenta', sortable: true },
                {key: 'status', label: 'Status', sortable: true },
                {key: 'created_at', label: 'Criado em', sortable: true },
                {key: 'updated_at', label: 'Atualizado em', sortable: true },
                {key: 'options', label: 'Opções'}
            ],
            info: null,
            filterOn: [],
            selectedReports: [],
            disabled: true,
            currentPage: 1

        }
    },
   
    methods: {

        linkGen(pageNum) {
            return pageNum === 1 ? '?' : `?page=${pageNum}`
        },
        async remove(id) {
            this.isBusy = true;
            try{
                const response = await this.axios.delete(`/api/reports/${id}/remove`).then(response => (this.info = response))
                
                if(response.data.success === true){
                    this.reports = this.reports.filter(report => report.id !== id)
                }

            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        },
        selectAllRows() {
            this.$refs.reportsTable.selectAllRows()
        },
        clearSelected() {
            this.$refs.reportsTable.clearSelected()
        },
        onRowSelected(items) {
            const listOfReportsId = items.reduce(function(accumulator, element) {
                if(element.id){
                    accumulator.push(element.id)
                }
                return accumulator
            }, [])
            this.selectedReports = listOfReportsId
            this.disabled = this.selectedReports.length > 10 || this.selectedReports.length <= 0
        },
        auditSelectedReports() {
            this.$emit('selectedReports', this.selectedReports)
        }
    }
}