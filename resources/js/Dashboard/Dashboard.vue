<template>
    
    <div >
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="container">
                    <BarChart :chart-data="data" :width="250" :height="250"/>
                    <button @click="fillData()">RAndomico</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script src="./js/dashboard.js">
</script>
