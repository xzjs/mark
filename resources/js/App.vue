<template>
    <div id="app">
        <Main v-if="user.name"></Main>
        <Login v-else></Login>
    </div>
</template>
<script>
    import Login from './components/Login';
    import Main from './components/Main';
    import {mapState, mapActions} from 'vuex';

    export default {
        name: 'app',
        data() {
            return {
                visible: false,
            }
        },
        components: {
            Login,
            Main
        },
        computed: {
            ...mapState(['user'])
        },
        mounted() {
            if (this.user.name === '') {
                axios.get('/tokens')
                    .then(response => {
                        this.setUser(response.data);
                    })
                    .catch(error => {

                    })
            }
        },
        methods: {
            ...mapActions(['setUser'])
        }
    };
</script>
<style scoped>

</style>
