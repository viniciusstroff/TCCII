<template>
    <div class="col-md-12">
       <h4>:: Dados da Audição ::</h4>

        <router-link data-toggle="collapse" :to="{ path: '/reports' }" back>
        <b-button variant="primary">
            Voltar
        </b-button>
      </router-link>    
      <div class="col-md-4" v-if="!carregando">
        <BarChart :chart-data="data" :width="50" :height="50" :options="data.options"/>
      </div>
     
    </div>
</template>

<script src="./js/report-score.js">
</script>
