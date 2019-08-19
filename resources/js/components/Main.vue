<template>
    <el-container>
        <el-header>
            <el-menu
                    :default-active="activeIndex"
                    class="el-menu-demo"
                    mode="horizontal"
                    background-color="#545c64"
                    text-color="#fff"
                    :router=true
                    active-text-color="#ffd04b">
                <el-menu-item index="book">图书管理</el-menu-item>
                <el-menu-item index="document">案例管理</el-menu-item>
                <el-menu-item index="user" v-if="user.type===0">用户管理</el-menu-item>
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
                activeIndex: "book",
            }
        }
    }
</script>

<style scoped>
    .username {
        float: right;
    }
</style>