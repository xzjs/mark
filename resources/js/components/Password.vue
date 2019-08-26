<template>
    <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="原密码" prop="oldPwd">
            <el-input type="password" v-model="ruleForm.oldPwd" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="新密码" prop="newPwd">
            <el-input type="password" v-model="ruleForm.newPwd" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="确认新密码" prop="repeatNewPwd">
            <el-input type="password" v-model="ruleForm.repeatNewPwd" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        name: "Password",
        data() {
            let validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入新密码'));
                } else {
                    if (this.ruleForm.repeatNewPwd !== '') {
                        this.$refs.ruleForm.validateField('repeatNewPwd');
                    }
                    callback();
                }
            };
            let validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.ruleForm.newPwd) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                ruleForm: {
                    oldPwd: '',
                    newPwd: '',
                    repeatOldPwd: '',
                },
                rules: {
                    oldPwd: [{required: true, message: '请输入原密码', trigger: 'blur'}],
                    newPwd: [{validator: validatePass, trigger: 'blur'}],
                    repeatNewPwd: [{validator: validatePass2, trigger: 'blur'}],
                }
            }
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.put('/users/0', this.ruleForm)
                            .then((response) => {
                                this.$message({
                                    message: '修改成功',
                                    'type': 'success'
                                })
                            })
                            .catch((error) => {
                                console.log(error);
                                this.$message.error(error.response.message);
                            })
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            }
        }
    }
</script>

<style scoped>

</style>