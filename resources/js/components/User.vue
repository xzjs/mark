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
            <el-table-column
                    prop="type"
                    label="角色"
                    align="center">
                <template slot-scope="scope">
                    <el-tag v-for="role in scope.row.roles">{{role}}</el-tag>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
                title="添加用户"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="u" :rules="rule" ref="form">
                <el-form-item label="用户名" prop="name">
                    <el-input v-model="u.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="角色" prop="roles">
                    <el-checkbox-group v-model="u.roles">
                        <el-checkbox label="标记数学"></el-checkbox>
                        <el-checkbox label="标记计算机"></el-checkbox>
                        <el-checkbox label="标记批判性思维"></el-checkbox>
                        <el-checkbox label="上传案例"></el-checkbox>
                    </el-checkbox-group>
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
    import {mapState} from 'vuex';

    export default {
        name: "User",
        data() {
            return {
                u: {
                    name: "",
                    roles: [],
                },
                users: [],
                dialogVisible: false,
                rule: {
                    name: [{required: true, message: '请输入用户名', trigger: 'blur'}],
                    roles: [{required: true, message: '请至少选择一个权限', trigger: 'blur'}],
                }
            }
        },
        computed: {
            ...mapState(['user'])
        },
        methods: {
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.u = {};
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios.post('/users', this.u)
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
            if (this.user.permissions.indexOf('user') === -1) {
                this.$router.push('/');
            }
            this.getUsers();
        }
    }
</script>

<style scoped>

</style>