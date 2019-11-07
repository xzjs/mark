<template>
    <div>
        <el-button type="primary" icon="el-icon-edit" @click="dialogVisible=true">添加</el-button>
        <el-table
                border
                :data="users"
                style="width: 100%">
            <el-table-column
                    prop="name"
                    label="用户名"
                    align="center">
            </el-table-column>
        </el-table>
        <el-dialog
                title="添加用户"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="user" :rules="rule" ref="form">
                <el-form-item label="用户名" prop="name">
                    <el-input v-model="user.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="标注类型" prop="type">
                    <el-select v-model="user.type">
                        <el-option label="数学" value="1"></el-option>
                        <el-option label="计算机" value="2"></el-option>
                        <el-option label="批判性思维" value="3"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="密码">
                    初始密码为111111
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="create">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "User",
        data() {
            return {
                user: {
                    name: "",
                    type: '',
                },
                users: [],
                dialogVisible: false,
                rule: {
                    name: [{required: true, message: '请输入用户名', trigger: 'blur'}],
                    type: [{required: true, message: '请选择标注类型', trigger: 'blur'}],
                }
            }
        },
        methods: {
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.user = {};
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios.post('/users', this.user)
                            .then((response) => {
                                this.getUsers();
                                this.$refs.form.resetFields();
                                this.dialogVisible = false;
                            })
                            .catch((error) => {
                                let message = error.response.data.message;
                                this.$message.error(message);
                            })
                    } else {
                        return false;
                    }
                });
            },
            getUsers() {
                axios.get('/users')
                    .then((response) => {
                        this.users = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
        },
        mounted() {
            this.getUsers();
        }
    }
</script>

<style scoped>

</style>