<template>
    <div class="">
        <div v-show="hasError">
          <b-alert  show fade variant="danger" class="small">
            <h5 v-show="hasError">Probemas nas informações preenchidas no formulário</h5>
            <p v-for="error in errors" :key="error"><strong>*</strong> {{error}}</p>
          </b-alert>
        </div>

        <div class="d-flex justify-content-center">
          <b-spinner v-show="loading" label="Loading..."></b-spinner>
        </div>
        
        <b-form @reset="onReset" v-show="!loading">
            <b-row>
              <b-col sm="6" >
              <b-form-group
                  id="input-group-1"
                  label="Site"
                  label-for="input-1"
                  description="https://www.google.com"
                  >
                  <b-form-input
                  id="input-1"
                  v-model="form.site"
                  type="url"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  placeholder="Digite um site"
                  required
                  ></b-form-input>
              </b-form-group>
              </b-col>
              <b-col sm="2" class="mt-4" v-if="!isEditing">
                  
                  <b-button type="button" @click="addReport()" variant="primary">Adicionar</b-button>
              </b-col>
            </b-row>
            <b-form-group id="input-group-3" label="Ferramenta" label-for="input-3">
                <b-form-select
                id="input-3"
                v-model="form.tool_name"
                :options="tools"
                required
                ></b-form-select>
            </b-form-group>

            <b-form-group id="input-group-4" v-slot="{ ariaDescribedby }">
                <b-form-checkbox-group
                v-model="form.checked"
                id="checkboxes-4"
                :aria-describedby="ariaDescribedby"
                >
                  <b-form-checkbox value="me">Check that out</b-form-checkbox>
                  <b-form-checkbox value="that">Check that out</b-form-checkbox>
                </b-form-checkbox-group>
            </b-form-group>

        </b-form>
    </div>
</template>

<script src="./js/report-form.js">
</script>
