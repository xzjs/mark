<template>
    <el-form :model="ruleForm2" :rules="rules2"
             status-icon
             ref="ruleForm2"
             label-position="left"
             label-width="0px"
             class="demo-ruleForm login-page">
        <h3 class="title">系统登录</h3>
        <el-form-item prop="name">
            <el-input type="text"
                      v-model="ruleForm2.name"
                      auto-complete="off"
                      placeholder="用户名"
            ></el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password"
                      v-model="ruleForm2.password"
                      auto-complete="off"
                      placeholder="密码"
            ></el-input>
        </el-form-item>
        <el-form-item style="width:100%;">
            <el-button type="primary" style="width:100%;" @click="handleSubmit" :loading="logining">登录</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        data() {
            return {
                logining: false,
                ruleForm2: {
                    name: '',
                    password: '',
                },
                rules2: {
                    name: [{required: true, message: '请输入用户名', trigger: 'blur'}],
                    password: [{required: true, message: '请输入密码', trigger: 'blur'}]
                },
            }
        },
        methods: {
            ...mapActions([
                'setUser'
            ]),
            handleSubmit(event) {
                this.$refs.ruleForm2.validate((valid) => {
                    if (valid) {
                        axios.post('/tokens', this.ruleForm2)
                            .then(response => {
                                this.setUser(response.data);
                            })
                            .catch(error => {
                                this.$message.error("用户名或密码错误");
                            })
                    } else {
                        console.log('别闹，好好输');
                        return false;
                    }
                })
            }
        }
    };
</script>

<style scoped>
    .login-container {
        width: 100%;
        height: 100%;
    }

    .login-page {
        -webkit-border-radius: 5px;
        border-radius: 5px;
        margin: 180px auto;
        width: 350px;
        padding: 35px 35px 15px;
        background: #fff;
        border: 1px solid #eaeaea;
        box-shadow: 0 0 25px #cac6c6;
    }

    label.el-checkbox.rememberme {
        margin: 0px 0px 15px;
        text-align: left;
    }
</style>