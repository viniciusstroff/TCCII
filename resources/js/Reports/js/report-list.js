import ReportTable from "../ReportTable.vue";


export default {
    components: {
        ReportTable
    },
    data() {
        return {
            reports: [],
            selectedReports: [],
            search: '',
            isBusy: false,
            info: null

        }
    },
    created() {
        this.searchReports()
    },
    watch: {
        selectedReports: function (val) {
            if(this.selectedReports !== undefined && this.selectedReports.length > 0 ){
                this.selectedReports = val
                this.auditSelectedReports()
            }
        }
    },
    methods: {
        async auditSelectedReports() {
            this.isBusy = true;
            try{
                const response = await this.axios.post('/api/reports/audit', {reports: this.selectedReports}).then(response => (this.info = response))
            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        },
        
        getSearched(searched) {
            this.search = searched
        },

        async searchReports() {
            this.isBusy = true
            try{
                const response = await axios.post('/api/reports/search', {})
                this.reports = response.data.data.data
            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        },

        setReports(reports) {
            this.reports = reports
        },

        async remove(id) {
            this.isBusy = true;
            try{
                const response = await axios.delete(`/api/reports/${id}/remove`).then(response => (this.info = response))
                
                if(response.data.success === true){
                    this.reports = this.reports.filter(report => report.id !== id)
                }

            }catch (err){
                console.log(err)
            }
            this.isBusy = false
        }
    }
}