<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <div class="overflow-auto">
                    
                    <router-link
                    data-toggle="collapse"
                    :to="{ path: 'report' }">
                        <b-button variant="primary">
                            Adicionar
                        </b-button>
                    </router-link>

                
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
                            <b-pagination v-model="currentPage" :per-page="perPage" :total-rows="reports.length"></b-pagination>
                        </b-col>
                        </b-row>
                    </b-form>

                    <b-table
                        id="table-transition-example"
                        :items="reports"
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
                            <router-link data-toggle="collapse" :to="{ name: 'report-edit', params: { id: row.item.id } }">
                                <b-button variant="primary" size="sm">Editar</b-button>
                            </router-link>

                            <b-button variant="danger" @click="remove(row.item.id)" size="sm">Deletar</b-button>
                            
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
                reports: [],
                perPage: 10,
                search: '',
                fields:[
                    {key: 'id', label: 'ID', sortable: true },
                    {key: 'file_name', label: 'Nome do Arquivo', sortable: true },
                    {key: 'site',  label: 'Site', sortable: true },
                    {key: 'tool_name', label: 'Ferramenta', sortable: true },
                    {key: 'options', label: 'Opções'}
                ],
                isBusy: false,
                info: null,
                filterOn: []

            }
        },
        created() {
            this.getReports()
        },
        methods: {

            async getReports(){
                this.isBusy = true;
                try{
                    const response = await axios.get('http://localhost:8000/api/reports/').then(response => (this.info = response))
                    this.reports = await response.data.data
                }catch (err){
                    console.log(err)
                }
                this.isBusy = false
                console.log(this.reports)
            },

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
            }
        }
    }
</script>
