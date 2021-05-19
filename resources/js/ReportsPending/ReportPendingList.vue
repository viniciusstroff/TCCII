<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <div class="overflow-auto">
                
                    <p class="mt-3">Current Page: {{ currentPage }}</p>
                    <b-form>
                        <b-row>
                        <b-col sm="7" md="6" class="my-1">
                            <!-- <b-form-group
                                id="input-group-1"
                                label="Email address:"
                                label-for="input-1"
                                description="We'll never share your email with anyone else."
                            > -->
                                <b-form-input
                                id="input-1"
                                v-model="search"
                                type="search"
                                class="mb-2 mr-sm-2 mb-sm-0"
                                placeholder="Procure"
                                ></b-form-input>
                            <!-- </b-form-group> -->
                        </b-col>
                        <b-col sm="4" md="5" class="my-1">
                            <b-pagination v-model="currentPage" :per-page="perPage" :total-rows="reportsPending.length"></b-pagination>
                        </b-col>
                        </b-row>
                    </b-form>
                    {{this.finished}}
                    <b-table
                        id="table-transition-example"
                        :items="reportsPending"
                        :fields="fields"
                        :per-page="perPage"
                        :current-page="currentPage"
                        striped
                        :busy="isBusy"
                        :filter="search"
                        :filter-included-fields="filterOn"
                        small>
                        <template #table-busy>
                            <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Carregando os dados...</strong>
                            </div>
                        </template>
                        <template #cell(options)="row">
                            <b-button variant="primary" @click="audit(row.item.report_id)" size="sm">Auditar</b-button>
                            <b-button variant="primary" @click="isAuditFinished(row.item.report_id)" size="sm">Verificar se Finalizou</b-button>
                        </template>
                    </b-table>

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
                search: '',
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

            async getPendingReports(){
                this.isBusy = true;
                try{
                    const response = await axios.get('http://localhost:8000/api/reports-pending/').then(response => (this.info = response))
                    this.reportsPending = await response.data.data
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
