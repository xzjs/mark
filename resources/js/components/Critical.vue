<template>
    <div>
        <el-button type="primary" icon="el-icon-edit" @click="showDialog">添加</el-button>
        <el-table
                border
                :data="criticals"
                style="width: 100%">
            <el-table-column prop="id" label="编号" width="50px">
            </el-table-column>
            <el-table-column prop="text" label="文字">
            </el-table-column>
            <el-table-column prop="images" label="图片">
                <template slot-scope="scope">
                    <div v-for="image in scope.row.images">
                        <el-image :src="image.url+'-small'" fit="contain"
                                  :preview-src-list="getImgList(scope.row.images)"></el-image>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="analysis" label="分析">
            </el-table-column>
            <el-table-column v-if="user.roles.indexOf('管理员')!=-1" label="标注人" width="100px" prop="user.name">
            </el-table-column>
            <el-table-column v-if="user.roles.indexOf('管理员')!=-1" label="花费时间(s)" prop="cost" width="50px">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="100">
                <template slot-scope="scope">
                    <el-button @click="edit(scope.$index, scope.row)" size="small">编辑</el-button>
                    <br>
                    <el-button @click="del(scope.$index, scope.row)" type="danger" size="small">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="pagination">
            <el-pagination background layout="prev, pager, next" :total="total"
                           :page-size="10"
                           @current-change="currentChange"
                           @prev-click="currentChange"
                           @next-click="nextClick"
            ></el-pagination>
        </div>
        <el-dialog
                title="标注"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="critical" :rules="rule" ref="form" label-width="200px">
                <el-form-item label="文字">
                    <el-input type="textarea"
                              :rows="2" placeholder="请输入文字" v-model="critical.text">
                    </el-input>
                </el-form-item>
                <el-form-item label="图片">
                    <el-upload
                            ref="images"
                            list-type="picture-card"
                            action="/upload"
                            name="img"
                            accept="image/jpeg,image/png"
                            :limit=10
                            :file-list="critical.images"
                            :on-success="uploadSuccess"
                            :on-remove="deleteSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="分析" prop="analysis">
                    <el-input type="textarea"
                              :rows="2" placeholder="请输入分析" v-model="critical.analysis">
                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="create">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Critical",
        data() {
            return {
                total: 0,
                critical: {
                    text: '',
                    images: [],
                    analysis: '',
                    start: 0,
                },
                criticals: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    analysis: [{required: true, message: '请填写', trigger: 'blur'}],
                }
            }
        },
        computed: {
            ...mapState(['user']),
        },
        methods: {
            showDialog() {
                this.dialogVisible = true;
                this.critical.start = (new Date()).getTime();
            },
            getCriticals: function (params) {
                axios.get('/criticals', {params: params})
                    .then(response => {
                        this.criticals = response.data.data;
                        this.total = response.data.total;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.critical = {};
                        this.$refs.images.clearFiles();
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        if (this.critical.images || this.critical.text) {
                            if (this.critical.id) {
                                axios.put('/criticals/' + this.critical.id, this.critical)
                                    .then(response => {
                                        this.refresh();
                                    })
                                    .catch(error => {
                                        this.$message.error(error.response.data);
                                    })
                            } else {
                                axios.post('/criticals', this.critical)
                                    .then((response) => {
                                        this.refresh();
                                    })
                                    .catch((error) => {
                                        this.$message.error(error.response.data);
                                    })
                            }
                        } else {
                            this.$message.error('text和图片不可都为空');
                        }
                    } else {
                        return false;
                    }
                });
            },
            refresh() {
                this.getCriticals();
                this.critical = {};
                this.$refs.form.resetFields();
                this.dialogVisible = false;
            },
            uploadSuccess(response, file, fileList) {
                this.critical.images = fileList;
            },
            deleteSuccess(file, fileList) {
                this.critical.images = fileList;
            },
            del(index, row) {
                axios.delete('/criticals/' + row.id)
                    .then(response => {
                        this.getCriticals();
                    })
                    .catch(error => {
                        console.log(error)
                        this.$message.error('没有权限');
                    })
            },
            edit(index, row) {
                if (row.user_id == this.user.id || this.user.roles.indexOf('管理员') !== -1) {
                    this.critical = row;
                    this.showDialog();
                } else {
                    this.$message.error('只能编辑自己的标注');
                }
            },
            getImgList(imgs) {
                let list = [];
                for (let i = 0; i < imgs.length; i++) {
                    list.push(imgs[i].url);
                }
                return list;
            },
            currentChange(page) {
                this.getCriticals({'page': page});
            },
            preCLick(page) {
                this.getCriticals({'page': page - 1});
            },
            nextClick(page) {
                this.getCriticals({'page': page + 1});
            }
        },
        mounted() {
            this.getCriticals();
        }
    }
</script>

<style scoped>
    .pagination {
        text-align: center;
        margin: 20px;
    }
</style>