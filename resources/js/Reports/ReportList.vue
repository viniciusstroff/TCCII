<template>
    
    <div >
        <div class="row">
            <div class="col-md-12 mb-5">
                <button-redirect :label="'Adicionar'" :routes="{ path: 'report' }" :button-class="{ variant: 'primary', class: 'mb-3' }" ></button-redirect>
                <report-search @reportsSearched="setReports($event)"/>
                <report-table :reports="reports" :filter="search" :filterIncludedFields="[]"  @selectedReports="selectedReports = $event"></report-table>

            </div>
        </div>
    </div>
</template>

<script src="./js/report-list.js">
    
</script>
