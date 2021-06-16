<template>
    <div>
      <b-button type="button" variant="primary" @click="saveReport()"></b-button>
      <b-button type="reset" variant="danger">Limpar</b-button>
      <router-link data-toggle="collapse" :to="{ path: '/reports' }" back>
        <b-button variant="primary">
            Voltar
        </b-button>
      </router-link>    
    </div>
</template>

<script src="./js/report-score.js">
</script>
