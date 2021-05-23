export default {
    props:{

    },
    data() {
        return {
            perPage: 2,
            pageOptions: [2, 10, 15, 50],

        }
    },
    created() {
        this.selectedPerPage()
    },
    methods: {
        selectedPerPage(){
            this.$emit('perPage', this.perPage)
        }
    }

}