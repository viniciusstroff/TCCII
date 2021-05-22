export default {
    data() {
        return {
            currentSort: 'site',
            currentSortDir: 'asc',
            currentPage: 1,
            reports: [],
            perPage: 2,
            pageOptions: [2, 10, 15, 50],
            search: '',
            fields:[
                {key: 'selected', label: 'Selecionados'},
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
            selected: []

        }
    },
    created() {
        this.searchReports()
    },
    methods: {
        getSearched(searched) {
            this.search = searched
        },
        async getReports(){
            this.isBusy = true;
            try{
                const response = await axios.get('http://localhost:8000/api/reports/').then(response => (this.info = response))
                this.reports = response.data.data
            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        },

        async searchReports() {
            this.isBusy = true
            try{
                const response = await axios.post('http://localhost:8000/api/reports/search', {})
                this.reports = response.data.data.data
            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        },

        linkGen(pageNum) {
            return pageNum === 1 ? '?' : `?page=${pageNum}`
        },

        setReports(reports) {
            this.reports = reports
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
            this.selected = items
        },
    }
}