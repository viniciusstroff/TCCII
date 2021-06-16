<template>
    <div>
      <h4>:: {{isEditing ? 'Edição' : 'Cadastro'}} de Audição ::</h4>
      <report-form @reports="getReports($event)" @form="getForm($event)" :errors="errors"></report-form>
      <!-- <div v-if="!isEditing">
      <b-pagination v-model="table.currentPage" :per-page="table.perPage" :total-rows="reports.length"></b-pagination>
        <b-table :items="reports" :per-page="table.perPage"  
          :fields="table.fields"
          striped
          small
          :current-page="table.currentPage">
          <template #cell(options)="row">
          <b-button variant="danger" @click="remove(row.index)" size="sm"
            >Remover</b-button
          >
        </template>
        </b-table>
      </div> -->
      <b-button type="button" variant="primary" @click="saveReport()">{{isEditing ? 'Atualizar' : 'Salvar'}}</b-button>
      <b-button type="reset" variant="danger">Limpar</b-button>
      <router-link data-toggle="collapse" :to="{ path: '/reports' }" back>
        <b-button variant="primary">
            Voltar
        </b-button>
      </router-link>    
    </div>
</template>

<script src="./js/report-add.js">
</script>
