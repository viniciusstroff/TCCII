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
                {key: 'is_finished', label: 'Status', sortable: true },
                {key: 'created_at', label: 'Criado em', sortable: true },
                {key: 'updated_at', label: 'Atualizado em', sortable: true },
                {key: 'options', label: 'Opções'}
            ],
            isBusy: false,
            info: null,
            filterOn: [],
            selectedReports: [],
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
                const response = await axios.delete(`http://localhost:8000/api/reports/${id}/remove`).then(response => (this.info = response))
                
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
            this.selectedReports = items
        },
        auditSelectedReports() {
            this.$emit('selectedReports', this.selectedReports)
        }
    }
}