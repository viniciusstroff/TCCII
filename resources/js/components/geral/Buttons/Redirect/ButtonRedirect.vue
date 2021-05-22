<template>
    <div>
        <router-link
        :to="this.routes">
            <b-button :variant="this.buttonClass.variant" :class="this.buttonClass.class" :size="this.buttonClass.size">
                {{this.label}}
            </b-button>
        </router-link>
    </div>
</template>
<script src="./js/button-redirect.js">
</script>
