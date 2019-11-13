<template>
    <el-container>
        <el-header>
            <el-menu
                    :default-active="activeIndex"
                    mode="horizontal"
                    background-color="#545c64"
                    text-color="#fff"
                    :router=true
                    active-text-color="#ffd04b">
                <el-menu-item v-if="user.permissions.indexOf('book.read')!==-1" index="/book">案例</el-menu-item>
                <el-menu-item v-if="user.permissions.indexOf('mark.math')!==-1" index="/math">数学</el-menu-item>
                <el-menu-item v-if="user.permissions.indexOf('mark.cs')!==-1" index="/computer">计算机/AI</el-menu-item>
                <el-menu-item v-if="user.permissions.indexOf('mark.cr')!==-1" index="2-3">批判性思维</el-menu-item>
                <el-menu-item index="user" v-if="user.permissions.indexOf('user')!==-1">用户管理</el-menu-item>
                <el-menu-item class="username" index="password">修改密码</el-menu-item>
            </el-menu>
        </el-header>
        <el-main>
            <router-view></router-view>
        </el-main>
    </el-container>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Main",
        computed: {
            ...mapState(['user'])
        },
        data() {
            return {
                activeIndex: "/book",
            }
        },
        mounted() {
            let path = this.$route.path;
            if (path !== '/') {
                this.activeIndex = path;
            }
        },
        methods: {}
    }
</script>

<style scoped>
    .username {
        float: right;
    }
</style>