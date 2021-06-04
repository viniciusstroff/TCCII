<template>
  <div>
   
    <per-page @perPage="perPage = $event"></per-page>
    <div class="overflow-auto">
      <b-table
        :items="reports"
        :fields="fields"
        :per-page="perPage"
        :current-page="currentPage"
        sort-icon-left
        striped
        :busy="isBusy"
        :filter="filter"
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
          <button-redirect
            :label="'Editar'"
            :routes="{ name: 'report-edit', params: { id: row.item.id } }"
            :button-class="{ variant: 'primary', class: 'mb-3', size: 'sm' }"
          ></button-redirect>
          <b-button variant="danger" @click="remove(row.item.id)" size="sm"
            >Deletar</b-button
          >
        </template>

        <template #cell(status)="data">
          <template v-if="data.value === 0">
            <b-badge variant="warning">Pendente</b-badge>
          </template>
          <template v-if="data.value === 1">
            <b-badge variant="primary">Finalizado</b-badge>
          </template>
        </template>
        <template #cell(selectedReports)="{ rowSelected }">
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
      <b-col cols="6">
        <b-button size="sm" @click="selectAllRows">Selecionar todos</b-button>
        <b-button size="sm" @click="clearSelected">Desmarcar</b-button>
        <b-button size="sm" :disabled="disabled" variant="primary" @click="auditSelectedReports()">Auditar Selecionados</b-button>
      </b-col>
      <b-col cols="3">
        <b-pagination
          v-model="currentPage"
          :per-page="perPage"
          :total-rows="reports.length"
        ></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>
<script src="./js/report-table.js">
</script>
