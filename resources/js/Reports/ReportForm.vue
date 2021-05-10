<template>
    <div>
      <b-form @submit="onSubmit" @reset="onReset" v-if="show">
          <b-row>
            <b-col sm="6">
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
            <b-col sm="2">
                <br> 
                <b-button type="button" @click="addSite()" variant="danger">Adicionar</b-button>
            </b-col>
          </b-row>
          <b-form-group id="input-group-3" label="Ferramenta" label-for="input-3">
              <b-form-select
              id="input-3"
              v-model="form.tool"
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
              <b-form-checkbox value="me">Check me out</b-form-checkbox>
              <b-form-checkbox value="that">Check that out</b-form-checkbox>
              </b-form-checkbox-group>
          </b-form-group>

      </b-form>
      <!-- <b-card class="mt-3" header="Form Data Result">
          <pre class="m-0">{{ form }}</pre>
          <pre class="m-0">{{data}} </pre>
      </b-card> -->
    </div>
</template>

<script>
export default {

  data() {
    return {
      form: {
        site: '',
        tool: '',
        checked: []
      },
      data: {
        sites: [],
      },
      tools: [
        { text: 'Select One', value: null },
        { text: 'Lighthouse', value: 'lighthouse' }, 
        { text: 'Wave', value: 'wave' }],
      show: true
    }
  },
  methods: {
    onSubmit(event) {
      event.preventDefault()
      alert(JSON.stringify(this.form))
    },

    onReset(event) {
      event.preventDefault()
      this.form = {}
      this.show = false
      this.$nextTick(() => {
        this.show = true
      })
    },

    addSite() {
      const site = { url: this.form.site, tool_name: this.form.tool }
      this.data.sites.push(site)
      this.data.sites.reverse()
      this.form = {}

      this.$emit('sites', this.data.sites)
    }
  }
}
</script>
