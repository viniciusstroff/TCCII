<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <report-pending-search @dataSearched="getDataSearched($event)"/>
                <div class="overflow-auto">
                    <b-table
                            id="table-transition-example"
                            :items="reportsPending"
                            :fields="fields"
                            :per-page="perPage"
                            :current-page="currentPage"
                            striped
                            :busy="isBusy"
                            :filter-included-fields="filterOn"
                            small>
                            <template #table-busy>
                                <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Carregando os dados...</strong>
                                </div>
                            </template>
                             <template #cell(is_finished)="data">
                                 <template v-if="data.value === 0">
                                     <b-badge variant="warning">Pendente</b-badge>
                                 </template>
                                 <template v-if="data.value === 1">
                                     <b-badge variant="primary">Finalizado</b-badge>
                                 </template>
                                 
                            </template>
                            <template #cell(options)="row">
                                <b-button variant="primary" @click="audit(row.item.report_id)" size="sm">Auditar</b-button>
                                <b-button variant="primary" @click="isAuditFinished(row.item.report_id)" size="sm">Verificar se Finalizou</b-button>
                            </template>
                    </b-table>
                    
                    <b-pagination v-model="currentPage" :per-page="perPage" :total-rows="reportsPending.length"></b-pagination>
                </div>
            </div>
           
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                currentSort: 'site',
                currentSortDir: 'asc',
                currentPage: 1,
                reportsPending: [],
                perPage: 10,
                fields:[
                    {key: 'id', label: 'ID', sortable: true },
                    {key: 'report_id', label: 'ID do Relatorio', sortable: true },
                    {key: 'is_finished',  label: 'Terminou?', sortable: true },
                    {key: 'created_at', label: 'Criado em', sortable: true },
                    {key: 'updated_at', label: 'Atualizao em', sortable: true },
                    {key: 'options', label: 'Opcoes', sortable: false }
                ],
                isBusy: false,
                finished: false,
                filterOn: []

            }
        },
        mounted() {
            this.getPendingReports()
        },
        methods: {

            async getDataSearched(listReportPendingSearched){
                this.isBusy = true;
                this.reportsPending = listReportPendingSearched
                this.isBusy = false
            },
            async getPendingReports(){
                this.isBusy = true;
                try{
                    const response = await axios.get('http://localhost:8000/api/reports-pending/').then(response => (this.info = response))
                    const data = await response.data.data
                    this.reportsPending = data
                    // this.reportsPending = data.map(item => {
                    //     if(item.is_finished === 0)
                    //         item.is_finished = "Pendente"
                    //     if(item.is_finished === 1)
                    //         item.is_finished = "Finalizado"
                    //     return item
                    // })
                }catch (err){
                    console.log(err)
                }
                this.isBusy = false
            },
            
            linkGen(pageNum) {
                return pageNum === 1 ? '?' : `?page=${pageNum}`
            },

            async audit(report_id){
                this.isBusy = true;
                try{
                    const response = await axios.post('http://localhost:8000/api/reports-pending/audit', {report_id: report_id}).then(response => (this.info = response))
                    this.reportsPending = await response.data.data
                }catch (err){
                    console.log(err)
                }
                this.isBusy = false
            },

             async isAuditFinished(report_id){
                this.isBusy = true;
                try{
                    const response = await axios.get(`http://localhost:8000/api/reports-pending/finished/${report_id}`).then(response => (this.info = response))
                    this.finished = await reponse.data.data

                    this.getPendingReports()
                }catch (err){
                    console.log(err)
                }
                this.isBusy = false
            }
            
        }
    }
</script>
