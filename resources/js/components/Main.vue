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
                <el-menu-item index="book">案例</el-menu-item>
                <el-submenu index="#">
                    <template slot="title">标注</template>
                    <el-menu-item v-if="user.type === 0 || user.type === 1" index="math">数学</el-menu-item>
                    <el-menu-item v-if="user.type === 0 || user.type === 2" index="2-2">计算机/AI</el-menu-item>
                    <el-menu-item v-if="user.type === 0 || user.type === 3" index="2-3">批判性思维</el-menu-item>
                </el-submenu>
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