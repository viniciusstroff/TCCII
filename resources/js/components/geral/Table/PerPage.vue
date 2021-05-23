<template>
    <div class="col-md-2 col-lg-2 col-sm-4 mb-1 text-left">
        <!-- <b-row>
            <b-col sm="1" lg="2" md="1" class="mb-1"> -->
                <!-- <b-form-group
                label=""
                label-for="per-page-select"
                label-cols-sm="1"
                label-cols-md="1"
                label-cols-lg="1"
                label-align-sm="left"
                label-size="sm"
                class="mb-0"
                > -->
                <b-form-select
                    id="per-page-select"
                    v-model="perPage"
                    :options="pageOptions"
                    @change="selectedPerPage()"
                    size="sm"
                ></b-form-select>
                <!-- </b-form-group> -->
            <!-- </b-col>
        </b-row> -->
    </div>
</template>

<script src="./js/per-page.js">
</script>