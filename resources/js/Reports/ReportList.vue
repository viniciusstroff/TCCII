<template>
    <div >
        <div class="row">
            <div class="col-md-12">
                <button-redirect :label="'Adicionar'" :routes="{ path: 'report' }" :button-class="{ variant: 'primary', class: 'mb-3' }" ></button-redirect>
                <report-search @reportsSearched="setReports($event)"/>
                
                <b-row>
                    <b-col sm="1" lg="2" md="1" class="mb-1">
                        <b-form-group
                        label=""
                        label-for="per-page-select"
                        label-cols-sm="1"
                        label-cols-md="1"
                        label-cols-lg="1"
                        label-align-sm="right"
                        label-size="sm"
                        class="mb-0"
                        >
                        <b-form-select
                            id="per-page-select"
                            v-model="perPage"
                            :options="pageOptions"
                            size="sm"
                        ></b-form-select>
                        </b-form-group>
                    </b-col>
                </b-row>
                <div class="overflow-auto">

                    <b-table
                        :items="reports"
                        :fields="fields"
                        :per-page="perPage"
                        :current-page="currentPage"
                        sort-icon-left
                        striped
                        :busy="isBusy"
                        :filter="search"
                        ref="reportsTable"
                        :filter-included-fields="filterOn"
                        selectable
                        @row-selected="onRowSelected"
                        >
                        <template #table-busy>
                            <div class="text-center text-danger my-2">
                                <b-spinner class="align-middle"></b-spinner>
                                <strong>Carregando os dados...</strong>
                            </div>
                        </template>

                        <template #cell(options)="row">
                            <button-redirect :label="'Editar'" :routes="{ name: 'report-edit',  params: { id: row.item.id } }" :button-class="{ variant: 'primary', class: 'mb-3', size:'sm' }" ></button-redirect>
                            <b-button variant="danger" @click="remove(row.item.id)" size="sm">Deletar</b-button>
                        </template>

                        <template #cell(is_finished)="data">
                            <template v-if="data.value === 0">
                                <b-badge variant="warning">Pendente</b-badge>
                            </template>
                            <template v-if="data.value === 1">
                                <b-badge variant="primary">Finalizado</b-badge>
                            </template>
                                 
                        </template>
                        <template #cell(selected)="{ rowSelected }">
                            <template v-if="rowSelected">
                                <span aria-hidden="true">&check;</span>
                                <span class="sr-only">Selecionado</span>
                            </template>
                            <template v-else>
                                <span aria-hidden="true">&nbsp;</span>
                                <span class="sr-only">Não selecionado</span>
                            </template>
                        </template>
                    </b-table>
                </div>
                <b-row align-h="between">
                    <b-col cols="4">
                        <b-button size="sm" @click="selectAllRows">Selecionar todos</b-button>
                        <b-button size="sm" @click="clearSelected">Desmarcar</b-button>
                        
                    </b-col>
                    <b-col cols="3">
                        <b-pagination v-model="currentPage" :per-page="perPage" :total-rows="reports.length"></b-pagination>
                    </b-col>
                
                </b-row>
            </div>
        </div>
    </div>
</template>

<script src="./js/report-list.js">
    
</script>
